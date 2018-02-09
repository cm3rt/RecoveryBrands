<?php

namespace RecoveryBrands;

use RecoveryBrands\Http\Requests;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Distance
{
    public function distanceDriving($address1, $destination)
    {
        $array = $this->getMapData("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=$address1&destinations=$destination&key=AIzaSyAzaO1yS3TLehlNxgwSXIR3vma_QtBLeWs");
        return $array['rows'][0]['elements'][0]['distance']['text'];
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

    public function getCoordinates($address)
    {
        $c_key = 'AIzaSyDKUre1kbkBeZsRJb1gC1ZWxhF2GYbGoBM';
        $address = str_replace(" ", "+", $address);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=$c_key";
        $array = $this->getMapData($url);
        return($array['results'][0]['geometry']['location']);
    }


}
