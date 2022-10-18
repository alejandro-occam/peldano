<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProposalsController extends Controller
{
    //

    //Consultar usuarios
    function getUsers(){
        $array_users = User::get();
        $response['array_users'] = $array_users;
        return response()->json($response);
    }
}
