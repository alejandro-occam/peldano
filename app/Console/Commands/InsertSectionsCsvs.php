<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Department;
use App\Models\Section;

class InsertSectionsCsvs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsertSectionsCsvs:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Sections Csvs';

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
        $content = fopen(public_path().'/secciones.csv','r');
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
        
        //Creamos un array custom para secciones
        $array_sections_custom = [];
        foreach($array_data as $line){
            $words = explode(";", $line);
            $section['name'] = $words[1];
            $section['nomenclature'] = $words[0];
            $array_sections_custom[] = $section;
        }

        //Recorremos el csv de las relaciones entre departamentos y secciones
        $content = fopen(public_path().'/departamentos_secciones.csv','r');
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

        //Recorremos las filas y guardamos la tabla secciones
        foreach($array_data as $line){
            $words = explode(";", $line);
            //Consultamos si existe el calendario y si no lo creamos
            $department = Department::where('nomenclature', $words[0])->first();
            if($department){
                foreach($array_sections_custom as $section_custom){
                    if($section_custom['nomenclature'] == $words[1]){
                        //Consultamos si existe la Sección
                        $section = Section::where([
                            'name' => $section_custom['name'],
                            'nomenclature' => $section_custom['nomenclature'],
                            'id_department' => $department->id,
                        ])->first();
                        if(!$section){
                            Section::create([
                                'name' => $section_custom['name'],
                                'nomenclature' => $section_custom['nomenclature'],
                                'id_department' => $department->id,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
