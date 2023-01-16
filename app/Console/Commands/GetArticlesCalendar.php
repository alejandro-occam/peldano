<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Models\Calendar;


class GetArticlesCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getArticlesCalenda:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get articles calendar';

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
        $content = fopen(public_path().'/articles_calendars.csv','r');
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

            //Consultamos el artículo
            $article = Article::where('id_sage', $words[0])->first();
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
