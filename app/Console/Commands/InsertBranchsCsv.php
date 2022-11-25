<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Department;
use App\Models\Section;
use App\Models\Channel;
use App\Models\Project;
use App\Models\Chapter;
use App\Models\Batch;

class InsertBranchsCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsertBranchsCsv:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert branch from csv';

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
        $content = fopen(public_path().'/lotes.csv','r');
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
        $array_branchs_custom = [];
        foreach($array_data as $line){
            $words = explode(";", $line);
            $branch['name'] = $words[1];
            $branch['nomenclature'] = $words[0];
            $array_branchs_custom[] = $branch;
        }

        //Recorremos el csv de las relaciones entre secciones y canales
        $content = fopen(public_path().'/departamentos_secciones_canales_proyectos_capitulos_lotes.csv','r');
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
                        //Consultamos el proyecto
                        $project = Project::where('nomenclature', $words[3])->where('id_channel', $channel->id)->first();
                        if($project){
                            //Consultamos el capitulo
                            $chapter = Chapter::where('nomenclature', $words[4])->where('id_project', $project->id)->first();
                            if($chapter){
                                foreach($array_branchs_custom as $branch_custom){
                                    if($branch_custom['nomenclature'] == $words[4]){
                                        //Consultamos si existe el canal y si no lo creamos
                                        $branch = Batch::where([
                                            'name' => $branch_custom['name'],
                                            'nomenclature' => $branch_custom['nomenclature'],
                                            'id_chapter' => $chapter->id,
                                        ])->first();

                                        if(!$branch){
                                            Batch::create([
                                                'name' => $branch_custom['name'],
                                                'nomenclature' => $branch_custom['nomenclature'],
                                                'id_chapter' => $chapter->id,
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
    }
}
