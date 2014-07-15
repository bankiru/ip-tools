<?php
namespace Bankiru\IPTools;

class IPToolsTestDisabled extends \PHPUnit_Framework_TestCase
{
    /** @var IPTools */
    private $instance;

    protected function setUp()
    {
        $this->instance = new IPTools;
    }

    /**
     * @dataProvider ipIsValidProvide
     */
    public function testIpIsValid($ip, $expected)
    {
        $this->assertEquals($expected, $this->instance->ipIsValid($ip), "IP $ip is " . ($expected?:'in') . "valid");
    }

    public function ipIsValidProvide()
    {
        return array(
            array('23.14.77.1', true),
            array('0.0.0.0', true),
            array('127.0.0.2', true),
            array('127.0.0.', false),
            array('192.168.255', false),
            array('192.168.*.7', false),
            array('192.168.255.1', true),
            array('192.168.0.1/27', false),
            array('8.8.8.8.8', false),
            array('192.168.256.7', false),
            array('192.168.43.100-192.168.45.7', false),
        );
    }

    /**
     * @dataProvider rangeIsValidProvide
     */
    public function testRangeIsValid($range, $expected)
    {
        $this->assertEquals($expected, $this->instance->rangeIsValid($range), "RangeStub $range is " . ($expected?'':'in') . "valid");
    }

    public function rangeIsValidProvide()
    {
        return array(
            array('23.14.77.1', true),
            array('0.0.0.0', true),
            array('127.0.0.', false),
            array('192.168.*.7', true),
            array('192.168.0.1/27', true),
            array('192.168.0.1/-1', false),
            array('192.168.0.1/asdasd', false),
            array('192.168.0.1/33', false),
            array('8.8.8.8.8', false),
            array('192.168.256.7', false),
            array('192.168.43.100-192.168.45.7', true),
            array('192.168.*.100-192.168.45.7', false),
            array('192.168.43.100-192.168.45.7/7', false),
        );
    }

    /**
     * @dataProvider ipMatchProvideValid
     */
    public function testIpMatched($ip, $range)
    {
        $this->assertTrue($this->instance->ipMatch($ip, $range), "$ip should match with $range");
    }

    public function ipMatchProvideValid()
    {
        return array(
            array('23.14.77.19', '23.14.77.19'),
            array('127.0.0.1', '127.0.0.1/32'),
            array('192.168.33.8', '192.168.*.7'),
            array('192.168.255.1', '192.168.0.1/24'),
            array('10.73.24.6', '10.73.0.1/16'),
            array('8.8.8.8', '8.0.0.1/8'),
            array('192.168.44.9', '192.168.43.100-192.168.45.7'),
        );
    }

    /**
     * @dataProvider ipMatchProvideInvalid
     */
    public function testIpNotMatched($ip, $range)
    {
        $this->assertFalse($this->instance->ipMatch($ip, $range), "$ip should not match with $range");
    }

    public function ipMatchProvideInvalid()
    {
        return array(
            array('23.14.77.19', '23.14.77.29'),
            array('127.0.0.2', '127.0.0.1/32'),
            array('192.168.255.8', '192.168.*.7'),
            array('192.168.255.1', '192.168.0.1/27'),
            array('10.73.24.6', '10.74.24.1/16'),
            array('8.8.8.8', '8.9.8.1/8'),
            array('192.168.43.99', '192.168.43.100-192.168.45.7'),
        );
    }
}
