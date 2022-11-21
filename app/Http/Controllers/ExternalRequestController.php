<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Company;
use App\Models\Contact;


class ExternalRequestController extends Controller
{
    //Guardar empresas desde Hubspot
    function saveCompaniesFromHubspot(Request $request){
        //Comprobamos si existe la empresa
        $company = Company::where('id_hubspot', $request->get('hub_id'))->first();
        $address = $request->get('direccion').', '.$request->get('direccion_2').', '.$request->get('ciudad').', '.$request->get('provincia').', '.$request->get('cp');
        if($company){
            $company->name = $request->get('name');
            $company->nif = $request->get('cifnif');
            $company->address = $address;
            $company->save();

        }else{
            $company = Company::create([
                'name' => $request->get('name'),
                'nif' => $request->get('cifnif'),
                'address' => $address,
                'id_hubspot' => $request->get('hub_id')
            ]);
        }
    }

    //Guardar contactos desde Hubspot
    function saveContactsFromHubspot(Request $request){
        $hub_id = $request->get('hub_id');
        //Comprobamos si existe la empresa
        $contacts = Contact::where('id_hubspot', $hub_id)->first();
        if($company){
            $contacts->name = $request->get('firstname');
            $contacts->surnames = $request->get('lastname');
            $contacts->email = $request->get('email');
            $contacts->phone = $request->get('phone');
            $contacts->save();
            
        }else{

            //Consultamos la empresa asociada a este contacto
            $url_contacts_association = 'https://api.hubapi.com/crm/v4/objects/contacts/'.$hub_id.'/associations/companies?limit=10&archived=false';
            $stop_companies_association_contacts = 0;
            while($stop_companies_association_contacts == 0){
                $array_companies_association_contacts_hubspot_obj = json_decode($requ_curls->getCurl($url_contacts_association, 1)['response'], true);
                foreach($array_companies_association_contacts_hubspot_obj["results"] as $company_association_contacts){
                    $id_company = $company_association_contacts['toObjectId'];
                    //Consultamos el contacto en la bd
                    $company = Company::where('id_hubspot', $id_company)->first();
                    if($company){
                        $stop_companies_association_contacts = 1;
                    }
                }

                if($stop_companies_association_contacts == 0){
                    if(!isset($company_association_contacts['paging'])){
                        $stop_companies_association_contacts = 1;
                        
                    }else{
                        $url = $company_association_contacts['paging']['next']['link'];
                    }
                }
            }

            $contact = Contact::create([
                'name' => $request->get('firstname'),
                'surnames' => $request->get('lastname'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'id_company' => $company->id,
                'id_hubspot' => $hub_id
            ]);
        }

        
    }
}
