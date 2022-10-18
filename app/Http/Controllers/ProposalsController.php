<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use DB;

class ProposalsController extends Controller
{
    //Consultar usuarios
    function getUsers(){
        $array_users = User::get();
        $response['array_users'] = $array_users;
        return response()->json($response);
    }

    //Consultar empresas
    function getCompanies(){
        $array_companies = Company::select('companies.*', DB::raw('CONCAT(contacts.name, " ", contacts.surnames) as fullname'), 'contacts.email')->leftJoin('contacts', 'contacts.id_company', 'companies.id')->get();
        $response['array_companies'] = $array_companies;
        return response()->json($response);
    }
}
