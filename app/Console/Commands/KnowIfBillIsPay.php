<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class KnowIfBillIsPay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'knowIfBillIsPay:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Know if bill is pay';

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
        return 0;
    }
}
