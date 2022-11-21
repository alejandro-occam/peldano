<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;
use App\Models\Company;
use App\Http\Controllers\CurlController;

class GetContactsCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getContactsCompany:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get contacts and companies from Hubspot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $requ_curls = new CurlController();

        //Consultamos las empresas registradas
        $url = 'https://api.hubapi.com/crm/v3/objects/companies?limit=10&archived=false';
        $stop_companies = 0;
        while($stop_companies == 0){
            $array_companies_hubspot_obj = json_decode($requ_curls->getCurl($url, 1)['response'], true);
            foreach($array_companies_hubspot_obj["results"] as $company_hubspot){
                //Comprobamos si existe la empresa
                $company = Company::where('id_hubspot', $company_hubspot['id'])->first();
                if(!$company){
                    $company = Company::create([
                        'name' => $company_hubspot['properties']['name'],
                        'nif' => '',
                        'address' => '',
                        'id_hubspot' => $company_hubspot['id']
                    ]);
                }

                //Consultamos los contactos asociados a esta empresa
                $url_contacts_association = 'https://api.hubapi.com/crm/v4/objects/companies/6262437110/associations/contacts?limit=10&archived=false';
                $stop_companies_association_contacts = 0;
                while($stop_companies_association_contacts == 0){
                    $array_companies_association_contacts_hubspot_obj = json_decode($requ_curls->getCurl($url_contacts_association, 1)['response'], true);
                    foreach($array_companies_association_contacts_hubspot_obj["results"] as $company_association_contacts){
                        $id_contact = $company_association_contacts['toObjectId'];
                        //Consultamos el contacto en la bd
                        $contact = Contact::where('id_hubspot', $id_contact)->first();
                        if(!$contact){
                            $url_contact = 'https://api.hubapi.com/crm/v4/objects/contacts/'.$id_contact;
                            $contact_hubspot_obj = json_decode($requ_curls->getCurl($url_contact, 1)['response'], true);
                            Contact::create([
                                'name' => $contact_hubspot_obj['properties']['firstname'],
                                'surnames' => $contact_hubspot_obj['properties']['lastname'],
                                'email' => $contact_hubspot_obj['properties']['email'],
                                'phone' => '',
                                'id_company' => $company->id,
                                'id_hubspot' => $contact_hubspot_obj['id']
                            ]);
                        }
                    }

                    if(!isset($company_association_contacts['paging'])){
                        $stop_companies_association_contacts = 1;
                        
                    }else{
                        $url = $company_association_contacts['paging']['next']['link'];
                    }
                }
                

                if(!isset($company_hubspot['paging'])){
                    $stop_companies = 1;

                }else{
                    $url = $company_hubspot['paging']['next']['link'];
                }
            }
        }
        /*$company = config('constants.id_company_sage');
        $url = 'https://sage200.sage.es:443/api/sales/ProductFamilies?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company.'%27';
        error_log($url);
        $data = json_decode($requ_curls->getSageCurl($url)['response'], true);
        error_log(print_r($data, true));*/
    }
}
