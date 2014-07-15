<?php
namespace Bankiru\IPTools\Converters;

class StartEndTest extends ConvertersTestBase
{
    /**
     * @return array
     */
    public function provide_valid_parse()
    {
        return array(
            array('192.168.0.7-192.168.0.7', ip2long('192.168.0.7'), ip2long('192.168.0.7')),
            array('193.168.0.7-194.168.1.7', ip2long('193.168.0.7'), ip2long('194.168.1.7')),
            array('0.0.0.0-255.255.255.255', ip2long('0.0.0.0'), ip2long('255.255.255.255')),
        );
    }

    /**
     * @return array
     */
    public function provide_invalid_parse()
    {
        return array(
            array('23.14.77.19'),
            array('192.168.*.7'),
            array('192.168.0.1/24'),
            array('192.168.45.100-192.168.43.7'),
            array('1.0.0.1/256.0.0.0'),
            array('1.0.256.1/255.0.0.0'),
        );
    }

    /**
     * @return array
     */
    public function provide_valid_stringify()
    {
        return array(
            array('192.168.0.7', '192.168.0.7', '192.168.0.7-192.168.0.7'),
            array('192.168.0.0', '192.168.0.255', '192.168.0.0-192.168.0.255'),
            array('10.0.168.5', '10.0.169.100', '10.0.168.5-10.0.169.100'),
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
