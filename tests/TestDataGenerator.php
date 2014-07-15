<?php
namespace Bankiru\IPTools;

class TestDataGenerator
{
    private static $used = array();

    public static function ipInt($unique = false)
    {
        mt_srand(microtime(true));

        $ip = rand(0,IP::MAX_INT_VALUE);
        if ($unique) {
            while (isset(self::$used[$ip])) {
                $ip = rand(0,IP::MAX_INT_VALUE);
            }
        }

        self::$used[$ip] = true;

        return $ip;
    }

    public static function ipString($unique = false)
    {
        return long2ip(self::ipInt($unique));
    }
}
