<?php
namespace Bankiru\IPTools;

class IP
{
    const MAX_INT_VALUE = 4294967295;

    /** @var int */
    protected $intValue;

    /**
     * @param  int|string                $value
     * @throws \InvalidArgumentException
     */
    public function __construct($value)
    {
        if (is_int($value) && $value >= 0 && $value <= self::MAX_INT_VALUE
            || is_string($value) && ($value = ip2long($value)) !== false
        ) {
            $this->intValue = (int) $value;
        } else {
            throw new \InvalidArgumentException('IP "' . $value . '" is invalid');
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return long2ip($this->intValue);
    }

    /**
     * @return int
     */
    public function getIntValue()
    {
        return $this->intValue;
    }
}
