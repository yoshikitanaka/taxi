<?php

namespace TripleI\taxi;

class AppTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var app
     */
    protected $skeleton;

    protected function setUp()
    {
        parent::setUp();
        $this->skeleton = new App;
    }

    public function testRun()
    {
        $app = $this->skeleton;

        foreach ($this->getData() as $data) {
            $app->run($data);
            $this->assertEquals($app->getTotalFee(), $data[1]);
        }
    }

    public function getData()
    {
        $ret = [
            ["ADFC", "510"],
            ["CFDA", "500"],
            ["AB", "460"],
            ["BA", "460"],
            ["CD", "400"],
            ["DC", "350"],
            ["BG", "520"],
            ["GB", "530"],
            ["FDA", "450",],
            ["BGFCDAB", "1180"],
            ["CDFC", "460"],
            ["CFDC", "450"],
            ["ABGEDA", "1420"],
            ["ADEGBA", "1470"],
            ["CFGB", "640"],
            ["BGFC", "630"],
            ["ABGEDFC", "1480"],
            ["CFDEGBA", "1520"],
            ["CDFGEDABG", "1770"],
            ["GBADEGFDC", "1680"],
        ];

        return $ret;
    }
}
