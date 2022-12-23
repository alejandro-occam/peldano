<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Department;
use App\Models\Section;
use App\Models\Channel;
use App\Models\Project;
use App\Models\Chapter;
use App\Models\Batch;
use App\Models\Article;

class InsertArticlesCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsertArticlesCsv:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert articles from csv file';

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
        $content = fopen(public_path().'/articulos.csv','r');
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
        $array_articles_custom = [];
        foreach($array_data as $line){
            $words = explode(";", $line);
            $article['name'] = $words[1];
            $article['id_sage'] = $words[0];
            $article['id_family_sage'] = $words[2];
            $article['pvp'] = $words[3];
            
            $article['department'] = $words[4];
            $article['section'] = $words[5];
            $article['channel'] = $words[6];
            $article['project'] = $words[7];
            $article['chapter'] = $words[8];
            $article['batch'] = $words[9];
            $array_articles_custom[] = $article;
        }

        //Recorremos las filas y guardamos la tabla canales
        foreach($array_articles_custom as $article){
            //Consultamos el departamento
            $department = Department::where('nomenclature', $article['department'])->first();
            if($department){
                //Consultamos la sección
                $section = Section::where('nomenclature', $article['section'])->where('id_department', $department->id)->first();
                if($section){
                    //Consultamos el canal
                    $channel = Channel::where('nomenclature', $article['channel'])->where('id_section', $section->id)->first();
                    if($channel){
                        //Consultamos el proyecto
                        $project = Project::where('nomenclature', $article['project'])->where('id_channel', $channel->id)->first();
                        if($project){
                            //Consultamos el capitulo
                            $chapter = Chapter::where('nomenclature', $article['chapter'])->where('id_project', $project->id)->first();
                            if($chapter){
                                //Consultamos el capitulo
                                $batch = Batch::where('nomenclature', $article['batch'])->where('id_chapter', $chapter->id)->first();
                                if($batch){
                                    //Consultamos si existe el articulo y si no lo creamos
                                    $article_obj = Article::where([
                                        'name' => $article['name'],
                                        'id_batch' => $batch->id,
                                    ])->first();

                                    if(!$article_obj){
                                        if(str_contains($article['pvp'], '-')){
                                            $article['pvp'] = 0;
                                        }

                                        Article::create([
                                            'name' => $article['name'],
                                            'pvp' => $article['pvp'],
                                            'id_sage' => $article['id_sage'],
                                            'id_family_sage' => $article['id_family_sage'],
                                            'pvp' => $article['pvp'],
                                            'id_batch' => $batch->id,
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
