<?php
namespace Bankiru\IPTools;

use Bankiru\IPTools\Interfaces\RangeConverterInterface;
use Bankiru\IPTools\Interfaces\RangeFactoryInterface;
use Bankiru\IPTools\Interfaces\RangeInterface;

class RangeFactory implements RangeFactoryInterface
{
    /** @var string[] */
    protected static $converterClasses = array(
        'Bankiru\\IPTools\\Converters\\SingleIP',
        'Bankiru\\IPTools\\Converters\\Cidr',
        'Bankiru\\IPTools\\Converters\\Netmask',
        'Bankiru\\IPTools\\Converters\\Wildcard',
        'Bankiru\\IPTools\\Converters\\StartEnd',
    );

    /** @var RangeConverterInterface[] */
    protected static $converters = array();

    public function __construct()
    {
        if (!self::$converters) {
            foreach (self::$converterClasses as $converterClass) {
                self::$converters[] = new $converterClass;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function parse($stringValue)
    {
        foreach (self::$converters as $converter) {
            if ($converter->isValidString($stringValue)) {
                list($start, $end) = $converter->parse($stringValue);

                return new Range(new IP($start), new IP($end));
            }
        }

        throw new \InvalidArgumentException('Range "' . $stringValue . '" is invalid');
    }

    /**
     * @inheritdoc
     */
    public function stringify(RangeInterface $range)
    {
        foreach (self::$converters as $converter) {
            try {
                return $converter->stringify($range);
            } catch (\InvalidArgumentException $exception) {
                // trying next converter
            }
        }

        throw new \InvalidArgumentException('Range [' . $range->getStart() . '-' . $range->getEnd() . '] con not be stringified');
    }
}
