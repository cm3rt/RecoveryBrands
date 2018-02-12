<?php

namespace RecoveryBrands;

/**
 * MeetingManager is a class that gathers, arranges,and returns AA/NA Meetings
 * by location
 *
 * MeetingManager depends on LocationManager and MeetingFinder. It connects to
 * multiple APIs, and finds the meetings in your area, sorted by distance and
 * day.
 *
 * Example usage:
 * $mtgMgr = new MeetingManager($locationManager, $meetingFinger, $cityOfMeetings,
 *                              $stateOfMeetings,
 *                              $addressOrigin);
 * $results = $mtgMgr->showSortedMeetings();
 *
 * @package  MeetingManager
 * @author   Joseph Alai <josephalai@gmail.com>
 * @version  1
 * @access   public

 */

class MeetingManager
{
    private $locMgr;
    private $mtgFinder;
    private $mtgList;
    private $originCity;
    private $originState;
    private $originLat;
    private $originLng;
    private $sortedMeetings;

    /*
     * @param $loc
     * @param $finder
     * @param $city
     * @param $state
     * @param $day
     */
    public function __construct(LocationManager $loc, MeetingFinder $finder, $city= "", $state="", $day="", $address="")
    {

        return $this->returnMeetingData($loc, $finder, $city, $state, $day, $address);


    }

    /*
     * @params $html
     * @return array
     */
    public function showSortedMeetings($html=false){

        $mtgList = $this->mtgList;
        if ($html == false) {
            return var_dump($mtgList);
        }
        else
            return $mtgList;
    }

    /*
     * @param $a
     * @param $b
     * return integer
     */
    function cmp($a, $b)
    {
        return ($a['distance'] < $b['distance']) ? -1 : (($a['distance'] > $b['distance']) ? 1 : 0);
    }


    public function setOrigin($city, $state){
        $this->originCity = $city;
        $this->originState = $state;
    }




    public function distance($lat1, $lon1, $lat2, $lon2, $unit, $destinationId) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);


            return $miles;

    }

    /**
     * @param MeetingFinder $finder
     * @return mixed
     */
    public function sortMeetingData(MeetingFinder $finder)
    {
        $meetingData = $finder->retrieveMeetingData();
        foreach ($meetingData as $key => $meeting) {
            $meetingData[$key]['distance'] = $this->distance($this->originLat, $this->originLng, $meeting['address']['lat'], $meeting['address']['lng'], "M", "");
        }

        usort($meetingData, array($this, "cmp"));
        return $meetingData;
    }

    /**
     * @param LocationManager $loc
     * @param MeetingFinder $finder
     * @param $city
     * @param $state
     * @param $day
     * @param $address
     * @return mixed
     */
    public function returnMeetingData(LocationManager $loc, MeetingFinder $finder, $city, $state, $day, $address)
    {
        $finder->setCity($city);
        $finder->setState($state);
        $finder->setDay($day);

        $loc->setAddress($address);
        $coordinates = $loc->getCoordinates($address);
        $this->originLat = $coordinates['lat'];
        $this->originLng = $coordinates['lng'];


        $this->sortedMeetings = $meetingData = $this->sortMeetingData($finder);


        $this->locMgr = $loc;
        $this->mtgFinder = $finder;
        $this->mtgList = $meetingData;
        return $meetingData;
    }


}
