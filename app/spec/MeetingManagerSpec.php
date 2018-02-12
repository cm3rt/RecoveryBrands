<?php

namespace spec\RecoveryBrands;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RecoveryBrands\LocationManager;
use RecoveryBrands\MeetingFinder;

class MeetingManagerSpec extends ObjectBehavior
{


    function it_returns_array_with_address(){
        $loc = new LocationManager();
        $finder = new MeetingFinder();
        $this->beConstructedWith($loc, $finder, 'chula vista', 'ca', 'wednesday', '517 4th ave san diego ca 92101');
        $this->returnMeetingData($loc, $finder, 'chula vista', 'ca', 'wednesday', '517 4th ave san diego ca 92101')->shouldBeArray();
    }

    function it_shows_html_when_asks_for_sorted_meetings()
    {
        $loc = new LocationManager();
        $finder = new MeetingFinder();
        $this->beConstructedWith($loc, $finder, 'chula vista', 'ca', 'wednesday', '517 4th ave san diego ca 92101');
        $this->showSortedMeetings(false)->shouldBeString();
    }

}
