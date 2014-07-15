<?php
namespace Bankiru\IPTools\Converters;

use Bankiru\IPTools\TestDataGenerator;

class WildcardTest extends ConvertersTestBase
{
    /**
     * @return array
     */
    public function provide_valid_parse()
    {
        $data = array_map(
            function ($n) {
                $wildcard = implode(
                    '.',
                    array_map(
                        function ($o) {
                            return $o ? '*' : rand(0, 255);
                        },
                        array_map(
                            'intval',
                            str_split(str_pad(decbin($n), 4, '0', STR_PAD_LEFT))
                        )
                    )
                );

                return array(
                    $wildcard,
                    ip2long(str_replace('*', '0', $wildcard)),
                    ip2long(str_replace('*', '255', $wildcard))
                );
            },
            range(0, 0x0F)
        );

        $ip = TestDataGenerator::ipInt();
        $data[] = array(long2ip($ip), $ip, $ip);

        return $data;
    }

    /**
     * @return array
     */
    public function provide_invalid_parse()
    {
        return array(
            array('23.14.77.1.*'),
            array('127.0.0.'),
            array('19*.168.255.0'),
            array('192.168.0.1/27'),
            array('8.8.8.8.8'),
            array('192.168.256.7'),
            array('192.168.43.100-192.168.45.7'),
        );
    }

    /**
     * @return array
     */
    public function provide_valid_stringify()
    {
        return array(
            array('192.168.0.1', '192.168.0.1', '192.168.0.1'),
            array('192.168.0.1', '192.168.255.1', '192.168.*.1'),
            array('192.0.0.1', '192.255.255.1', '192.*.*.1'),
            array('192.168.0.0', '192.168.0.255', '192.168.0.*'),
        );
    }

    /**
     * @return array
     */
    public function provide_invalid_stringify()
    {
        return array(
            array('192.168.0.1', '192.168.7.1'),
            array('192.168.0.1', '192.168.255.4'),
            array('192.0.0.0', '193.0.0.255'),
        );
    }
}
