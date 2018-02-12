<?php

namespace spec\RecoveryBrands;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MeetingFinderSpec extends ObjectBehavior
{
    protected $array = [
        'auth' => ['oXO8YKJUL2X3oqSpFpZ5', 'JaiXo2lZRJVn5P4sw0bt'],
        'json' => [
            'jsonrpc' => '2.0',
            'id'=> 1,
            'method' => 'byLocals',
            'params' =>
                array( 0 => array( 0=> array(
                    'state_abbr' => "CA",
                    'city' => "Chula Vista" ))
                )

        ]
    ];

    function it_is_initializable()
    {
        $this->shouldHaveType('RecoveryBrands\MeetingFinder');
    }

    function it_retrieves_json_from_array(){

        $this->retrieveMeetingData()->shouldBeArray();
    }



    function it_returns_array_from_single_argument(){
        $this->formatArgsProperly("Chula Vista, CA")->shouldReturn(array("Chula Vista", "CA"));
    }

    function it_returns_array_from_double_argument(){
        $this->formatArgsProperly("Chula Vista", "CA")->shouldReturn(array("Chula Vista", "CA"));
    }
}
