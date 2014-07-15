<?php
namespace Bankiru\IPTools;

use Bankiru\IPTools\Interfaces\RangeFactoryInterface;
use Bankiru\IPTools\Interfaces\RangeInterface;

class Range implements RangeInterface
{
    protected static $factory;
    protected static $factoryClass = 'Bankiru\IPTools\RangeFactory';

    /** @var IP */
    protected $start;
    /** @var IP */
    protected $end;

    /**
     * @param  IP                        $start
     * @param  IP                        $end
     * @throws \InvalidArgumentException
     */
    public function __construct(IP $start, IP $end)
    {
        if ($start->getIntValue() > $end->getIntValue()) {
            throw new \InvalidArgumentException('Start IP should be less then end IP');
        }
        $this->start = $start;
        $this->end = $end;
    }

    public static function setFactoryClass($class)
    {
        if (!in_array('Bankiru\IPTools\Interfaces\RangeFactoryInterface', class_implements($class))) {
            throw new \InvalidArgumentException(
                $class . ' should implements Bankiru\IPTools\Interfaces\RangeFactoryInterface'
            );
        }

        self::$factoryClass = $class;
        self::$factory = null;
    }

    /**
     * @return RangeFactoryInterface
     */
    public static function getFactory()
    {
        if (self::$factory === null) {
            self::$factory = new self::$factoryClass;
        }

        return self::$factory;
    }

    /**
     * @inheritdoc
     */
    public static function fromString($stringValue)
    {
        return self::getFactory()->parse($stringValue);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return self::getFactory()->stringify($this);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return \Traversable An instance of an object implementing <b>Iterator</b> or
     *                      <b>Traversable</b>
     */
    public function getIterator()
    {
        return new RangeIterator($this);
    }

    /**
     * @return IP
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return IP
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param  IP   $ip
     * @return bool
     */
    public function includesIP(IP $ip)
    {
        return $this->start->getIntValue() <= $ip->getIntValue()
            && $this->end->getIntValue() >= $ip->getIntValue();
    }

}
