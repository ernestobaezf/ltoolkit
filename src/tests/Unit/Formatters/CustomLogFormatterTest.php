<?php
/**
 * @author Ernesto Baez
 */


use l5toolkit\Facades\Math;
use Illuminate\Support\Facades\Config;
use l5toolkit\Test\Environment\TestCase;
use l5toolkit\Formatters\CustomLogFormatter;
use l5toolkit\Test\Environment\DynamicClass;
use l5toolkit\Test\Environment\StringSerializableClass;

class CustomLogFormatterTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_format()
    {
        global $logId;
        $time = explode(' ', microtime());
        $logId = sprintf('%d-%06d', $time[1], $time[0] * 1000000);

        $config = [
            "~credit_card",
        ];

        Config::set("l5toolkit.log.scrubber", $config);

        $date = new DateTime();

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
                "controller" => "",
                "response" => $data,
                "type" => "action"
            ],
            "level"=> 200,
            "level_name" => "INFO",
            "channel" => "local-ernesto",
            "datetime" => $date,
            "extra" => $data
        ];

        $SIMPLE_DATE = "Y-m-d H:i:s";

        $object = new CustomLogFormatter(null, $SIMPLE_DATE, false, true);

        $method = self::getMethod("format", CustomLogFormatter::class);
        $result = $method->invokeArgs($object, [$record]);

        self::assertIsString($result);
    }

    /**
     * @throws Exception
     */
    public function test_format_1()
    {
        global $logId;
        $time = explode(' ', microtime());
        $logId = sprintf('%d-%06d', $time[1], $time[0] * 1000000);

        $config = [
            "~credit_card",
        ];

        Config::set("l5toolkit.log.scrubber", $config);

        $date = new DateTime();

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
                "controller" => "",
                "response" => $data,
                "type" => "action"
            ],
            "level"=> 200,
            "level_name" => "INFO",
            "channel" => "local-ernesto",
            "datetime" => $date,
            "extra" => $data
        ];

        $SIMPLE_DATE = "Y-m-d H:i:s";

        $object = new CustomLogFormatter(null, $SIMPLE_DATE, false, true);

        $method = self::getMethod("format", CustomLogFormatter::class);
        $result = $method->invokeArgs($object, [$record]);

        self::assertIsString($result);
    }

    /**
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
                'exception' => new Exception(),
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
                'exception' => '[object] (Exception(code: 0):  at /home/ernesto/Projects/core-package/vendor/ernestobaezf/l5toolkit/src/tests/Unit/Formatters/CustomLogFormatterTest.php:136)',
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
     *
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
     *
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
     *
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
