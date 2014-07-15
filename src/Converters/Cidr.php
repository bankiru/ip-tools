<?php
namespace Bankiru\IPTools\Converters;

use Bankiru\IPTools\Interfaces\RangeConverterInterface;
use Bankiru\IPTools\Interfaces\RangeInterface;

class Cidr implements RangeConverterInterface
{
    private $singleIPValidator;

    public function __construct()
    {
        $this->singleIPValidator = new SingleIP();
    }

    /**
     * @inheritdoc
     */
    public function parse($stringValue)
    {
        if (!$this->isValidString($stringValue)) {
            throw new \InvalidArgumentException('Invalid CIDR string');
        }

        list($ip, $bits) = explode('/', $stringValue, 2);
        $mask = $bits == 0 ? 0 : (~0 << (32 - $bits));
        $start = ip2long($ip) & $mask;
        $end = ip2long($ip) | (~$mask & 0xFFFFFFFF);

        return array($start, $end);
    }

    /**
     * @inheritdoc
     */
    public function isValidString($stringValue)
    {
        return is_string($stringValue)
            && ($value = explode('/', $stringValue, 2))
            && count($value) == 2
            && (list($ip, $bits) = $value)
            && $this->singleIPValidator->isValidString($ip)
            && preg_match('@^\d{1,3}$@', $bits)
            && $bits >= 0 && $bits <= 32;
    }

    /**
     * @inheritdoc
     */
    public function stringify(RangeInterface $range)
    {
        $start = $range->getStart()->getIntValue();
        $end = $range->getEnd()->getIntValue();
        $listCIDRs = array();

        while ($end >= $start) {
            $maxsize = self::countSetBits(-($start & -$start));
            $maxdiff = 32 - intval(log($end - $start + 1) / log(2));
            $size = ($maxsize > $maxdiff) ? $maxsize : $maxdiff;
            $listCIDRs[] = long2ip($start) . "/$size";
            $start += pow(2, (32 - $size));
        }

        return implode(',', $listCIDRs);
    }

    public static function countSetBits($int)
    {
        $int = $int & 0xFFFFFFFF; // fix for extra 32 bits
        $int = ($int & 0x55555555) + (($int >> 1) & 0x55555555);
        $int = ($int & 0x33333333) + (($int >> 2) & 0x33333333);
        $int = ($int & 0x0F0F0F0F) + (($int >> 4) & 0x0F0F0F0F);
        $int = ($int & 0x00FF00FF) + (($int >> 8) & 0x00FF00FF);
        $int = ($int & 0x0000FFFF) + (($int >> 16) & 0x0000FFFF);
        $int = $int & 0x0000003F;

        return $int;
    }

}
