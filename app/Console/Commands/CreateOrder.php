<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BillOrder;
use App\Models\Order;
use App\Models\Proposal;
use App\Models\ProposalBill;
use App\Models\Bill;
use App\Models\ServiceBill;
use App\Models\Service;
use App\Models\Article;
use App\Models\Company;
use App\Http\Controllers\CurlController;
use App\Http\Controllers\ExternalRequestController;

class CreateOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createOrder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create delivery note and order';

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
        //Consultamos las ordernas con fecha actual
        $date = Date('d-m-Y');
        $array_bills_orders = BillOrder::where('date', $date)->where('id_sage', '')->orWhere('date', $date)->where('id_sage', null)->get();
        //Creamos un objeto para el controller ExternalRequest
        $requ_external_request = new ExternalRequestController();

        //Creamos el objeto request
        $request = new \Illuminate\Http\Request();

        //Recorremos las facturas creadas
        foreach($array_bills_orders as $bill_order){
            //Consultamos la orden
            $order = Order::find($bill_order->id_order);
            
            //Consultamos la propuesta de la orden
            $proposal = Proposal::find($order->id_proposal);

            //Consultamos la empresa a la que pertenece la propuesta
            $company = Company::select('companies.*')->leftJoin('contacts', 'contacts.id_company', 'companies.id')->where('contacts.id', $proposal->id_contact)->first();

            //Creamos un array para guardar los id_sage de cada artículo-producto
            $array_sage_products = array();

            //Consultamos las facturas internas de la propuesta para consultar los artículos-productos
            $array_proposals_bills = ProposalBill::where('id_proposal', $proposal->id)->get();
            foreach($array_proposals_bills as $proposal_bill){
                //Consultamos la fecha de la factura
                $bill_bd = Bill::find($proposal_bill->id_bill);

                //Comparamos fechas para saber si estamos haciendo las consultas en la factura correcta
                if($bill_bd->date == $bill_order->date){
                    //Consultamos los artículos de la factura
                    $services_bills = ServiceBill::where('id_bill', $proposal_bill->id_bill)->get();
                    foreach($services_bills as $service_bill){
                        $service = Service::find($service_bill->id_service);
                        $article = Article::find($service->id_article);

                        //Consultamos el id_sage del artículo
                        $request->replace(['code_sage' => $article->id_sage]);
                        $id_sage = $requ_external_request->getProductSage($request);
                        $product['id'] = $id_sage;
                        $product['pvp'] = $service->pvp;
                        $array_sage_products[] = $product;
                    }
                }
            }

            error_log(print_r($array_sage_products, true));
            //Generamos el albarán en Sage
            $number = Date('y').$bill_order->id.$bill_order->id_order;
            $request->replace(['array_sage_products' => $array_sage_products, 'customer_id' => $company->id_sage, 'id_bill_order' => $bill_order->id, 'id_order' => $bill_order->id_order, 'amount' => $bill_order->amount, 'number' => $number]);
            $invoice_custom = $requ_external_request->generateDeliveryNoteSage($request);
            if($id_sage != null){
                $bill_order->id_sage = $invoice_custom['Id'];
                $bill_order->receipt_order_sage = $invoice_custom['receipt_order_sage'];
                $bill_order->save();
            }
        }
        return 0;
    }
}
