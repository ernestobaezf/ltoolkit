<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit\Log\Handlers;

use Monolog\Handler\SocketHandler;
use Monolog\Formatter\FormatterInterface;
use LToolkit\Interfaces\LogstashFormatterInterface;
use LToolkit\Log\Formatters\LogstashFormatter;

class LogstashHandler extends SocketHandler
{
    public function setFormatter(FormatterInterface $formatter)
    {
        if (!($formatter instanceof LogstashFormatterInterface)) {
            return $this->getDefaultFormatter();
        }

        return $formatter;
    }

    protected function getDefaultFormatter()
    {
        return new LogstashFormatter("");
    }
}
