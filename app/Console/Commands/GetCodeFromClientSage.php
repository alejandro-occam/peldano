<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CurlController;
use App\Models\Company;

class getCodeFromClientSage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GetCodeFromClientSage:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get code from client of Sage';

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
        //Creamos el objeto Curl
        $requ_curls = new CurlController();

        $id_company_sage = config('constants.id_company_sage');

        //Consultamos las empresas
        $array_companies = Company::where('code_sage', null)->where('id_sage', '<>', null)->get();
        $url = 'https://sage200.sage.es/api/sales/Customers?$filter=CompanyId%20eq%20%27'.$id_company_sage.'%27%20and%20Id%20eq%20%27';
        $cont = 0;
        foreach($array_companies as $company){
            $custom_url = $url.$company->id_sage.'%27';
            $data = json_decode($requ_curls->getSageCurl($custom_url)['response'], true);
            error_log($cont);
            $company->id_sage = $data['value'][0]['Id'];
            $company->code_sage = $data['value'][0]['Code'];
            $company->save();
            $cont++;
            if($cont == 30){
                $cont = 0;
                sleep(60);
            }
        }
    }   
}
