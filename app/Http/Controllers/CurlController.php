<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurlController extends Controller
{
    //
    public function getCurl($url){
        error_log($url);
        $endpoint = $url; 
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_POST, false);
        @curl_setopt($ch, CURLOPT_URL, $endpoint);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'authorization: Bearer pat-eu1-ec2c556e-9678-4b3d-b17d-553415227a01'));
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = array(
            "response"      => @curl_exec($ch),
            "statusCode"    => @curl_getinfo($ch, CURLINFO_HTTP_CODE),
            "curlErrors"    => curl_error($ch)
        );
        @curl_close($ch);

        return $result;
       
    }
}