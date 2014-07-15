<?php
namespace Bankiru\IPTools;

class RangeIteratorTest extends \PHPUnit_Framework_TestCase
{
    /** @var RangeIterator */
    private $instance;

    /** @var IP */
    private $ipStart;

    /** @var IP */
    private $ipEnd;

    protected function setUp()
    {
        $this->ipStart = new IP(TestDataGenerator::ipInt());
        $this->ipEnd = new IP($this->ipStart->getIntValue() + 2);
        $this->instance = new RangeIterator(
            new Range($this->ipStart, $this->ipEnd)
        );
    }

    public function testMethods()
    {
        $this->assertEquals($this->ipStart->getIntValue(), $this->instance->key());
        $this->assertEquals($this->ipStart, $this->instance->current());
        $this->assertTrue($this->instance->valid());

        $this->instance->next();
        $this->assertEquals($this->ipStart->getIntValue()+1, $this->instance->key());
        $this->assertEquals(new IP($this->ipStart->getIntValue() + 1), $this->instance->current());
        $this->assertTrue($this->instance->valid());

        $this->instance->next();
        $this->assertEquals($this->ipEnd->getIntValue(), $this->instance->key());
        $this->assertEquals($this->ipEnd, $this->instance->current());
        $this->assertTrue($this->instance->valid());

        $this->instance->next();
        $this->assertNull($this->instance->key());
        $this->assertFalse($this->instance->valid());

        $this->instance->rewind();
        $this->assertEquals($this->ipStart->getIntValue(), $this->instance->key());
        $this->assertEquals($this->ipStart, $this->instance->current());
        $this->assertTrue($this->instance->valid());
    }

    public function testForeach()
    {
        $i = 0;
        $n = 2;
        /** @var IP $prevIp */
        $prevIp = null;
        foreach ($this->instance as $key => $ip) {
            if ($i == 0) {
                $this->assertEquals($this->ipStart->getIntValue(), $key);
                $this->assertEquals($this->ipStart, $ip);
            } elseif ($i < $n) {
                $this->assertEquals($prevIp->getIntValue() + 1, $key);
                $this->assertEquals(new IP($prevIp->getIntValue() + 1), $ip);
            } else {
                $this->assertEquals($this->ipEnd->getIntValue(), $key);
                $this->assertEquals($this->ipEnd, $ip);
            }
            $prevIp = $ip;
            $i++;
        }

    }

}
