<?php
/**
 * @author Ernesto Baez
 */

namespace l5toolkit\Test\Unit\Formatters;

use DateTime;
use Exception;
use l5toolkit\Facades\Math;
use Illuminate\Support\Facades\Config;
use l5toolkit\Test\Environment\TestCase;
use l5toolkit\Formatters\CustomLogFormatter;
use l5toolkit\Test\Environment\DynamicClass;
use l5toolkit\Test\Environment\StringSerializableClass;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomLogFormatterTest extends TestCase
{
    /**
     * Format the log in one string line
     *
     * @throws Exception
     */
    public function test_format()
    {
        $SIMPLE_FORMAT = "[%datetime%] %channel%.%level_name% %context% %extra% %message%\n";
        $SIMPLE_DATE = "Y-m-d H:i:s";

        global $logId;
        $logId = '1565630222-621879';

        $config = [
            "~credit_card",
        ];

        Config::set("l5toolkit.log.scrubber", $config);

        $date = DateTime::createFromFormat($SIMPLE_DATE, "2019-08-12 17:17:02");

        $data = [
            "date" => $date,
            "Credit_Card" => 100000,
            "array" => ["number_string" => "350"],
            "object" => new DynamicClass([
                "method" => function($param) {
                    return $param;
                }
            ])
        ];

        $record = [
            "message" => "Start execution",
            "context" => [
                "class" => "",
                "payload" => $data,
                "response" => $data,
                "type" => "action",
                "array" => ["key" => "value"]
            ],
            "level"=> 200,
            "level_name" => "INFO",
            "channel" => "local-ernesto",
            "datetime" => $date,
            "extra" => $data
        ];

        $object = new CustomLogFormatter($SIMPLE_FORMAT, $SIMPLE_DATE, false, true);

        $method = self::getMethod("format", CustomLogFormatter::class);
        $result = $method->invokeArgs($object, [$record]);

        self::assertIsString($result);
        self::assertEquals('[2019-08-12 17:17:02] local-ernesto.INFO {"class":"","payload":"{\"date\":\"2019-08-12 17:17:02\",\"Credit_Card\":\"[scrubbed value] ***\",\"array\":{\"number_string\":\"350\"},\"object\":\"[object] (l5toolkit\\\\\\\Test\\\\\\\Environment\\\\\\\DynamicClass: {})\"}","response":"{\"date\":\"2019-08-12 17:17:02\",\"Credit_Card\":\"[scrubbed value] ***\",\"array\":{\"number_string\":\"350\"},\"object\":\"[object] (l5toolkit\\\\\\\Test\\\\\\\Environment\\\\\\\DynamicClass: {})\"}","type":"action","array":"{\"key\":\"value\"}","controller":"","action":"","referer":null,"ip":"127.0.0.1","user":"unknown","logId":"1565630222-621879"} {"date":"2019-08-12 17:17:02","Credit_Card":"[scrubbed value] ***","array":"{\"number_string\":\"350\"}","object":"[object] (l5toolkit\\\\Test\\\\Environment\\\\DynamicClass: {})"} Start execution
',
            $result);
    }

    /**
     * Format the log in one string line
     *
     * @throws Exception
     */
    public function test_format_1()
    {
        $SIMPLE_FORMAT =
            "[%datetime%] %channel%.%level_name% %context% %context.payload% %extra% %extra.date% %message%\n";
        $SIMPLE_DATE = "Y-m-d H:i:s";

        global $logId;
        $logId = '1565630222-621879';

        $config = [
            "~credit_card",
        ];

        Config::set("l5toolkit.log.scrubber", $config);

        $date = DateTime::createFromFormat($SIMPLE_DATE, "2019-08-12 17:17:02");

        $data = [
            "date" => $date,
            "Credit_Card" => 100000,
            "array" => ["number_string" => "350"],
            "object" => new DynamicClass([
                "method" => function($param) {
                    return $param;
                }
            ])
        ];

        Config::set("l5toolkit.log_text_length", 118);
        $record = [
            "message" => "Start execution",
            "context" => [
                "class" => "",
                "payload" => $data,
                "response" => $data,
                "type" => "action"
            ],
            "level"=> 200,
            "level_name" => "INFO",
            "channel" => "local-ernesto",
            "datetime" => $date,
            "extra" => $data
        ];

        $object = new CustomLogFormatter($SIMPLE_FORMAT, $SIMPLE_DATE, false, true);

        $method = self::getMethod("format", CustomLogFormatter::class);
        $result = $method->invokeArgs($object, [$record]);

        self::assertIsString($result);
        self::assertEquals('[2019-08-12 17:17:02] local-ernesto.INFO {"class":"","response":"{\"date\":\"2019-08-12 17:17:02\",\"Credit_Card\":\"[scrubbed value] ***\",\"array\":{\"number_string\":\"350\"},\"object\":\"[object] truncated text...","type":"action","controller":"","action":"","referer":null,"ip":"127.0.0.1","user":"unknown","logId":"1565630222-621879"} {"date":"2019-08-12 17:17:02","Credit_Card":"[scrubbed value] ***","array":{"number_string":"350"},"object":"[object] (l5toolkit\\\Test\\\Environment\\\DynamicClass: {})"} {"Credit_Card":"[scrubbed value] ***","array":"{\"number_string\":\"350\"}","object":"[object] (l5toolkit\\\Test\\\Environment\\\DynamicClass: {})"} 2019-08-12 17:17:02 Start execution
',
            $result);
    }

    /**
     * Format the log in one string line
     *
     * @throws Exception
     */
    public function test_format_2()
    {
        $SIMPLE_FORMAT =
            "[%datetime%] %channel%.%level_name% %context% %context.leftover% %context.response% %context.action% 
            %context.referer% %context.ip% %context.user% %context.logId% %context.leftover% %context.response% 
            %context.payload% %context.controller% %extra% %extra.date% %extra.leftover% %message%\n";
        $SIMPLE_DATE = "Y-m-d H:i:s";

        global $logId;
        $logId = '1565630222-621879';

        $config = [
            "~credit_card",
        ];

        Config::set("l5toolkit.log.scrubber", $config);

        $date = DateTime::createFromFormat($SIMPLE_DATE, "2019-08-12 17:17:02");

        $data = ["date" => $date];
        $response = "";
        for ($i=0; $i < 705; $i++) {
            $response .= $i;
        }

        $record = [
            "message" => "Start execution",
            "context" => [
                "response" => $response
            ],
            "level"=> 200,
            "level_name" => "INFO",
            "channel" => "local-ernesto",
            "datetime" => $date,
            "extra" => $data
        ];

        $object = new CustomLogFormatter($SIMPLE_FORMAT, $SIMPLE_DATE, false, true);

        $method = self::getMethod("format", CustomLogFormatter::class);
        $result = $method->invokeArgs($object, [$record]);

        self::assertIsString($result);
    }

    /**
     * Format the log in one string line
     *
     * @throws Exception
     */
    public function test_normalize()
    {
        $SIMPLE_DATE = "Y-m-d H:i:s";
        $file = fopen('http://www.google.com', 'r');

        $date = new DateTime();
        $record = [
            "message" => "Start execution",
            "context" => [
                'exception' => new ModelNotFoundException("Test Exception"),
                "resource" => $file,
                "infinite" => Math::log(0),
                "nan" => Math::acos(8),
                "stringObject" => new StringSerializableClass(),
                "float" => 0.1,
                "date" => $date,
                "int" => 100000,
                "array" => ["number_string" => "350"],
                "object" => new DynamicClass([
                    "method" => function($param) {
                        return $param;
                    }
                ])
            ],
            "level"=> 200,
            "level_name" => "INFO",
            "channel" => "local-ernesto",
            "datetime" => $date,
            "extra" => []
        ];

        $object = $this->getMockBuilder(CustomLogFormatter::class)
            ->setConstructorArgs([null, $SIMPLE_DATE])
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->getMock();

        $method = self::getMethod("normalize", CustomLogFormatter::class);
        $result = $method->invokeArgs($object, [$record]);
        fclose($file);

        self::assertEquals([
            'message' => 'Start execution',
            'context' => [
                'exception' => '[object] (Illuminate\Database\Eloquent\ModelNotFoundException(code: 0): Test Exception at /home/ernesto/Projects/core-package/vendor/ernestobaezf/l5toolkit/src/tests/Unit/Formatters/CustomLogFormatterTest.php:203)',
                'date' => $date->format($SIMPLE_DATE),
                'int' => 100000,
                'array' => ["number_string" => "350"],
                'infinite' => '-INF',
                'nan' => 'NaN',
                'stringObject' => '[object] (l5toolkit\Test\Environment\StringSerializableClass: l5toolkit\Test\Environment\StringSerializableClass)',
                'float' => 0.1,
                'resource' => '[resource] (stream)',
                'object' => '[object] (l5toolkit\Test\Environment\DynamicClass: {})'],
            'level' => 200,
            'level_name' => 'INFO',
            'channel' => 'local-ernesto',
            'datetime' => $date->format($SIMPLE_DATE),
            'extra' => []], $result);
    }

    /**
     * Normalized the record data to be formatted
     */
    public function test_normalize_1()
    {
        $SIMPLE_DATE = "Y-m-d H:i:s";

        $level9 = [];
        $level = &$level9;
        for($i=0; $i <= 9; $i++) {
            $level["level$i"] = [];
            $level = &$level["level$i"];
        }

        $overflow = [];
        for($i=0; $i <= 1001; $i++) {
            $overflow["item$i"] = $i;
        }

        $record = [
            "message" => "Start execution",
            "levels" => $level9,
            "overflow" => $overflow,
            "level_name" => 'INFO',
        ];

        $object = $this->getMockBuilder(CustomLogFormatter::class)
            ->setConstructorArgs([null, $SIMPLE_DATE])
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->getMock();

        $method = self::getMethod("normalize", CustomLogFormatter::class);
        $result = $method->invokeArgs($object, [$record]);

        $overflow = [];
        for($i=0; $i <= 999; $i++) {
            $overflow["item$i"] = $i;
        }

        $overflow["..."] = "Over 1000 items (1002 total), aborting normalization";

        self::assertEquals([
            'message' => 'Start execution',
            'levels' => [
                'level0' => [
                    'level1' => [
                        'level2' => [
                            'level3' => [
                                'level4' => [
                                    'level5' => [
                                        'level6' => [
                                            'level7' => [
                                                'level8' => 'Over 9 levels deep, aborting normalization'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'overflow' => $overflow,
            'level_name' => 'INFO'], $result);
    }

    /**
     * Normalized the record data to be formatted
     */
    public function test_normalize_2()
    {
        $SIMPLE_DATE = "Y-m-d H:i:s";

        $record = [
            "message" => "Start execution",
            "unknown" => new DynamicClass([]),
            "level_name" => 'INFO',
        ];

        $object = $this->getMockBuilder(CustomLogFormatter::class)
            ->setConstructorArgs([null, $SIMPLE_DATE])
            ->disableArgumentCloning()
            ->setMethods(["isObject"])
            ->disableOriginalClone()
            ->getMock();

        $object->method("isObject")->willReturn(false);

        $method = self::getMethod("normalize", CustomLogFormatter::class);
        $result = $method->invokeArgs($object, [$record]);

        self::assertEquals([
            'message' => 'Start execution',
            'unknown' => '[unknown(object)]',
            'level_name' => 'INFO'], $result);
    }

    /**
     * Get a list of variations of keys to be hidden in the log
     */
    public function test_generateScrubList()
    {
        $config = [
            "~password",
            "~credit_card_number",
            "cC",
            "~cc"
        ];

        Config::set("l5toolkit.log.scrubber", $config);

        $object = $this->getMockBuilder(CustomLogFormatter::class)
            ->setConstructorArgs([true])
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->getMock();

        $method = self::getMethod("generateScrubList", CustomLogFormatter::class);
        $result = $method->invoke($object);

        self::assertEquals([
            'password',
            'PASSWORD',
            'Password',
            'credit_card_number',
            'creditCardNumber',
            'CREDIT_CARD_NUMBER',
            'Credit_Card_Number',
            'CreditCardNumber',
            'Credit_card_number',
            'credit card number',
            'CREDIT CARD NUMBER',
            'Credit Card Number',
            'Credit card number',
            'cC',
            'cc',
            'CC',
            'Cc'], $result);
    }
}
