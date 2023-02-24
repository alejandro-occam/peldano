<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\BillOrder;
use App\Models\Payment;

class ReportUnpaidInvoicesController extends Controller
{
    //Listado de facturas imagadas
    function reportsList(Request $request){
        $select_consultant = $request->get('select_consultant');
        $select_payment = $request->get('select_payment');
        $expiration_from = $request->get('expiration_from');
        $expiration_to = $request->get('expiration_to');
        $num_order = $request->get('num_order');
        $contact_id = $request->get('contact_id');
        $client_id_sage = $request->get('client_id_sage');
        $select_satus_bill = $request->get('select_satus_bill');

        $array_bill_orders = BillOrder::select('bills_orders.*', 'proposals.id_user as id_consultant', 'companies.name as name_company')
                                        ->leftJoin('orders', 'orders.id', 'bills_orders.id_order')
                                        ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')
                                        ->leftJoin('companies', 'companies.id', 'orders.id_company');

        if(!empty($client_id_sage)){
            $array_bill_orders = $array_bill_orders->leftJoin('payments', 'payments.id_bill_order', 'bills_orders.id')
                                                    ->where('payments.id_sage', $client_id_sage);
        }

        if($select_consultant != '0' && !empty($select_consultant)){
            $array_bill_orders = $array_bill_orders->where('proposals.id_user', $select_consultant);
        }                       

        if(!empty($num_order)){
            $array_bill_orders = $array_bill_orders->where('orders.id', $num_order);
        }

        if(!empty($contact_id)){
            $array_bill_orders = $array_bill_orders->where('proposals.id_contact', $contact_id);
        }

        

        $array_bill_orders = $array_bill_orders->get();
        $total_amount = 0;
        foreach($array_bill_orders as $bill_order){
            $bill_order['payment'] = 0;
            //Consultamos el payment
            $payment = Payment::where('id_bill_order', $bill_order->id)->first();
            if($payment){
                $bill_order['payment'] = 1;
            }

            $total_amount += floatval($bill_order->amount);
        }
        $response['code'] = 1000;
        $response['array_bill_orders'] = $array_bill_orders;
        $response['total_amount'] = $total_amount;
        return response()->json($response);
    }
}
