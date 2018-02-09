<?php

namespace spec\RecoveryBrands;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocationMakerSpec extends ObjectBehavior
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
        $this->shouldHaveType('RecoveryBrands\LocationMaker');
    }

    function it_retrieves_json_from_array(){

        $this->retrieve("CA", "Chula Vista")->shouldBeArray();
    }

    function it_returns_status_when_connected(){
        $this->status("username", "password")->shouldReturn(200);
    }

    function it_contains_locations(){
//        $this->retrieve("CA", "Chula Vista")->shouldBeArray();
        $this->getLocations()->shouldHaveKey("street");
    }
}
