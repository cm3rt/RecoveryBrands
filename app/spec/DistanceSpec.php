<?php

namespace spec\RecoveryBrands;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DistanceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('RecoveryBrands\Distance');
    }

    function it_returns_zero_if_same_address(){
        $this->distanceDriving("Chula Vista, CA", "Chula Vista, CA")->shouldReturn("1 ft");
    }

    function it_gives_distance_between_two_addresses(){
        $this->distanceDriving("Chula Vista, CA", "32.623718461538,-117.0594352307")->shouldReturn("2.6 mi");
    }

    function it_gives_coordinates_of_address1(){
        $this->getCoordinates("Chula Vista, CA")->shouldReturn(array('lat'=>32.6400541, 'lng'=>-117.0841955));
    }


}
