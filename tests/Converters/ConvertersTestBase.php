<?php
namespace Bankiru\IPTools\Converters;

use Bankiru\IPTools\Interfaces\RangeConverterInterface;
use Bankiru\IPTools\IP;
use Bankiru\IPTools\Range;

abstract class ConvertersTestBase extends \PHPUnit_Framework_TestCase
{
    const DATA_SET_LENGTH = 20;

    /** @var string */
    protected $class;

    /** @var RangeConverterInterface */
    protected $instance;

    protected function setUp()
    {
        $this->class = preg_replace('@Test$@', '', get_class($this));
        $this->instance = new $this->class();
    }

    /**
     * @dataProvider provide_isValidString
     */
    public function testIsValidString($value, $expected)
    {
        $this->assertEquals($expected, $this->instance->isValidString($value));
    }

    /**
     * @dataProvider provide_valid_parse
     */
    public function testParseSuccess($value, $expectedStart, $expectedEnd)
    {
        $msgBegin = $this->class . '->parse(' . var_export($value, true) . '):';
        $expectedStartStr = long2ip($expectedStart);
        $expectedEndStr = long2ip($expectedEnd);

        $actual = $this->instance->parse($value);

        $this->assertInternalType('array', $actual, "$msgBegin returns non array");
        $this->assertCount(2, $actual, "$msgBegin returns array that does not contains 2 elements");
        list($actualStart, $actualEnd) = $actual;
        $actualStartStr = long2ip($actualStart);
        $actualEndStr = long2ip($actualEnd);
        $this->assertEquals($expectedStart, $actualStart, "$msgBegin expected $expectedStart ($expectedStartStr) in 1st element but actual is $actualStart ($actualStartStr)");
        $this->assertEquals($expectedEnd, $actualEnd, "$msgBegin expected $expectedEnd ($expectedEndStr) in 2nd element but actual is $actualEnd ($actualEndStr)");
    }

    /**
     * @dataProvider provide_invalid_parse
     * @expectedException \InvalidArgumentException
     */
    public function testParseFail($value)
    {
        $this->instance->parse($value);
        $this->fail('parse should fail on invalid data');
    }

    /**
     * @dataProvider provide_valid_stringify
     */
    public function testStringifySuccess($valueStart, $valueEnd, $expected)
    {
        $range = new Range(new IP($valueStart), new IP($valueEnd));

        $msgBegin = $this->class . "->stringify(Range($valueStart, $valueEnd)):";
        $actual = $this->instance->stringify($range);
        $this->assertEquals($expected, $actual, "$msgBegin expected " . var_export($expected, true) . ' but actual ' . var_export($actual, true));
    }

    /**
     * @dataProvider provide_invalid_stringify
     * @expectedException \InvalidArgumentException
     */
    public function testStringifyFail($valueStart, $valueEnd)
    {
        $range = new Range(new IP($valueStart), new IP($valueEnd));

        $this->instance->stringify($range);
        $this->fail('stringify should fail on invalid data');
    }

    /**
     * @return array [[value, expected], ...]
     */
    public function provide_isValidString()
    {
        return array_filter(
            array_merge(
                array_map(
                    function ($item) {
                        return array($item[0], true);
                    },
                    $this->provide_valid_parse()
                ),
                array_map(
                    function ($item) {
                        return array($item[0], false);
                    },
                    $this->provide_invalid_parse()
                )
            ),
            function ($item) {
                return is_string($item[0]);
            }
        );
    }

    /**
     * @return array [[value, expectedStart, expectedEnd], ...]
     */
    abstract public function provide_valid_parse();

    /**
     * @return array [[value], ...]
     */
    abstract public function provide_invalid_parse();

    /**
     * @return array [[value, expected], ...]
     */
    abstract public function provide_valid_stringify();

    /**
     * @return array [[value], ...]
     */
    abstract public function provide_invalid_stringify();
}
