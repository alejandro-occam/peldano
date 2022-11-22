<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Calendar;
use App\Models\CalendarMagazine;

class ReadCalendarCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'readCsvCalendar:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read csv calendar';

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
        $content = fopen(public_path().'/calendario_publicaciones.csv','r');
        $data = '';
        $fila = 1;
        $array_data = array();
        while (($datos = fgetcsv($content, 1000, ",")) !== FALSE) {
            $numero = count($datos);
            $fila++;
            if($fila > 2){
                for ($c=0; $c < $numero; $c++) {
                    $array_data[] = $datos[$c];
                }
            }
        }
        fclose($content);
        
        //Recorremos las filas y guardamos la tabla calendar
        foreach($array_data as $line){
            $words = explode(";", $line);
            //Consultamos si existe el calendario y si no lo creamos
            $calendar = Calendar::where('id', $words[1])->where('name', $words[0])->first();
            if(!$calendar){
                Calendar::create([
                    'id' => $words[1],
                    'name' => $words[0]
                ]);
            }
        }

        //Recorremos las filas y guardamos la tabla calendar
        foreach($array_data as $line){
            $words = explode(";", $line);
            CalendarMagazine::create([
                'number' => $words[2],
                'title' => $words[3],
                'topics' => $this->formatingDate($words[4]),  
                'drafting' => $this->formatingDate($words[5]),  
                'commercial' => $this->formatingDate($words[6]),  
                'output' => $this->formatingDate($words[7]),  
                'billing' => $this->formatingDate($words[8]),  
                'front_page' => $this->formatingDate($words[9]), 
                'id_calendar' => $words[1]
            ]);
        }

    }

    //Formateo de fechas
    function formatingDate($date){
        $custom_date = '';
        if(!empty($date) && $date != '0000-00-00'){
            $date = date_create($date);
            $custom_date = date_format($date,"d-m-Y");
        }
        return $custom_date;
    }
}
