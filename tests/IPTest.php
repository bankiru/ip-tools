<?php
namespace Bankiru\IPTools;

class IPTest extends \PHPUnit_Framework_TestCase
{
    const DATA_SET_LENGTH = 20;

    /**
     * @dataProvider provideConstructValid
     */
    public function testConstructOk($value, $expected)
    {
        $ip = new IP($value);
        $this->assertEquals($expected, $ip->getIntValue());
    }

    public function provideConstructValid()
    {
        $data = array();

        for ($i=0;$i<self::DATA_SET_LENGTH;$i++) {
            $intValue = TestDataGenerator::ipInt();
            $data[] = array($intValue, $intValue);
        }

        for ($i=0;$i<self::DATA_SET_LENGTH;$i++) {
            $stringValue = TestDataGenerator::ipString();
            $data[] = array($stringValue, ip2long($stringValue));
        }

        return $data;
    }

    /**
     * @dataProvider provideConstructInvalid
     * @expectedException \InvalidArgumentException
     */
    public function testConstructFail($value)
    {
        new IP($value);
        $this->fail('Construct should fail on ' . $value);
    }

    public function provideConstructInvalid()
    {
        return array(
            array(-1),
            array(IP::MAX_INT_VALUE+1),
            array('127.0.0.'),
            array('192.168.255'),
            array('192.168.*.7'),
            array('192.168.0.1/27'),
            array('8.8.8.8.8'),
            array('192.168.256.7'),
            array('192.168.43.100-192.168.45.7'),
        );
    }

    public function testToString()
    {
        $stringValue = TestDataGenerator::ipString();
        $ip = new IP($stringValue);
        $this->assertEquals($stringValue, (string) $ip);
    }
}
