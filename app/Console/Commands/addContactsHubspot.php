<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\Contact;

class addContactsHubspot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addContactsHubspot:cron';

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
        $content = fopen(public_path().'/contacts_hubspot.csv','r');
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
        foreach($array_data as $key => $line){
            $words = explode(";", $line);
            if(!empty($words[3])){
                //Consultamos si existe la empresa
                $company = Company::where('name', $words[5])->first();

                if($company){
                    $name = null;
                    if(!empty($words[1])){
                        $name = $words[1];
                    }

                    $surname = null;
                    if(!empty($words[2])){
                        $surname = $words[2];
                    }

                    $email = $words[3];

                    $phone = null;
                    if(!empty($words[4])){
                        $phone = $words[4];
                    }

                    //Consultamos si existe el contacto
                    $contact = Contact::where('email', $email)->where('id_hubspot', $words[0])->first();

                    if($contact){
                        $contact->name = $name;
                        $contact->surnames = $surname;
                        $contact->phone = $phone;
                        $contact->id_company = $company->id;
                        $contact->save();

                    }else{
                        Contact::create([
                            'name' => $name,
                            'surnames' => $surname,
                            'email' => $email,
                            'phone' => $phone,
                            'id_company' => $company->id,
                            'id_hubspot' => $words[0]
                        ]);
                    }
                }
            }
        }
    }
}
