<?php
namespace Bankiru\IPTools;

class RangeTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructSuccess()
    {
        $ip = new IP(TestDataGenerator::ipInt());
        $range = new Range($ip, $ip);
        $this->assertInstanceOf('Bankiru\IPTools\Range', $range);
        $this->assertEquals($ip->getIntValue(), $range->getStart()->getIntValue());
        $this->assertEquals($ip->getIntValue(), $range->getEnd()->getIntValue());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructFail()
    {
        $ip = TestDataGenerator::ipInt();
        new Range(new IP($ip), new IP($ip - 1));
        $this->fail('Constructor should fail on invalid params');
    }

    /**
     * @depends testConstructSuccess
     */
    public function testIncludesIP()
    {
        $ip = TestDataGenerator::ipInt();
        $range = new Range(new IP($ip), new IP($ip));

        $this->assertTrue($range->includesIP(new IP($ip)));
        $this->assertFalse($range->includesIP(new IP($ip - 1)));
        $this->assertFalse($range->includesIP(new IP($ip + 1)));
    }

    public function testGetIterator()
    {
        $ip = TestDataGenerator::ipInt();
        $range = new Range(new IP($ip), new IP($ip));

        $this->assertInstanceOf('Bankiru\IPTools\Interfaces\RangeIteratorInterface', $range->getIterator());
    }

    public function testSetFactoryClass()
    {
        $class = $this->getMockClass('Bankiru\IPTools\Interfaces\RangeFactoryInterface');
        Range::setFactoryClass($class);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetFactoryClassFail()
    {
        Range::setFactoryClass('\InvalidArgumentException');
    }

    public function testFromString()
    {
        $ip = TestDataGenerator::ipString();
        $range = new Range(new IP($ip), new IP($ip));

        $mock = $this->getMock('Bankiru\IPTools\Interfaces\RangeFactoryInterface');
        $mock->expects($this->once())
            ->method('parse')
            ->with($this->equalTo($ip))
            ->willReturn($range);

        $refClass = new \ReflectionClass('Bankiru\IPTools\Range');
        $refProp = $refClass->getProperty('factory');
        $refProp->setAccessible(true);
        $refProp->setValue(null, $mock);
        $refProp->setAccessible(false);

        $this->assertEquals($range, Range::fromString($ip));
    }

    public function testToString()
    {
        $ip = TestDataGenerator::ipString();
        $range = new Range(new IP($ip), new IP($ip));

        $mock = $this->getMock('Bankiru\IPTools\Interfaces\RangeFactoryInterface');
        $mock->expects($this->once())
            ->method('stringify')
            ->with($this->equalTo($range))
            ->willReturn($ip);

        $refClass = new \ReflectionClass('Bankiru\IPTools\Range');
        $refProp = $refClass->getProperty('factory');
        $refProp->setAccessible(true);
        $refProp->setValue(null, $mock);
        $refProp->setAccessible(false);

        $this->assertEquals($ip, (string) $range);
    }

    public function testGetFactory()
    {
        $factory = Range::getFactory();

        $this->assertInstanceOf('Bankiru\IPTools\Interfaces\RangeFactoryInterface', $factory);
    }

    protected function tearDown()
    {
        $refClass = new \ReflectionClass('Bankiru\IPTools\Range');
        $refProp = $refClass->getProperty('factory');
        $refProp->setAccessible(true);
        $refProp->setValue(null, null);
        $refProp->setAccessible(false);
    }

}
