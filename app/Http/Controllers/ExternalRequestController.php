<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExternalRequestController extends Controller
{
    //Guardar empresas desde Hubspot
    function saveCompaniesFromHubspot(Request $request){
        Log::error('hub_id: ' .  $request->get('hub_id'));
        //Comprobamos si existe la empresa
        /*$company = Company::where('id_hubspot', $company_hubspot['id'])->first();
        if($company){
            $company->name = $company_hubspot['properties']['name'];
            $company->nif = $company_hubspot['properties']['nif'];
            $company->address = $company_hubspot['properties']['address'];
            $company->save();

        }else{
            $company = Company::create([
                'name' => $company_hubspot['properties']['name'],
                'nif' => '',
                'address' => '',
                'id_hubspot' => $company_hubspot['id']
            ]);
        }*/
    }

    //Guardar contactos desde Hubspot
    function saveContactsFromHubspot(Request $request){
        Log::error('hub_id: ' .  $request->get('hub_id'));
        //Comprobamos si existe la empresa
        /*$company = Company::where('id_hubspot', $company_hubspot['id'])->first();
        if($company){
            $company->name = $company_hubspot['properties']['name'];
            $company->nif = $company_hubspot['properties']['nif'];
            $company->address = $company_hubspot['properties']['address'];
            $company->save();
            
        }else{
            $company = Company::create([
                'name' => $company_hubspot['properties']['name'],
                'nif' => '',
                'address' => '',
                'id_hubspot' => $company_hubspot['id']
            ]);
        }*/
    }
}
