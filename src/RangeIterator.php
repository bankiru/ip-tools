<?php
namespace Bankiru\IPTools;

use Bankiru\IPTools\Interfaces\RangeInterface;
use Bankiru\IPTools\Interfaces\RangeIteratorInterface;

class RangeIterator implements RangeIteratorInterface
{
    /** @var RangeInterface */
    protected $range;

    /** @var IP */
    protected $current;

    /**
     * @param RangeInterface $range
     */
    public function __construct(RangeInterface $range)
    {
        $this->range = $range;
        $this->current = $range->getStart();
    }

    /**
     * Return the current IP
     * @return IP
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Move forward to next element
     */
    public function next()
    {
        if ($this->valid()) {
            $this->current = $this->current->getIntValue() == $this->range->getEnd()->getIntValue()
                ? null
                : new IP($this->current->getIntValue() + 1);
        }
    }

    /**
     * Return the key of the current element
     * @return null|int
     */
    public function key()
    {
        return $this->valid() ? $this->current->getIntValue() : null;
    }

    /**
     * Checks if current position is valid
     * @return boolean The return value will be casted to boolean and then evaluated.
     *                 Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->current !== null;
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind()
    {
        $this->current = $this->range->getStart();
    }
}
