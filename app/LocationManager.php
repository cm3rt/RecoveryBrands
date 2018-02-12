<?php

namespace RecoveryBrands;

use RecoveryBrands\Http\Requests;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class LocationManager
{
    private $address;
    private $addressCoordinates;
    private $destination;
    private $destinationCoordinates;
    private $results;
    private $destinationId;

    public function __construct($address="", $destination="")
    {
        if ($address != "") {
            $this->address = $address;
            $this->addressCoordinates = $this->getCoordinates($address);
        }
        if ($destination != ""){
            $this->destination=$destination;
            $this->destinationCoordinates = $this->getCoordinates($destination);
        }
    }

    public function setAddress($address){
        $this->address = $address;
        $this->addressCoordinates = $this->getCoordinates();

    }

    public function setDestination($destination, $destinationId){
        $this->destination = $destination;
        $this->destinationId = $destinationId;
        $this->destinationCoordinates = $this->getCoordinates($destination);
    }

    public function calculate($api=0){
        if ($api != 0){

        }
    }

    /*
     * Gets distance by ref'ng API each time (slow)
     * @return array
     *
     */
    public function getDistanceByDriving($address1="", $destination="")
    {

        if ($address1 != "" && $destination == ""){
            $destination = $address1;
            $address1 = $this->address;
        }
        if ($destination != "")
            $this->destination = $destination;
        $array = $this->getMapData("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=$address1&destinations=$destination&key=AIzaSyAzaO1yS3TLehlNxgwSXIR3vma_QtBLeWs");
        return $array['rows'][0]['elements'][0]['distance']['text'];
    }

    /*
     * Gets distance by Coordinates (fast)
     * @return array
     *
     */
    function getDistanceByCoordinates($destinationId="-1") {

        $lat1 = $this->addressCoordinates['lat'];
        $lon1 = $this->addressCoordinates['lng'];
        $lat2 = $this->destinationCoordinates['lat'];
        $lon2 = $this->destinationCoordinates['lng'];
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $this->results[$destinationId] = $miles;
        return array($destinationId, $miles);

    }


    public function getMapData($uri)

    {
        $client = new Client( ['headers' => [
            'Content-Type' => 'application/json'
        ]
        ],
            ['defaults' => [
                'verify' => 'false'
            ]]);
        $res = $client->request('GET', $uri);

        $this->status =  $res->getStatusCode();
        $body =  $res->getBody();
        $data = json_decode($body);
        $array = ObjToArray::convertMe($data, $array);
        return $array ;

    }

    public function getCoordinates($address="")
    {
        if ($address == ""){
            $address = $this->address;
        }

        $c_key = 'AIzaSyDKUre1kbkBeZsRJb1gC1ZWxhF2GYbGoBM';
        $address = str_replace(" ", "+", $address);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=$c_key";
        $array = $this->getMapData($url);
        return($array['results'][0]['geometry']['location']);
    }


}
