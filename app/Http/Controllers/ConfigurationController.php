<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ConfigurationController extends Controller
{
    //

    function listUsers(){
        $user = User::get();
        return $user;
    }
}
