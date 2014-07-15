<?php
namespace Bankiru\IPTools\Interfaces;

use Bankiru\IPTools\IP;

interface RangeInterface extends \IteratorAggregate
{
    /**
     * @param IP $start
     * @param IP $end
     */
    public function __construct(IP $start, IP $end);

    /**
     * @return string
     */
    public function __toString();

    /**
     * @return IP
     */
    public function getStart();

    /**
     * @return IP
     */
    public function getEnd();

    /**
     * @param  IP   $ip
     * @return bool
     */
    public function includesIP(IP $ip);
}
