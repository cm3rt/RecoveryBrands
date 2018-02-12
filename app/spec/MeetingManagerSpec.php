<?php

namespace spec\RecoveryBrands;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MeetingManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('RecoveryBrands\MeetingManager');
    }

    function it_returns_sorted_meeting_list(){
        $this->sortMeetings()->shouldBeArray();
    }

    function it_returns_meeting_list(){
        $this->getMeetingList()->shouldBeArray();
    }
}
