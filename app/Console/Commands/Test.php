<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\BillOrder;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cron';

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
        $id = 5;
        $order = Order::find($id);
        if(!$order){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //Consultamos las facturas de la orden y miramos si alguna es anterior a la fecha actual. Si es así, no se puede eliminar.
        $array_bills_orders = BillOrder::where('id_order', $order->id)->get();
        $date_now = Date('Y-m-d');
        $will_delete = true;

        foreach($array_bills_orders as $bill_order){
            $array_date_custom_bill = explode("-", $bill_order->date);
            $date_custom_bill = $array_date_custom_bill[2].'-'.$array_date_custom_bill[1].'-'.$array_date_custom_bill[0];
            if ($date_custom_bill < $date_now){
                $will_delete = false;
            }
        }
    }
}
