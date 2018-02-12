<?php

namespace RecoveryBrands;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use League\Flysystem\Adapter\Local;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use Kevinrob\GuzzleCache\Storage\FlysystemStorage;

/**
 * MeetingFinder is a class that gathers AA/NA Meetings within the city
 * and state that you input, as well as the day
 *
 *
 * Example usage:
 * $mtgFinder = new MeetingFinder(String $address, String $destination, String $day);
 * $results = $mtgFinder->retrieveMeetingData();
 *
 * @package  MeetingManager
 * @author   Joseph Alai <josephalai@gmail.com>
 * @version  1
 * @access   public

 */
class MeetingFinder
{
    private $json;
    private $city;
    private $state;
    private $returnData;
    private $day;

    /*
     * @param city
     * @param state
     * @return array
     */
    public function __construct($city = "", $state="", $day = "")
    {
        $this->formatArgsProperly($city, $state, $day);
        return $this->retrieveMeetingData();
    }

    /*
     * @param @city
     */
    public function setCity($city){
        $this->city = $city;
    }


    /*
     * @param $state
     */
    public function setState($state){
        $this->state = $state;
    }
    /*
     * @param $day
     */
    public function setDay($day){
        $this->day = $day;
    }

    /*
     * @param $city
     * @param state
     *
     */
    public function setLocation($city, $state){
        $this->city = $city;
        $this->state = $state;

    }

    /*
     * @param $state
     * @param $city
     * @return array
     */
    public function retrieveMeetingData()
    {

        if ($this->city == ""){
            $this->state = "CA";
            $this->city = "San Diego";

        }

       $this->json = [
        'auth' => ['oXO8YKJUL2X3oqSpFpZ5', 'JaiXo2lZRJVn5P4sw0bt'],
        'json' => [
            'jsonrpc' => '2.0',
            'id'=> 1,
            'method' => 'byLocals',
            'params' =>
                array(
                    0 => array(
                        0=> array(
                            'state_abbr' => $this->state ,
                            'city' => $this->city
                        )
                    )
                )

        ]
    ];
        return $this->getMeetingsData($this->json);
    }



    /*
     * @params $data
     * return array
     */
    public function getMeetingsData($data)
    {
        $this->connectToApi($data);
        $data = json_decode($this->returnData);
        $data = (array) $data;
        ObjToArray::convertMe($data['result'], $arr);
        if ($this->day != "") {

            foreach ($arr as $key => $meeting) {
                if ($meeting['time']['day'] == $this->day) {
                } else
                    unset ($arr[$key]);
            }
        }
        return $arr;
    }




    /**
     * @param $city
     * @param $state
     * @return array
     */
    public function formatArgsProperly($city="", $state="", $day="")
    {
        if ($day != ""){
            $this->day = $day;
        }
        else{
            $this->day = "monday";
        }
        if ($city != "" && $state == "") {
            list($this->city, $this->state) = explode(",", $city);
            $this->setCity(trim($this->city));
            $this->setState(trim($this->state));

        } elseif ($city != "" && $state != "") {
            $this->setCity(trim($city));
            $this->setState(trim($state));
        }
        return array($this->city, $this->state);
    }

    /**
     * @param $data
     * @return int
     */
    public function connectToApi($data)
    {
        $uri = "http://tools.referralsolutionsgroup.com/meetings-api/v1/";

        $stack=HandlerStack::create();
        $stack->push(
            new CacheMiddleware(
                new GreedyCacheStrategy(
                    new FlysystemStorage(
                        new Local("/tmp/sitex")
                    ),
                    180
                )
            ),
            "cache"
        );
        $client = new Client(["handler" => $stack,
            'headers' => [
            'Content-Type' => 'application/json'
        ]
        ]);
        $res = $client->request('POST', 'http://tools.referralsolutionsgroup.com/meetings-api/v1/', $data);

        $this->status = $res->getStatusCode();
        $this->returnData = $res->getBody();
        return $this->status;
    }
}
