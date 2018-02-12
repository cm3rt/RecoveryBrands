<?php

namespace RecoveryBrands\Http\Controllers;

use RecoveryBrands\LocationManager;
use RecoveryBrands\MeetingFinder;
use RecoveryBrands\MeetingManager;

class MeetingController extends Controller
{
    public function startApi($city, $state, $address, $day){

            $loc = new LocationManager();
            $finder = new MeetingFinder();
            $mtgMgr = new MeetingManager($loc, $finder, $city, $state, $day, $address);
            $array = $mtgMgr->showSortedMeetings(true);
            return view('meetings')->with(["meetings" => $array, 'city' => $city,
                'state'=> $state, 'day' => $day, 'address'=>$address]);

    }


}
