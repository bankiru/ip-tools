<?php
namespace Bankiru\IPTools\Converters;

use Bankiru\IPTools\Interfaces\RangeConverterInterface;
use Bankiru\IPTools\Interfaces\RangeInterface;

class StartEnd implements RangeConverterInterface
{
    private $singleIPValidator;

    public function __construct()
    {
        $this->singleIPValidator = new SingleIP();
    }

    /**
     * @inheritdoc
     */
    public function isValidString($stringValue)
    {
        return is_string($stringValue)
            && ($value = explode('-', $stringValue, 2))
            && count($value) == 2
            && (list($startIP, $endIP) = $value)
            && $this->singleIPValidator->isValidString($startIP)
            && $this->singleIPValidator->isValidString($endIP)
            && ip2long($startIP) <= ip2long($endIP);
    }

    /**
     * @inheritdoc
     */
    public function parse($stringValue)
    {
        if (!$this->isValidString($stringValue)) {
            throw new \InvalidArgumentException('Invalid IP range string');
        }

        return array_map('ip2long', explode('-', $stringValue, 2));
    }

    /**
     * @inheritdoc
     */
    public function stringify(RangeInterface $range)
    {
        return $range->getStart() . '-' . $range->getEnd();
    }
}
