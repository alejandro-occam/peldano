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
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'authorization: Bearer '.config('constants.bearer')));
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