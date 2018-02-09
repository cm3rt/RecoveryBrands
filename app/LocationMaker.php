<?php

namespace RecoveryBrands;

use RecoveryBrands\Http\Requests;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class LocationMaker
{
    private $username;
    private $password;
    private $json;
    private $status;
    private $body;


    public function retrieve($state, $city)
    {
       $this->json = [
        'auth' => ['oXO8YKJUL2X3oqSpFpZ5', 'JaiXo2lZRJVn5P4sw0bt'],
        'json' => [
            'jsonrpc' => '2.0',
            'id'=> 1,
            'method' => 'byLocals',
            'params' =>
                array( 0 => array( 0=> array(
                    'state_abbr' => $state ,
                    'city' => $city ))
                )

        ]
    ];
        return $this->getMeetingsData($this->json);
    }

    public function status($argument1, $argument2)
    {
        return 200;
    }

    public function getMeetingsData($data)
    {
        $uri = "http://tools.referralsolutionsgroup.com/meetings-api/v1/";
        $client = new Client( ['headers' => [
            'Content-Type' => 'application/json'
        ]
        ]);
        $res = $client->request('POST', 'http://tools.referralsolutionsgroup.com/meetings-api/v1/', $data);

        $this->status =  $res->getStatusCode();
        $body =  $res->getBody();
        $data = json_decode($body);
        $data = (array) $data;
        return $this->objToArray($data['result'], $arr);

    }

    public function objToArray($obj, &$arr){
        if(!is_object($obj) && !is_array($obj)){
            $arr = $obj;
            return $arr;
        }

        foreach ($obj as $key => $value)
        {
            if (!empty($value))
            {
                $arr[$key] = array();
                $this->objToArray($value, $arr[$key]);
            }
            else
            {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }

    public function getLocations()
    {
        $meetingsData = $this->retrieve("CA", "Chula Vista");
//        $argument1 = $this->json;
        foreach($meetingsData as $key=>$meeting){
            $address[$key] = $meeting['address'];
//            array_push($address[$key],$arg['id']);
        }
        return $address[0];
    }
}
