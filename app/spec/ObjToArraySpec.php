<?php

namespace spec\RecoveryBrands;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjToArraySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('RecoveryBrands\ObjToArray');
    }
}
