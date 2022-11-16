<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CurlController;
use Config;
use File;

class GetTokenSage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getTokenSage:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get token from Sage';

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
        $requ_curls = new CurlController();
        
        //Consultamos el token de nuestro cliente
        $url = 'https://id.sage.com/oauth/token';
        $params['grant_type'] = 'authorization_code';
        $params['client_id'] = config('constants.client_id_sage');
        $params['client_secret'] = config('constants.client_secret_sage');
        $params['code'] = config('constants.code_sage');
        $params['redirect_uri'] = config('constants.redirect_uri_sage');
        $data_token_sage = json_decode($requ_curls->postCurl($url, $params, 3)['response'], true);
        if(!isset($data_token_sage['error'])){
            $array = Config::get('constants');
            $array['bearer_sage'] = $data_token_sage['access_token'];
            $array['refresh_token'] = $data_token_sage['refresh_token'];
            $array['id_token'] = $data_token_sage['id_token'];
            $data = var_export($array, 1);
            File::put(app_path() . '/../config/constants.php', "<?php\n return $data ;");
        }        
    }
}
