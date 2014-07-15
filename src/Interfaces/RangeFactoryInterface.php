<?php
namespace Bankiru\IPTools\Interfaces;

interface RangeFactoryInterface
{
    /**
     * @param  string                    $range
     * @return RangeInterface
     * @throws \InvalidArgumentException
     */
    public function parse($range);

    /**
     * @param  RangeInterface $range
     * @return string
     */
    public function stringify(RangeInterface $range);
}
