<?php
namespace Bankiru\IPTools\Converters;

use Bankiru\IPTools\Interfaces\RangeConverterInterface;
use Bankiru\IPTools\Interfaces\RangeInterface;

class Wildcard implements RangeConverterInterface
{
    const REGEX = '(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|\*)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|\*)';

    /**
     * @inheritdoc
     */
    public function isValidString($stringValue)
    {
        return is_string($stringValue) && preg_match('@^' . self::REGEX . '$@', $stringValue);
    }

    /**
     * @inheritdoc
     */
    public function parse($stringValue)
    {
        if (!$this->isValidString($stringValue)) {
            throw new \InvalidArgumentException('Invalid Wildcard string');
        }

        return array(
            ip2long(str_replace('*', '0', $stringValue)),
            ip2long(str_replace('*', '255', $stringValue)),
        );
    }

    /**
     * @inheritdoc
     */
    public function stringify(RangeInterface $range)
    {
        if ($range->getStart()->getIntValue() == $range->getEnd()->getIntValue()) {
            return (string) $range->getStart();
        }

        $start = explode('.', (string) $range->getStart());
        $end = explode('.', (string) $range->getEnd());

        $wildcard = array();
        for ($i=0; $i<4; $i++) {
            $byteS = $start[$i];
            $byteE = $end[$i];

            if ($byteS == '0' && $byteE == '255') {
                $wildcard[$i] = '*';
            } elseif ($byteS == $byteE) {
                $wildcard[$i] = $byteS;
            } else {
                throw new \InvalidArgumentException("Range [{$range->getStart()}-{$range->getEnd()} can not be converted to wildcard");
            }
        }

        return implode('.', $wildcard);
    }
}
