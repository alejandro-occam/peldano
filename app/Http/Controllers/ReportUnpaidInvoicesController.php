<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\BillOrder;

class ReportUnpaidInvoicesController extends Controller
{
    //Listado de facturas imagadas
    function reportsList(Request $request){
        $array_bill_orders = BillOrder::select('bills_orders.*', 'proposals.id_user as id_consultant', 'companies.name as name_company')
                                        ->leftJoin('orders', 'orders.id', 'bills_orders.id_order')
                                        ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')
                                        ->leftJoin('companies', 'companies.id', 'orders.id_company')
                                        ->get();
        $response['code'] = 1000;
        $response['array_bill_orders'] = $array_bill_orders;
        return response()->json($response);
    }
}
