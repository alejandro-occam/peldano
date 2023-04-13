<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserObjetive;
use App\Models\Department;

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
    protected $description = 'Read objetives contacts';

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
        $content = fopen(public_path().'/contacts_objetives.csv','r');
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
                
                //Consultamos el departamento
                $department = Department::where('nomenclature', $info_contact[4])->first();

                if($department){
                    $user_objetive = UserObjetive::create([
                        'id_user' => $contact->id,
                        'obj_print' => $obj_print,
                        'obj_dig' => $obj_dig,
                        'obj_eve' => $obj_eve,
                        'id_department' => $department->id,
                        'year' => 2023
                    ]);
                }
            }
            $cont++;
        }
        fclose($content);
    }
}
