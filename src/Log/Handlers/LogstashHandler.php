<?php
/**
 * @author Ernesto Baez
 */

use Monolog\Handler\SocketHandler;
use l5toolkit\Log\Formatters\LogstashFormatter;

class LogstashHandler extends SocketHandler
{
    protected function getDefaultFormatter()
    {
        return new LogstashFormatter("");
    }
}
