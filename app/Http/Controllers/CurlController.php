<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurlController extends Controller
{
    //
    public function getCurl($url, $type){
        $bearer = '';
        if($type == 1){
            $bearer = config('constants.bearer_hubspot');
        }else{
            $bearer = config('constants.bearer_sage');
        }

        $endpoint = $url; 
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_POST, false);
        @curl_setopt($ch, CURLOPT_URL, $endpoint);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'authorization: Bearer '.$bearer));
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = array(
            "response"      => @curl_exec($ch),
            "statusCode"    => @curl_getinfo($ch, CURLINFO_HTTP_CODE),
            "curlErrors"    => curl_error($ch)
        );
        @curl_close($ch);

        return $result;
       
    }

    public function getNormalCurl($url){
        $endpoint = $url; 
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_POST, false);
        @curl_setopt($ch, CURLOPT_URL, $endpoint);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = array(
            "response"      => @curl_exec($ch),
            "statusCode"    => @curl_getinfo($ch, CURLINFO_HTTP_CODE),
            "curlErrors"    => curl_error($ch)
        );
        @curl_close($ch);

        return $result;
       
    }

    public function postCurl($url, $type, $arr = null){
        $bearer = '';
        if($type == 1){
            $bearer = config('constants.bearer_hubspot');
        }else{
            $bearer = config('constants.bearer_sage');
        }

        $post_json = json_encode($arr);               
        $endpoint = $url; 
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        @curl_setopt($ch, CURLOPT_URL, $endpoint);
        error_log("ENDPOINT");
        error_log($endpoint);
        error_log("========");
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'authorization: Bearer '.$bearer));
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = array(
            "response"      => @curl_exec($ch),
            "statusCode"    => @curl_getinfo($ch, CURLINFO_HTTP_CODE),
            "curlErrors"    => curl_error($ch)
        );
        @curl_close($ch);

        return $result;
       
    }

    //Get para sage con sus cabeceras
    public function getSageCurl($url, $arr = null){
        $bearer = $bearer = config('constants.bearer_sage');
        $x_nonce = config('constants.x_nonce');
        $x_site = config('constants.x_site');
        $ocp = config('constants.ocp');

        $endpoint = $url; 
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_POST, false);
        @curl_setopt($ch, CURLOPT_URL, $endpoint);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'authorization: Bearer '.$bearer, 
                                                                                      'X-Nonce: '.$x_nonce, 
                                                                                      'X-Site: '.$x_site, 
                                                                                      'Ocp-Apim-Subscription-Key: '.$ocp));
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = array(
            "response"      => @curl_exec($ch),
            "statusCode"    => @curl_getinfo($ch, CURLINFO_HTTP_CODE),
            "curlErrors"    => curl_error($ch)
        );
        @curl_close($ch);

        return $result;
       
    }

    //Post para sage con sus cabeceras
    public function postSageCurl($url, $arr = null){
        $bearer = $bearer = config('constants.bearer_sage');
        $x_nonce = config('constants.x_nonce');
        $x_site = config('constants.x_site');
        $ocp = config('constants.ocp');

        $post_json = json_encode($arr);               
        $endpoint = $url; 
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        @curl_setopt($ch, CURLOPT_URL, $endpoint);
        error_log("ENDPOINT");
        error_log($endpoint);
        error_log("========");
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'authorization: Bearer '.$bearer, 
                                                                                      'X-Nonce: '.$x_nonce, 
                                                                                      'X-Site: '.$x_site, 
                                                                                      'Ocp-Apim-Subscription-Key: '.$ocp));
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