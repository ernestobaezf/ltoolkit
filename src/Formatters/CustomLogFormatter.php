<?php
/**
 * @author Ernesto Baez
 */

namespace l5toolkit\Formatters;


use DateTime;
use Countable;
use Exception;
use Throwable;
use JsonSerializable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Monolog\Formatter\LineFormatter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class CustomLogFormatter extends LineFormatter
{
    const SIMPLE_FORMAT = "[%datetime%] %channel%.%level_name% %context% %extra% %message%\n";

    private const KEY = 'l5toolkit.log.scrubber';
    private $scrub = [];

    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        global $logId;

        $vars = $this->normalize($record);

        $output = $this->format;

        foreach ($vars['extra'] as $var => $val) {
            if (false !== strpos($output, '%extra.'.$var.'%')) {
                $output = str_replace('%extra.'.$var.'%', $this->stringify($val), $output);
                unset($vars['extra'][$var]);
            } elseif (is_array($val) || $val instanceof Countable) {
                $vars['extra'][$var] = json_encode($val);
            }
        }

        $currentRoute = Route::current();
        if (!isset($vars['context']['controller'])) {
            $actionName = $currentRoute ? $currentRoute->getActionName() : "";
            $path = explode("@", $actionName ?: "@");

            $vars['context']['controller'] = $path[0] ?? "Unknown";
            $vars['context']['action'] = $path[1] ?? "Unknown";
        }

        $vars['context']['referer'] = Request::server('HTTP_REFERER');
        $vars['context']['ip'] = Request::ip();

        $user = Auth::user();
        $_user = $user->username ?? null;
        $vars['context']['user'] = $_user ? "(".$user->id.") ".$_user : "unknown";

        if (!isset($vars['context']['payload'])) {
            /** @var array $request */
            $request = request()->all();
            $payload = $request ?: [];

            $parameters = $currentRoute ? $currentRoute->originalParameters() : [];
            $vars['context']['payload'] = array_merge($payload, $parameters);
        }

        $payload = $vars['context']['payload'];
        if (!is_string($payload)) {
            $payload = json_encode($payload);
        }

        $vars['context']['payload'] = $payload;

        if ($logId) {
            $vars['context']['logId'] = $logId;
        }

        if (isset($vars['context']['response'])) {
            $response = $vars['context']['response'];
            if (!is_string($response)) {
                $response = json_encode($response);
            }

            $logTextLength = Config::get("l5toolkit.log_text_length", 3000);
            if ($logTextLength > 0 && strlen($response) > $logTextLength) {
                $response = substr($response, 0, $logTextLength). "truncated text...";
            }

            $vars['context']['response'] = $response;
        }

        foreach ($vars['context'] as $var => $val) {
            if (false !== strpos($output, '%context.'.$var.'%')) {
                $output = str_replace('%context.'.$var.'%', $this->stringify($val), $output);
                unset($vars['context'][$var]);
            } elseif (is_array($val) || $val instanceof Countable) {
                $vars['context'][$var] = json_encode($val);
            }
        }

        if ($this->ignoreEmptyContextAndExtra) {
            if (empty($vars['context'])) {
                unset($vars['context']);
                $output = str_replace('%context%', '', $output);
            }

            if (empty($vars['extra'])) {
                unset($vars['extra']);
                $output = str_replace('%extra%', '', $output);
            }
        }

        foreach ($vars as $var => $val) {
            if (false !== strpos($output, '%'.$var.'%')) {
                $output = str_replace('%'.$var.'%', $this->stringify($val), $output);
            }
        }

        // remove leftover %extra.xxx% and %context.xxx% if any
        if (false !== strpos($output, '%')) {
            $output = preg_replace('/%(?:extra|context)\..+?%/', '', $output);
        }

        return $output;
    }

    protected function normalize($data, $depth = 0)
    {
        if ($depth > 9) {
            return 'Over 9 levels deep, aborting normalization';
        }

        if (null === $data || is_scalar($data)) {
            if (is_float($data)) {
                if (is_infinite($data)) {
                    return ($data > 0 ? '' : '-') . 'INF';
                }
                if (is_nan($data)) {
                    return 'NaN';
                }
            }

            if (is_string($data)) {
                $data = $this->scrubFromString($data);
            }

            return $data;
        }

        if (is_array($data)) {
            $normalized = array();

            $count = 1;
            foreach ($data as $key => $value) {
                if ($count++ > 1000) {
                    $normalized['...'] = 'Over 1000 items ('.count($data).' total), aborting normalization';
                    break;
                }

                if (in_array($key, $this->getScrubList())) {
                    $normalized[$key] = "[scrubbed value] ***";
                } else {
                    $normalized[$key] = $this->normalize($value, $depth+1);
                }
            }

            return $normalized;
        }

        if ($data instanceof DateTime) {
            return $data->format($this->dateFormat);
        }

        if ($this->isObject($data)) {
            // TODO 2.0 only check for Throwable
            if ($data instanceof Exception || (PHP_VERSION_ID > 70000 && $data instanceof Throwable)) {
                return $this->normalizeException($data);
            }

            // non-serializable objects that implement __toString
            if (method_exists($data, '__toString') && !$data instanceof JsonSerializable) {
                $value = $data->__toString();
            } else {
                // the rest is json-serialized in some way
                $value = $this->toJson($data, true);
            }

            $value = $this->scrubFromString($value);

            return sprintf("[object] (%s: %s)", $this::getClass($data), $value);
        }

        if (is_resource($data)) {
            return sprintf('[resource] (%s)', get_resource_type($data));
        }

        return '[unknown('.gettype($data).')]';
    }

    private static function getClass($object)
    {
        $class = get_class($object);

        return 'c' === $class[0] && 0 === strpos($class, "class@anonymous\0") ? get_parent_class($class).'@anonymous' : $class;
    }

    protected function isObject($object)
    {
        return is_object($object);
    }

    private function getScrubList(): array
    {
        if (!$this->scrub) {
            // store for a week
            $ttl = 7 * 24 * 3600;
            $this->scrub = Cache::remember(self::KEY, $ttl,
                function ()
                {
                    return $this->generateScrubList();
                }
            );
        }

        return $this->scrub;
    }

    protected function generateScrubList(): array
    {
        $result = [];

        $process = function ($item, &$result) {
            $result[$item] = $item;

            $value = Str::camel($item);
            $result[$value] = $value;

            $value = Str::upper($item);
            $result[$value] = $value;

            $value = Str::title($item);
            $result[$value] = $value;

            $value = Str::snake($item);
            $result[$value] = $value;

            $value = Str::ucfirst(Str::camel($item));
            $result[$value] = $value;

            $value = Str::ucfirst($item);
            $result[$value] = $value;
        };

        $scrubber = Config::get(self::KEY) ?: [];
        foreach ($scrubber as $item) {
            if (Str::startsWith($item, "~")) {
                $_item = Str::substr($item, 1);
                $process($_item, $result);

                $_item = implode(" ", explode("_", $_item));
                $process($_item, $result);

            } else {
                $result[$item] = $item;
            }
        }

        return array_keys($result);
    }

    /**
     * Scrub data from string
     *
     * @param string $value
     * @return string|string[]|null
     */
    private function scrubFromString(string $value)
    {
        $list = implode("|", $this->getScrubList());

        $pattern = '/("(' . $list . ')"):"([^"]*)"/i';
        $replacement = '$1:"[scrubbed value] ***"';
        $value = preg_replace($pattern, $replacement, $value);

        return $value;
    }
}
