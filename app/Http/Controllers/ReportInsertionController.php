<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ServiceBillOrder;
use DB;

class ReportInsertionController extends Controller
{
    //Listado de facturas según el filtro
    function reportsList(Request $request){
        //Incializamos el contador
        DB::statement(DB::raw('SET @counter := 0'));

        //Consultamos los servicios
        $array_services_bills_orders = ServiceBillOrder::select(DB::raw('(@counter := @counter+1) as `index`'), 'articles.*', 'companies.name as client_name', 'orders.advertiser as advertise', 'orders.id as id_order', 
                                                            'orders.type_proposal', 'proposals.is_custom as type_order', 'services.pvp as amount', 'proposals.id_user as consultant', 'services_bills_orders.id as id_services_bills_orders')
                                                            ->leftJoin('services', 'services.id', 'services_bills_orders.id_service')
                                                            ->leftJoin('articles', 'articles.id', 'services.id_article')
                                                            ->leftJoin('bills_orders', 'bills_orders.id', 'services_bills_orders.id_bill_order')
                                                            ->leftJoin('orders', 'orders.id', 'bills_orders.id_order')
                                                            ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')
                                                            ->leftJoin('contacts', 'contacts.id', 'proposals.id_contact')
                                                            ->leftJoin('companies', 'companies.id', 'contacts.id_company')
                                                            ->get();

        //Calculamos el total de los servicios
        $total = ServiceBillOrder::leftJoin('services', 'services.id', 'services_bills_orders.id_service')
                                        ->leftJoin('articles', 'articles.id', 'services.id_article')
                                        ->leftJoin('bills_orders', 'bills_orders.id', 'services_bills_orders.id_bill_order')
                                        ->leftJoin('orders', 'orders.id', 'bills_orders.id_order')
                                        ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')
                                        ->leftJoin('contacts', 'contacts.id', 'proposals.id_contact')
                                        ->leftJoin('companies', 'companies.id', 'contacts.id_company')
                                        ->sum('services.pvp');
                                            
        $response['code'] = 1000;
        $response['array_articles'] = $array_services_bills_orders;
        $response['total_amount'] = $total;
        return response()->json($response);
    }
}
