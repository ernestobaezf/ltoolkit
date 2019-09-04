<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit\Log\Formatters;


use Illuminate\Support\Facades\Config;
use LToolkit\Interfaces\ILogstashFormatter;
use Monolog\Formatter\LogstashFormatter as ParentFormatter;

class LogstashFormatter extends ParentFormatter implements ILogstashFormatter
{
    public function __construct($applicationName,
                                $systemName = null,
                                $extraPrefix = null,
                                $contextPrefix = 'ctxt_',
                                $version = ParentFormatter::V1)
    {
        ParentFormatter::__construct(Config::get("app.name", $applicationName),
            $systemName,
            $extraPrefix,
            $contextPrefix,
            $version);
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        /** @var CustomLogFormatter $formatter */
        $formatter = app(CustomLogFormatter::class);

        $record = ["message" => $formatter->format($record)];
        if ($this->version === self::V1) {
            $message = $this->formatV1($record);
        } else {
            $message = $this->formatV0($record);
        }

        $message["agent"] = array(
            'hostname' => env("HOSTNAME", $this->systemName),
            'name' => env("HOSTNAME", $this->systemName),
            'type' => "app-logs",
        );

        return $this->toJson($message) . "\n";
    }
}
