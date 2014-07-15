<?php
namespace Bankiru\IPTools\Converters;

use Bankiru\IPTools\TestDataGenerator;

class CidrTest extends ConvertersTestBase
{
    /**
     * @return array
     */
    public function provide_valid_parse()
    {
        return array(
            array('193.168.0.1/32', ip2long('193.168.0.1'), ip2long('193.168.0.1')),
            array('10.8.3.4/24', ip2long('10.8.3.0'), ip2long('10.8.3.255')),
            array('10.100.3.4/16', ip2long('10.100.0.0'), ip2long('10.100.255.255')),
            array('192.168.128.33/17', ip2long('192.168.128.0'), ip2long('192.168.255.255')),
            array(TestDataGenerator::ipString() . '/0', ip2long('0.0.0.0'), ip2long('255.255.255.255')),
        );
    }

    /**
     * @return array
     */
    public function provide_invalid_parse()
    {
        return array(
            array('192.168.10.1/-1'),
            array('192.168.9.1/asdasd'),
            array('192.168.8.1/33'),
            array('1.1.0/32'),
            array('8.8.8.8'),
        );
    }

    /**
     * @return array
     */
    public function provide_valid_stringify()
    {
        return array(
            array('192.168.0.7', '192.168.0.7', '192.168.0.7/32'),
            array('192.168.0.0', '192.168.0.255', '192.168.0.0/24'),
            array('10.0.168.5', '10.0.169.100', '10.0.168.5/32,10.0.168.6/31,10.0.168.8/29,10.0.168.16/28,10.0.168.32/27,10.0.168.64/26,10.0.168.128/25,10.0.169.0/26,10.0.169.64/27,10.0.169.96/30,10.0.169.100/32'),
        );
    }

    /**
     * @return array
     */
    public function provide_invalid_stringify()
    {
        return array(array('192.168.0.7', '192.168.0.6'));
    }
}
