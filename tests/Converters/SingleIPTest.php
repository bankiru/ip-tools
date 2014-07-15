<?php
namespace Bankiru\IPTools\Converters;

use Bankiru\IPTools\IP;
use Bankiru\IPTools\TestDataGenerator;

class SingleIPTest extends ConvertersTestBase
{
    /**
     * @inheritdoc
     */
    public function provide_valid_parse()
    {
        $data = array();

        for ($i = 0; $i < self::DATA_SET_LENGTH; $i++) {
            $stringValue = TestDataGenerator::ipString();
            $intValue = ip2long($stringValue);
            $data[] = array($stringValue, $intValue, $intValue);
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function provide_invalid_parse()
    {
        return array(
            array(-1),
            array(IP::MAX_INT_VALUE + 1),
            array('127.0.0.'),
            array('192.168.255'),
            array('192.168.*.7'),
            array('192.168.0.1/27'),
            array('8.8.8.8.8'),
            array('192.168.256.7'),
            array('192.168.43.100-192.168.45.7'),
        );
    }

    /**
     * @inheritdoc
     */
    public function provide_valid_stringify()
    {
        return array_map(
            function ($item) {
                array_push($item, array_shift($item));

                return $item;
            },
            $this->provide_valid_parse()
        );
    }

    /**
     * @inheritdoc
     */
    public function provide_invalid_stringify()
    {
        return array(array('192.168.0.7', '192.168.0.8'));
    }
}
