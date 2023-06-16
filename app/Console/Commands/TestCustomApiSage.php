<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CurlController;

class TestCustomApiSage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testCustomApiSage:cron';

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
        $requ_curls = new CurlController();
        //Consultamos el token de nuestro cliente
        $url = 'http://217.76.155.168:8080/infoges/callClient';

        $response = json_decode($requ_curls->getNormalCurl($url)['response'], true);
        error_log(print_r($response, true));
        return;
    }
}
