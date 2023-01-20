<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ExternalRequestController;
use App\Models\BillOrder;

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
        //Creamos un objeto para el controller ExternalRequest
        $requ_external_request = new ExternalRequestController();

        //Creamos el objeto request
        $request = new \Illuminate\Http\Request();

        $array_bills_orders = BillOrder::select('bills_orders.*')
                                        ->leftJoin('payments', 'payments.id_bill_order', 'bills_orders.id')
                                        ->where('payments.id_bill_order', null)
                                        ->where('bills_orders.id_sage', '<>', null)
                                        ->where('bills_orders.receipt_order_sage', '<>', null)
                                        ->get();

        foreach($array_bills_orders as $bill_order){
            $request->replace(['receipt_order_sage' => $bill_order->receipt_order_sage, 'id_bill_order' => $bill_order->id]);
            //$request->replace(['array_sage_products' => $array_sage_products, 'customer_id' => $company->id_sage, 'id_bill_order' => $bill_order->id, 'id_order' => $bill_order->id_order, 'amount' => $bill_order->amount, 'number' => $number]);
            $requ_external_request->getReceiptSage($request);
        }
    }
}
