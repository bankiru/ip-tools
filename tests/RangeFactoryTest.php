<?php
namespace Bankiru\IPTools;

class RangeFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \ReflectionProperty */
    private $refProp;

    /** @var RangeFactory */
    private $instance;

    protected function setUp()
    {
        $this->instance = new RangeFactory();
        $refObj = new \ReflectionObject($this->instance);
        $this->refProp = $refObj->getProperty('converters');
        $this->refProp->setAccessible(true);
    }

    protected function tearDown()
    {
        $this->refProp->setValue(null, array());
        $this->refProp->setAccessible(false);
    }

    public function testConstruct()
    {
        $this->assertGreaterThan(0, count($this->refProp->getValue()));
    }

    public function testParse()
    {
        $ip = TestDataGenerator::ipInt();

        $mockConverter = $this->getMock('Bankiru\IPTools\Interfaces\RangeConverterInterface');

        $mockConverter->expects($this->once())
            ->method('isValidString')
            ->with($this->equalTo($ip))
            ->willReturn(true);

        $mockConverter->expects($this->once())
            ->method('parse')
            ->with($this->equalTo($ip))
            ->willReturn(array($ip, $ip));

        $this->refProp->setValue(null, array($mockConverter));

        $range = $this->instance->parse($ip);

        $this->assertInstanceOf('Bankiru\IPTools\Interfaces\RangeInterface', $range);
        $this->assertEquals($ip, $range->getStart()->getIntValue());
        $this->assertEquals($ip, $range->getEnd()->getIntValue());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Range "invalid_range_string" is invalid
     */
    public function testParseFail()
    {
        $ip = 'invalid_range_string';

        $mockConverter = $this->getMock('Bankiru\IPTools\Interfaces\RangeConverterInterface');

        $mockConverter->expects($this->once())
            ->method('isValidString')
            ->with($this->equalTo($ip))
            ->willReturn(false);

        $this->refProp->setValue(null, array($mockConverter));

        $this->instance->parse($ip);

        $this->fail('parse should fail on invalid input');
    }

    public function testStringify()
    {
        $ip = TestDataGenerator::ipString();
        $range = new Range(new IP($ip), new IP($ip));

        $mockConverter = $this->getMock('Bankiru\IPTools\Interfaces\RangeConverterInterface');

        $mockConverter->expects($this->once())
            ->method('stringify')
            ->with($this->equalTo($range))
            ->willReturn($ip);

        $this->refProp->setValue(null, array($mockConverter));

        $actual = $this->instance->stringify($range);

        $this->assertInternalType('string', $actual);
        $this->assertEquals($ip, $actual);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStringifyFail()
    {
        $ip = TestDataGenerator::ipString();
        $range = new Range(new IP($ip), new IP($ip));

        $mockConverter = $this->getMock('Bankiru\IPTools\Interfaces\RangeConverterInterface');

        $mockConverter->expects($this->once())
            ->method('stringify')
            ->with($this->equalTo($range))
            ->willThrowException(new \InvalidArgumentException('INVALID RANGE'));

        $this->refProp->setValue(null, array($mockConverter));

        $this->instance->stringify($range);

        $this->fail('parse should fail on invalid input');
    }
}
