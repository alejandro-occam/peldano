<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillOrder;

class InvoiceValidationController extends Controller
{
    //Listado de facturas
    function listBillsOrder(Request $request){
        $array_bill_orders = BillOrder::select('bills_orders.*', 'proposals.id_user as id_consultant')
                                        ->leftJoin('orders', 'orders.id', 'bills_orders.id_order')
                                        ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')
                                        ->leftJoin('contacts', 'contacts.id', 'proposals.id_contact')
                                        ->leftJoin('companies', 'companies.id', 'contacts.id_company')
                                        ->get();

        $response['array_bill_orders'] = $array_bill_orders;
        $response['code'] = 1000;
        return response()->json($response);
    }
}
