<?php
namespace Bankiru\IPTools\Interfaces;

interface RangeIteratorInterface extends \Iterator
{
    /**
     * @param RangeInterface $range
     */
    public function __construct(RangeInterface $range);
}
