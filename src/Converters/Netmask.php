<?php
namespace Bankiru\IPTools\Converters;

use Bankiru\IPTools\Interfaces\RangeConverterInterface;
use Bankiru\IPTools\Interfaces\RangeInterface;

class Netmask implements RangeConverterInterface
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
            throw new \InvalidArgumentException('Invalid IP with Netmask string');
        }

        list($ip, $netmask) = explode('/', $stringValue, 2);

        $cidr = new Cidr();

        return $cidr->parse($ip . '/' . Cidr::countSetBits(ip2long($netmask)));
    }

    /**
     * @inheritdoc
     */
    public function isValidString($stringValue)
    {
        $netmask = null;

        if (!(is_string($stringValue)
            && ($value = explode('/', $stringValue, 2))
            && count($value) == 2
            && (list($ip, $netmask) = $value)
            && $this->singleIPValidator->isValidString($ip)
            && $this->singleIPValidator->isValidString($netmask))
        ) {
            return false;
        }

        $netmask = ip2long($netmask);
        $neg = ((~(int) $netmask) & 0xFFFFFFFF);

        return (($neg + 1) & $neg) === 0;
    }

    /**
     * @inheritdoc
     */
    public function stringify(RangeInterface $range)
    {
        $cidr = new Cidr();

        return implode(
            ',',
            array_map(
                function ($cidr) {
                    list($ip, $bits) = explode('/', $cidr, 2);
                    $mask = $bits == 0 ? 0 : (~0 << (32 - $bits));

                    return $ip . '/' . long2ip($mask);
                },
                explode(',', $cidr->stringify($range))
            )
        );
    }
}
