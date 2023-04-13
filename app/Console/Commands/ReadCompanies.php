<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;

class ReadCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'readCompanies:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //Leemos las filas del csv
        $content = fopen(public_path().'/companies_table.csv','r');
        $data = '';
        $array_data = array();
        $fila = 1;

        while (($datos = fgetcsv($content, 1000, ",")) !== FALSE) {
            $numero = count($datos);
            $fila++;
            for ($c=0; $c < $numero; $c++) {
                $array_data[] = $datos[$c];
            }
        }

        $cont = 1;
        foreach($array_data as $data){
            $info_company = explode(";", $data);
            //Comprobamos si existe la empresa
            $company = Company::where('id_hubspot', $info_company[0])->first();
            if($company){
                //Preparamos los datos de las empresas
                $name = null;
                if(!empty($info_company[1])){
                   $name = $info_company[1];
                }
                
                //CIF/NIF
                $nif = null;
                if(!empty($info_company[3])){
                    $nif = $info_company[3];
                }

                //Dirección
                $address = null;
                //Ciudad
                if(!empty($info_company[5])){
                    $address .= $info_company[5].', ';
                }
                //Dirección
                if(!empty($info_company[6])){
                    $address .= $info_company[6].', ';
                }
                //Dirección 2
                if(!empty($info_company[7])){
                    $address .= $info_company[7].', ';
                }
                //Provincia
                if(!empty($info_company[4])){
                    $address .= $info_company[4].', ';
                }
                //CP
                if(!empty($info_company[2])){
                    $address .= $info_company[2].', ';
                }
                
                $company = Company::create([
                    'name' => $name,
                    'nif' => $nif,
                    'address' => $address,
                    'id_hubspot' => $$info_company[0]
                ]);
            }
            $cont++;
        }
        fclose($content);
    }
}
