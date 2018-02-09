<?php

namespace spec\RecoveryBrands;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocationMakerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('RecoveryBrands\LocationMaker');
    }

    function it_retrieves_json_from_array(){
        $array = [
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
        $this->retrieve($array)->shouldReturn(60954);
    }

    function it_returns_status_when_connected(){
        $this->status("username", "password")->shouldReturn(200);
    }
}
