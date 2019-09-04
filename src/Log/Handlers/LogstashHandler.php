<?php
/**
 * @author Ernesto Baez
 */

namespace ltoolkit\Log\Handlers;

use Monolog\Handler\SocketHandler;
use Monolog\Formatter\FormatterInterface;
use ltoolkit\Interfaces\ILogstashFormatter;
use ltoolkit\Log\Formatters\LogstashFormatter;

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
