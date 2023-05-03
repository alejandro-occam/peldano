<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Models\Batch;

class addArticlesLosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addArticlesLost:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add articles lost to the client';

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
        $content = fopen(public_path().'/articles_losts.csv','r');
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
        
        foreach($array_data as $line){
            $words = explode(";", $line);

            //Consultamos el departamento
            $department = Department::where('nomenclature', $words[4])->first();
            if($department){
                //Consultamos la sección
                $section = Section::where('nomenclature', $words[5])->where('id_department', $department->id)->first();
                if($section){
                    //Consultamos el canal
                    $channel = Channel::where('nomenclature', $words[6])->where('id_section', $section->id)->first();
                    if($channel){
                        //Consultamos el proyecto
                        $project = Project::where('nomenclature', $words[7])->where('id_channel', $channel->id)->first();
                        if($project){
                            //Consultamos el capitulo
                            $chapter = Chapter::where('nomenclature', $words[8])->where('id_project', $project->id)->first();
                            if($chapter){
                                //Consultamos el capitulo
                                $batch = Batch::where('nomenclature', $words[9])->where('id_chapter', $chapter->id)->first();
                                if($batch){
                                    //Consultamos si existe ya el artículo
                                    $article = Article::where('id_sage', $words[0])->orWhere('name', $words[1])->first();
                                    if(!$article){
                                        //Creamos el artóculo
                                        Article::create([
                                            "name" => $words[1],
                                            "english_name" => null,
                                            "pvp" => $words[3],
                                            "id_sage" => $words[0],
                                            "id_family_sage" => $words[2],
                                            "id_batch" => $batch->id
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
