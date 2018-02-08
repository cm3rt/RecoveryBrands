<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class MeetingController extends Controller
{
    public function saveApiData()
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
                        'state_abbr' => 'CA',
                        'city' => 'Chula Vista' ))
                    )

            ]
        ]);


//        $response = $client->post('url', [
//            GuzzleHttp\RequestOptions::JSON => ['foo' => 'bar']
//        ]);
        $status =  $res->getStatusCode();
        // "200";
        // 'application/json; charset=utf8'
        $body =  $res->getBody();
        $data = json_decode($body);
        print_r($data);

        echo $status . "<br />";;
//        echo $body;
        // {"type":"User"...'
    }
}
