<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Channel;
use App\Models\Section;
use App\Models\Department;

class InsertChannelsCsv extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsertChannelsCsvs:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert Channels Csvs';

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
        $content = fopen(public_path().'/canales.csv','r');
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
        $array_channels_custom = [];
        foreach($array_data as $line){
            $words = explode(";", $line);
            $channel['name'] = $words[1];
            $channel['nomenclature'] = $words[0];
            $array_channels_custom[] = $channel;
        }

        //Recorremos el csv de las relaciones entre secciones y canales
        $content = fopen(public_path().'/departamentos_secciones_canales.csv','r');
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
                    foreach($array_channels_custom as $channel_custom){
                        if($channel_custom['nomenclature'] == $words[2]){
                            //Consultamos si existe el canal y si no lo creamos
                            $channel = Channel::where([
                                'name' => $channel_custom['name'],
                                'nomenclature' => $channel_custom['nomenclature'],
                                'id_section' => $section->id,
                            ])->first();

                            if(!$channel){
                                Channel::create([
                                    'name' => $channel_custom['name'],
                                    'nomenclature' => $channel_custom['nomenclature'],
                                    'id_section' => $section->id,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
