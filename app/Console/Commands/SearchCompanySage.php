<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CurlController;
use App\Models\Company;

class SearchCompanySage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'searchCompanySage:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search company Sagen';

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
        $company = config('constants.id_company_sage');
        //Consultamos las empresas con id_sage null y que tengan CIF/NIF
        $array_companies = Company::whereNull('id_sage')->whereNotNull('nif')->get();

        foreach($array_companies as $company_obj){
            $url = 'https://sage200.sage.es:443/api/sales/Customers?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20TaxNumber%20eq%20%27'.$company_obj->nif.'%27';
            error_log($url);
            $data = json_decode($requ_curls->getSageCurl($url)['response'], true);
            error_log(print_r($data, true));
            if(isset($data['diagnosis'][0]['errorCode'])){
                sleep(30);
                $url = 'https://sage200.sage.es:443/api/sales/Customers?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20TaxNumber%20eq%20%27'.$company_obj->nif.'%27';
                $data = json_decode($requ_curls->getSageCurl($url)['response'], true);
                if(isset($data['value'][0]['Id'])){
                    $company_obj->id_sage = $data['value'][0]['Id'];
                    $company_obj->save();
                }

            }else if(isset($data['value'][0]['Id'])){
                $company_obj->id_sage = $data['value'][0]['Id'];
                $company_obj->save();
            }
            
        }
        /*$url = 'https://sage200.sage.es:443/api/sales/ProductFamilies?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company.'%27';
        error_log($url);
        $data = json_decode($requ_curls->getSageCurl($url)['response'], true);
        error_log(print_r($data, true));*/
    }
}
