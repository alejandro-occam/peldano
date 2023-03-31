<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserObjetive;

class ReadObjetivesContacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'readObjetivesContacts:cron';

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
        $content = fopen(public_path().'/objetives_consultant.csv','r');
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

        //error_log('datoos: '.print_r($array_data, true));
        $cont = 1;
        foreach($array_data as $data){
            $info_contact = explode(";", $data);
            //Comprobamos si existe el contacto
            $contact = User::where('email', $info_contact[0])->first();
            if($contact){
                //Preparamos los datos de los obejtivos
                if($info_contact[1] != '-'){
                    $obj_print_aux = explode("€", $info_contact[1]);
                    $obj_print = $obj_print_aux[0];

                }else{
                    $obj_print = 0;
                }
                
                if($info_contact[2] != '-'){
                    $obj_dig_aux = explode("€", $info_contact[2]);
                    $obj_dig = $obj_dig_aux[0];

                }else{
                    $obj_dig = 0;
                }
                
                if($info_contact[3] != '-'){
                    $obj_eve_aux = explode("€", $info_contact[3]);
                    $obj_eve = $obj_eve_aux[0];

                }else{
                    $obj_eve = 0;
                }
                
                $user_objetive = UserObjetive::create([
                    'id_user' => $contact->id,
                    'obj_print' => $obj_print,
                    'obj_dig' => $obj_dig,
                    'obj_eve' => $obj_eve,
                    'year' => 2023
                ]);
            }
            $cont++;
        }
        fclose($content);
    }
}
