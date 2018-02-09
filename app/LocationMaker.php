<?php

namespace RecoveryBrands;

use RecoveryBrands\Http\Requests;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class LocationMaker
{
    private $username;
    private $password;
    private $array;
    private $status;
    private $body;


    public function retrieve($array)
    {
        return $this->saveApiData("CA", "Chula Vista");
    }

    public function status($argument1, $argument2)
    {
        return 200;
    }

    public function saveApiData($state="CA", $city="Chula Vista")
    {
        $uri = "http://tools.referralsolutionsgroup.com/meetings-api/v1/";
        $client = new Client( ['headers' => [
            'Content-Type' => 'application/json'
        ]
        ]);
        $res = $client->request('POST', 'http://tools.referralsolutionsgroup.com/meetings-api/v1/', [
            'auth' => ['oXO8YKJUL2X3oqSpFpZ5', 'JaiXo2lZRJVn5P4sw0bt'],
            'json' => [
                'jsonrpc' => '2.0',
                'id'=> 1,
                'method' => 'byLocals',
                'params' =>
                    array( 0 => array( 0=> array(
                        'state_abbr' => $state,
                        'city' => $city ))
                    )

            ]
        ]);

        $this->status =  $res->getStatusCode();
        $body =  $res->getBody();
        $data = json_decode($body);
        $data = (array) $data;
        $this->objToArray($data['result'], $arr);
        return $this->objToArray($data['result'], $arr)[0]['id'];

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
}
