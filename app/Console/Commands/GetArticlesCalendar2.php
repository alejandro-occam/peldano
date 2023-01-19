<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Models\Project;
use App\Models\Calendar;


class GetArticlesCalendar2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getArticlesCalendar2:cron';

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
        //Leemos las filas del csv de Canales
        $content = fopen(public_path().'/articles_calendars2.csv','r');
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
        
        //Creamos un array custom para los articlulos
        $array_articles_calendars_custom = [];
        foreach($array_data as $line){
            $words = explode(";", $line);

            //Consultamos el proyecto
            $proyect = Project::leftJoin('channels', 'channels.id', 'proyects.id_channel')
                                ->leftJoin('sections', 'sections.id', 'proyects.id_section')
                                ->leftJoin('departments', 'departments.id', 'proyects.id_department')
                                ->where('proyects.nomenclature', $words[4])->first();
            error_log($proyect);
            return;
            if($article){
                //Consultamos el calendario
                $calendar = Calendar::where('name', $words[2])->where('id_article', 0)->first();
                if($calendar){
                    $calendar->id_article = $article->id;
                    $calendar->save();
                }
            }
        }
    }
}
