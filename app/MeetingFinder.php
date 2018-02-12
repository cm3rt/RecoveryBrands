<?php

namespace RecoveryBrands;

use RecoveryBrands\Http\Requests;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class MeetingFinder
{
    private $json;
    private $status;
    private $city;
    private $state;
    private $returnData;

    /*
     * @param city
     * @param state
     * @return array
     */
    public function __construct($city = "", $state="")
    {
        $this->formatArgsProperly($city, $state);
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

        if ($this->state == ""){
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
                array( 0 => array( 0=> array(
                    'state_abbr' => $this->state ,
                    'city' => $this->city ))
                )

        ]
    ];
        return $this->getMeetingsData($this->json);
    }

    /*
     * @return int
     */
    public function status()
    {

        return ($this->status != "") ? $this->status : 0;
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
        return ObjToArray::convertMe($data['result'], $arr);

    }


    /*
     * @return array
     */
    public function getAddress()
    {
        $meetingsData = $this->retrieveMeetingData("CA", "Chula Vista");
//        $argument1 = $this->json;
        foreach($meetingsData as $key=>$meeting){
            $address[$key] = $meeting['address'];
        }
        if (is_array($address))
            return $address[0];
        else
            return 0;
    }

    /**
     * @param $city
     * @param $state
     * @return array
     */
    public function formatArgsProperly($city="", $state="")
    {
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
        $client = new Client(['headers' => [
            'Content-Type' => 'application/json'
        ]
        ]);
        $res = $client->request('POST', 'http://tools.referralsolutionsgroup.com/meetings-api/v1/', $data);

        $this->status = $res->getStatusCode();
        $this->returnData = $res->getBody();
        return $this->status;
    }
}
