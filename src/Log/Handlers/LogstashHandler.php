<?php
/**
 * @author Ernesto Baez
 */

namespace l5toolkit\Log\Handlers;

use Monolog\Handler\SocketHandler;
use Monolog\Formatter\FormatterInterface;
use l5toolkit\Interfaces\ILogstashFormatter;
use l5toolkit\Log\Formatters\LogstashFormatter;

class LogstashHandler extends SocketHandler
{
    public function setFormatter(FormatterInterface $formatter)
    {
        if (!($formatter instanceof ILogstashFormatter)) {
            return $this->getDefaultFormatter();
        }

        return $formatter;
    }

    protected function getDefaultFormatter()
    {
        return new LogstashFormatter("");
    }
}
