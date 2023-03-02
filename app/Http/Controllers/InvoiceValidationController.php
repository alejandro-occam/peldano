<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillOrder;
use App\Models\ServiceBillOrder;
use DB;

class InvoiceValidationController extends Controller
{
    //Listado de facturas
    function listBillsOrder(Request $request){
        $select_type = $request->get('select_type');
        $select_validate = $request->get('select_validate');
        $date = $request->get('date');
        
        $array_bill_orders = BillOrder::select('bills_orders.*', 'proposals.id_user as id_consultant', 'companies.name as name_company')
                                        ->leftJoin('orders', 'orders.id', 'bills_orders.id_order')
                                        ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')
                                        ->leftJoin('contacts', 'contacts.id', 'proposals.id_contact')
                                        ->leftJoin('companies', 'companies.id', 'contacts.id_company');

        //Todas, personalizas o simples
        if(!empty($select_type)){
            if($select_type == 1){
                $array_bill_orders = $array_bill_orders->where('orders.is_custom', 1);
            }
            if($select_type == 2){
                $array_bill_orders = $array_bill_orders->where('orders.is_custom', 0);
            }
        }

        //Todas, si validadas, no validadas
        if(!empty($select_validate)){
            if($select_validate == 1){
                $array_bill_orders = $array_bill_orders->where('bills_orders.status_validate', 1);
            }
            if($select_validate == 2){
                $array_bill_orders = $array_bill_orders->where('bills_orders.status_validate', 0);
            }
        }
        
        $array_bill_orders = $array_bill_orders->get();
        //Consultamos los artículos de la factura
        foreach($array_bill_orders as $index => $bill_order){
            $is_delete = false;
            if(!empty($date)){
                $array_date_order = explode("-", $bill_order->date);
                $date_order = $array_date_order[2].'-'.$array_date_order[1].'-'.$array_date_order[0];

                $array_date_front = explode("-", $date);
                $date_custom = $array_date_front[2].'-'.$array_date_front[1].'-'.$array_date_front[0];

                if($date_custom < $date_order){
                    unset($array_bill_orders[$index]);
                    $is_delete = true;
                }
            }
            
            if(!$is_delete){
                $array_articles = array();
                $array_services_bill_order = ServiceBillOrder::select('articles.name', 'articles.id as id_article')
                                                                ->leftJoin('services', 'services.id', 'services_bills_orders.id_service')
                                                                ->leftJoin('articles', 'articles.id', 'services.id_article')
                                                                ->where('id_bill_order', $bill_order->id)
                                                                ->get();

                $exist = false;                                                
                foreach($array_services_bill_order as $service){
                    foreach($array_articles as $article){
                        if($article['id_article'] == $service['id_article']){
                            $custom_article['amount'] += 1;
                            $exist = true;
                        }
                    }
                    if(!$exist){
                        $custom_article['id_article'] = $service['id_article'];
                        $custom_article['name'] = $service['name'];
                        $custom_article['amount'] = 1;
                        $array_articles[] = $custom_article;
                    }
                    
                }
                $bill_order['array_articles'] = $array_articles;
            }
        }

        $response['array_bill_orders'] = $array_bill_orders;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Validar factura
    function validateBill(Request $request){
        if(!$request->has('id_bill')){
            $response['code'] = 10011;
            return response()->json($response);
        }

        $id_bill = $request->get('id_bill');

        if(empty($id_bill)){
            $response['code'] = 10012;
            return response()->json($response);
        }

        //Comprobamos si existe la factura
        $bill_order = BillOrder::find($id_bill);
        if(!$bill_order){
            $response['code'] = 10013;
            return response()->json($response);
        }

        $bill_order->status_validate = 1;
        $bill_order->save();

        $response['code'] = 1000;
        return response()->json($response);
    }
}