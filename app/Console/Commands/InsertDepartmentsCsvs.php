<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Department;

class InsertDepartmentsCsvs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsertDepartmentsCsvs:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Csvs';

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
        //Leemos las filas del csv de Departamentos
        $content = fopen(public_path().'/departamentos.csv','r');
        $data = '';
        $fila = 1;
        $array_data = array();
        while (($datos = fgetcsv($content, 1000, ",")) !== FALSE) {
            $numero = count($datos);
            $fila++;
            for ($c=0; $c < $numero; $c++) {
                $array_data[] = $datos[$c];
            }
        }
        fclose($content);
        
        //Recorremos las filas y guardamos la tabla department
        foreach($array_data as $line){
            $words = explode(";", $line);
            //Consultamos si existe el calendario y si no lo creamos
            $department = Department::where('name', $words[1])->where('nomenclature', $words[0])->first();
            if(!$department){
                Department::create([
                    'name' => $words[1],
                    'nomenclature' => $words[0]
                ]);
            }
        }
    }
}
