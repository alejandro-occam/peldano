<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Department;
use App\Models\Section;
use App\Models\Channel;
use App\Models\Project;

class InsertProjectsCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsertProjectsCsvs:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inseret Projects Csv';

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
        //Leemos las filas del csv de Canales
        $content = fopen(public_path().'/proyectos.csv','r');
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
        
        //Creamos un array custom para canales
        $array_projects_custom = [];
        foreach($array_data as $line){
            $words = explode(";", $line);
            $project['name'] = $words[1];
            $project['nomenclature'] = $words[0];
            $array_projects_custom[] = $project;
        }

        //Recorremos el csv de las relaciones entre secciones y canales
        $content = fopen(public_path().'/departamentos_secciones_canales_proyectos.csv','r');
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

        //Recorremos las filas y guardamos la tabla canales
        foreach($array_data as $line){
            $words = explode(";", $line);
            //Consultamos el departamento
            $department = Department::where('nomenclature', $words[0])->first();
            if($department){
                //Consultamos la sección
                $section = Section::where('nomenclature', $words[1])->where('id_department', $department->id)->first();
                if($section){
                    //Consultamos el canal
                    $channel = Channel::where('nomenclature', $words[2])->where('id_section', $section->id)->first();
                    if($channel){
                        foreach($array_projects_custom as $project_custom){
                            if($project_custom['nomenclature'] == $words[3]){
                                //Consultamos si existe el canal y si no lo creamos
                                if($words[0] == 'DEN' && $words[1] = 'DEN' && $words[2] = 'DIG' && $words[3] == 'GD' ){
                                    error_log('hola');
                                }
                                $project = Project::where([
                                    'name' => $project_custom['name'],
                                    'nomenclature' => $project_custom['nomenclature'],
                                    'id_channel' => $channel->id,
                                ])->first();

                                if(!$project){
                                    Project::create([
                                        'name' => $project_custom['name'],
                                        'nomenclature' => $project_custom['nomenclature'],
                                        'id_channel' => $channel->id,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
