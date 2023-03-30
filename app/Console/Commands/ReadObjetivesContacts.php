<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReadObjetivesContacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        $fila = 1;
        $array_data = array();
        while (($datos = fgetcsv($content, 1000, ",")) !== FALSE) {
            $numero = count($datos);
            $fila++;
            if($fila > 1){
                for ($c=0; $c < $numero; $c++) {
                    $array_data[] = $datos[$c];
                }
            }
        }

        $cont = 1;
        foreach($array_data as $data){
            $info_contact = explode(";", $data);
            //Comprobamos si existe el contacto
            $contact = User::where('name', $info_contact[0])->where('email', $info_contact[1])->first();
            if(!$contact){
                $user_obj = User::create([
                    'email' => $info_contact[1],
                    'password' => Hash::make($info_contact[1]),
                    'name' => $this->remove_accents($info_contact[0]),
                    'id_position' => 1
                ]);
            }
            $cont++;
        }
        fclose($content);
    }
}
