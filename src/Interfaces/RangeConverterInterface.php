<?php
namespace Bankiru\IPTools\Interfaces;

interface RangeConverterInterface
{
    /**
     * @param  string $stringValue
     * @return bool
     */
    public function isValidString($stringValue);

    /**
     * @param  string                    $stringValue
     * @return int[]                     array with start and end IPs
     * @throws \InvalidArgumentException
     */
    public function parse($stringValue);

    /**
     * @param  RangeInterface            $range
     * @return string
     * @throws \InvalidArgumentException
     */
    public function stringify(RangeInterface $range);
}
