<?php
namespace Bankiru\IPTools\Converters;

use Bankiru\IPTools\Interfaces\RangeConverterInterface;
use Bankiru\IPTools\Interfaces\RangeInterface;

class SingleIP implements RangeConverterInterface
{
    /**
     * @inheritdoc
     */
    public function isValidString($stringValue)
    {
        return is_string($stringValue) && ip2long($stringValue) !== false;
    }

    /**
     * @inheritdoc
     */
    public function parse($stringValue)
    {
        if (!$this->isValidString($stringValue)) {
            throw new \InvalidArgumentException('Invalid IP string');
        }

        $ip = ip2long($stringValue);

        return array($ip, $ip);
    }

    /**
     * @inheritdoc
     */
    public function stringify(RangeInterface $range)
    {
        if ($range->getStart()->getIntValue() !== $range->getEnd()->getIntValue()) {
            throw new \InvalidArgumentException('SingleIP range accepts only ranges with equal start and end IPs');
        }

        return (string) $range->getStart();
    }
}
