<?php

namespace RecoveryBrands;

class MeetingManager
{
    private $locMgr;
    private $mtgFinder;
    private $mtgList;
    private $originCity;
    private $originState;
    private $origin;
    private $originLat;
    private $originLng;

    public function __construct(LocationManager $loc, MeetingFinder $finder, $city= "", $state="")
    {

        $finder->setCity($city);
        $finder->setState($state);
        $loc->setAddress("$city, $state");
        $coordinates = $loc->getCoordinates();
        $this->originLat = $coordinates['lat'];
        $this->originLng = $coordinates['lng'];

        print $this->originLat . ", ";
        print $this->originLng . "<br />";


        $meetingData = $finder->retrieveMeetingData();

//        print "Meeting Data: " . print_r($meetingData, 1) . "<br />";
        $answers = array_map(array($this, 'sortByDistance'), $meetingData);
        print_r($answers);
        $answers2 = usort($answers, array($this, 'sort_by_order'));
        print "<h2>answers 2</h2>";
        print_r($answers2);

//        print_r($answers);

//        $arrayIndexed = $this->flip3d($answers);
        usort($answers, array($this, 'sortByOrder'));

//        print "Sorted : ";
//        print_r($answers);
        $this->locMgr = $loc;
        $this->mtgFinder = $finder;
        $this->mtgList = $answers;




    }


    public function sort_by_order ($a, $b)
    {
        return $a[0] - $b[0];
    }

    public function setOrigin($city, $state){
        $this->originCity = $city;
        $this->originState = $state;
    }



    public function sortByDistance($destination){
        return $this->distance($this->originLat, $this->originLng, $destination['address']['lat'],  $destination['address']['lng'], "M", $destination['id']);
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit, $destinationId) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return array(($miles * 1.609344), $destinationId);
        } else if ($unit == "N") {
            return array(($miles * 0.8684), $destinationId);
        } else {
            return array($miles, $destinationId);
        }
    }

    public function flip3d($r){
        $indexed = array();
        foreach ($r as $row) {
            $indexed[$row[1]] = $row;
        }

        // or if you're concerned about memory (e.g. result set is large), less smooth version:
        foreach ($r as $index => $row) {
            $r[$row[1]] = $row;
            unset($r[$index]);    // it works ok, foreach doesn't traverse over added elements, but it isn't a good way
        }

        // or smoother alternative for unset(), have second array contain links to first:
        $indexed = array();
        foreach ($r as &$row) {
            $indexed[$row[1]] = &$row;
        }
        return $indexed;
    }

    public function sortByOrder($a, $b) {
        return $a[0] - $b[0];
    }

    public function sortMeetings()
    {
        // TODO: write logic here
    }

    public function getMeetingList()
    {
        // TODO: write logic here
    }
}
