<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CurlController;
use Config;
use File;

class RefreshTokenSage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refreshTokenSage:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh token Sage';

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
        
        //Refrescamos el token de Sage
        $url = 'https://id.sage.com/oauth/token';
        $params['grant_type'] = 'refresh_token';
        $params['client_id'] = config('constants.client_id_sage');
        $params['client_secret'] = config('constants.client_secret_sage');
        $params['refresh_token'] = config('constants.refresh_token');
        error_log(print_r($params, true));
        $data_token_sage = json_decode($requ_curls->postCurl($url, 3, $params)['response'], true);

        error_log(print_r($data_token_sage, true));

        if(!isset($data_token_sage['error'])){
            $array = Config::get('constants');
            $array['bearer_sage'] = $data_token_sage['access_token'];
            error_log('bearer_sage: '.$array['bearer_sage']);
            $array['refresh_token'] = $data_token_sage['refresh_token'];
            $array['id_token'] = $data_token_sage['id_token'];
            $data = var_export($array, 1);
            File::put(app_path() . '/../config/constants.php', "<?php\n return $data ;");
        }        
    }
}
