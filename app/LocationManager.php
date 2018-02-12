<?php

namespace RecoveryBrands;

use League\Flysystem\Adapter\Local;


use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use Kevinrob\GuzzleCache\Storage\FlysystemStorage;

/**
 * LocationManager is a class that gathers Coordinates, finds realtime driving
 * distance (not coordinate equations), and other Map Data from Google Maps API
 *
 *
 * Example usage:
 * $locMgr = new LocationManager(String $address, String $destination);
 * $results = $locMgr->showSortedMeetings($uri);
 *
 * @package  MeetingManager
 * @author   Joseph Alai <josephalai@gmail.com>
 * @version  1
 * @access   public

 */
class LocationManager
{
    private $address;
    private $addressCoordinates;
    private $destination;
    private $destinationCoordinates;
    private $results;
    private $destinationId;



    /*
     * @param $address
     * @param $destination
     */
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

    /*
     * @param $address
     *
     */
    public function setAddress($address){
        $this->address = $address;
        $this->addressCoordinates = $this->getCoordinates();

    }

    /*
     * $param $destination
     * $param $destinationId
     */
    public function setDestination($destination, $destinationId){
        $this->destination = $destination;
        $this->destinationId = $destinationId;
        $this->destinationCoordinates = $this->getCoordinates($destination);
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


    /*
     * @param $uri: uri for maps
     * @return array
     */
    public function getMapData($uri)

    {

        $stack = HandlerStack::create();
        $stack->push(
            new CacheMiddleware(
                new PrivateCacheStrategy(
                    new FlysystemStorage(
                        new Local("/tmp/sitex")
                    )
                )
            ),
            "cache"
        );

        $client = new Client( ['headers' => [
            'Content-Type' => 'application/json'
        ],
            'handler'=>$stack,
        ],
            ['defaults' => [
                'verify' => 'false'
            ]]);
        $res = $client->request('GET', $uri);

        $body =  $res->getBody();
        $data = json_decode($body);
        $array = ObjToArray::convertMe($data, $array);
        return $array ;

    }

    /*
     * @param $address
     * @return array
     */

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
