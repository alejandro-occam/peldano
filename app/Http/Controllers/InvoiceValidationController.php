<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillOrder;
use App\Models\ServiceBillOrder;
use App\Models\ConsultanOrder;
use App\Models\User;
use App\Models\Order;
use App\Models\Article;
use App\Models\Service;
use App\Models\Proposal;
use App\Models\Company;
use DateTime;
use DB;

class InvoiceValidationController extends Controller
{
    //Listado de facturas
    function listBillsOrder(Request $request){
        $select_type = $request->get('select_type');
        $select_validate = $request->get('select_validate');
        $date = $request->get('date');
        
        $array_bill_orders_custom = BillOrder::select('bills_orders.*', 'orders.id as id_order', 'orders.advertiser as advertiser', 'proposals.id_user as id_consultant', 'companies.name as name_company', 'companies.id_sage as id_company_sage', 'proposals.is_custom as type_order', 'orders.is_custom  as order_custom')
                                        ->leftJoin('orders', 'orders.id', 'bills_orders.id_order')
                                        ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')
                                        ->leftJoin('contacts', 'contacts.id', 'proposals.id_contact')
                                        ->leftJoin('pay_invoices', 'pay_invoices.id_bill', 'bills_orders.id')
                                        ->leftJoin('companies', 'companies.id', 'contacts.id_company')
                                        ->where('pay_invoices.id', null);

        //Todas, personalizas o simples
        if(!empty($select_type)){
            if($select_type == 1){
                $array_bill_orders_custom = $array_bill_orders_custom->where('orders.is_custom', 1);
            }
            if($select_type == 2){
                $array_bill_orders_custom = $array_bill_orders_custom->where('orders.is_custom', 0);
            }
        }

        //Todas, si validadas, no validadas
        if(!empty($select_validate)){
            if($select_validate == 1){
                $array_bill_orders_custom = $array_bill_orders_custom->where('bills_orders.status_validate', 1);
            }
            if($select_validate == 2){
                $array_bill_orders_custom = $array_bill_orders_custom->where('bills_orders.status_validate', 0);
            }
        }
        
        $array_bill_orders_custom = $array_bill_orders_custom->get();
        error_log('array_bill_orders_custom: '.$array_bill_orders_custom);

        $array_dates = array();

        //Consultamos los artículos de la factura
        foreach($array_bill_orders_custom as $index => $bill_order){
            $is_delete = false;
            if(!empty($date)){
                $array_date_order = explode("-", $bill_order->date);
                $date_order = $array_date_order[2].'-'.$array_date_order[1].'-'.$array_date_order[0];
                $bill_order['custom_date'] = $date_order;
                $array_dates[] = $bill_order['custom_date'];
                $array_date_front = explode("-", $date);
                $date_custom = $array_date_front[2].'-'.$array_date_front[1].'-'.$array_date_front[0];

                if($date_custom < $date_order){
                    unset($array_bill_orders_custom[$index]);
                    $is_delete = true;
                }
            }
            
            if(!$is_delete){
                $array_articles = array();
                $array_services_bill_order = ServiceBillOrder::select('articles.name', 'articles.pvp as price_article', 'services.pvp as price_article_service', 'articles.id as id_article', 'articles.id_sage as id_sage_article')
                                                                ->leftJoin('services', 'services.id', 'services_bills_orders.id_service')
                                                                ->leftJoin('articles', 'articles.id', 'services.id_article')
                                                                ->where('id_bill_order', $bill_order->id)
                                                                ->get();

                $exist = false;                                                
                foreach($array_services_bill_order as $service){
                    foreach($array_articles as $key_article => $article){
                        if($article['id_article'] == $service['id_article']){
                            $array_articles[$key_article]['amount'] += 1;
                            $exist = true;
                            if($bill_order['order_custom'] == 1){
                                if($service['price_article_service'] > 0){
                                    $array_articles[$key_article]['real_amount'] = ($service['price_article_service'] * $array_articles[$key_article]['amount'] ) / $count_bill_order;
                                }
                            }
                        }
                    }
                    if(!$exist){
                        $custom_article['discount_percent'] = 0;
                        if($service['price_article'] != $service['price_article_service']){
                            if($service['price_article_service'] == 0){
                                $custom_article['discount_percent'] = 100;
                            }else{
                                $custom_article['discount_percent'] = ($service['price_article_service'] * 100) / $service['price_article'];
                            }
                        }
                        $custom_article['id_sage_article'] = $service['id_sage_article'];
                        $custom_article['id_article'] = $service['id_article'];
                        $custom_article['name'] = $service['name'];
                        $custom_article['price_article'] = $service['price_article'];
                        $custom_article['price_article_percent'] = $service['price_article_percent'];
                        $custom_article['amount'] = 1;
                        $custom_article['amount_percent'] = number_format(1, 3);
                        $custom_article['real_amount'] = $service['price_article_service'];
                        if($bill_order['order_custom'] == 1){
                            //Consultamos el número de facturas que hacen la orden
                            $count_bill_order = BillOrder::where('id_order', $bill_order['id_order'])->count();
                            $custom_article['amount_percent'] = number_format(1 / $count_bill_order, 3);
                            $custom_article['price_article_percent'] = $custom_article['price_article'] * $custom_article['amount_percent'];
                            $custom_article['real_amount'] = 0;
                            if($service['price_article_service'] > 0){
                                $custom_article['real_amount'] = ($service['price_article_service'] * $custom_article['amount'] ) / $count_bill_order;
                            }
                            
                        }
                        $array_articles[] = $custom_article;
                    }
                    
                }
                $bill_order['array_articles'] = $array_articles;
            }

            //Consultamos los consultores
            $user = User::find($bill_order['id_consultant']);
            $array_custom_consultant = array();
            $custom_consultant['id_consultant'] = $user->id;
            $custom_consultant['percentage'] = 100;
            $custom_consultant['name'] = $user->name.' '.$user->surname;
            $array_custom_consultant[] = $custom_consultant;

            $array_consultants = ConsultanOrder::where('id_order', $bill_order['id_order'])->get();
            foreach($array_consultants as $consultant){
                $user_consultant = User::find($consultant->id_consultant);
                $custom_consultant['id_consultant'] = $user_consultant->id;
                $custom_consultant['percentage'] = $consultant->percentage;
                $custom_consultant['name'] = $user_consultant->name.' '.$user_consultant->surname;
                $array_custom_consultant[] = $custom_consultant;
                $array_custom_consultant[0]['percentage'] -= $consultant->percentage;
            }
            $bill_order['array_custom_consultant'] = $array_custom_consultant;
        }

        $array_bill_orders = array();
        foreach($array_bill_orders_custom as $bill_order_custom){
            $bill_order['type_order'] = $bill_order_custom['type_order'];
            $bill_order['id_company_sage'] = $bill_order_custom['id_company_sage'];
            $bill_order['name_company'] = $bill_order_custom['name_company'];
            $bill_order['id_order'] = $bill_order_custom['id_order'];
            $bill_order['amount'] = $bill_order_custom['amount'];
            $bill_order['array_custom_consultant'] = $bill_order_custom['array_custom_consultant'];
            $bill_order['date'] = $bill_order_custom['date'];
            $bill_order['expiration'] = $bill_order_custom['expiration'];
            $bill_order['way_to_pay'] = $bill_order_custom['way_to_pay'];
            $bill_order['num_order'] = $bill_order_custom['num_order'];
            $bill_order['observations'] = $bill_order_custom['observations'];
            $bill_order['internal_observations'] = $bill_order_custom['internal_observations'];
            $bill_order['array_articles'] = $bill_order_custom['array_articles'];
            $bill_order['custom_date'] = $bill_order_custom['custom_date'];
            $bill_order['advertiser'] = $bill_order_custom['advertiser'];
            
            $array_bill_orders[] = $bill_order_custom;
        }

        usort($array_bill_orders, array($this, "sortFunction"));

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

        //Generamos el albarán y la factura en SAGE
        $date = Date('d-m-Y');
        //Creamos un objeto para el controller ExternalRequest
        $requ_external_request = new ExternalRequestController();

        //Creamos el objeto request
        $request = new \Illuminate\Http\Request();

        //Consultamos la orden
        $order = Order::find($bill_order->id_order);
            
        //Consultamos la propuesta de la orden
        $proposal = Proposal::find($order->id_proposal);

        //Consultamos la empresa a la que pertenece la propuesta
        $company = Company::select('companies.*')->leftJoin('contacts', 'contacts.id_company', 'companies.id')->where('contacts.id', $proposal->id_contact)->first();

        //Creamos un array para guardar los id_sage de cada artículo-producto
        $array_sage_products = array();

        //Consultamos los artículos de la factura
        $services_bills_orders = ServiceBillOrder::where('id_bill_order', $bill_order->id)->get();
        foreach($services_bills_orders as $service_bill_order){
            $service = Service::find($service_bill_order->id_service);
            $article = Article::find($service->id_article);

            //Consultamos el id_sage del artículo
            $request->replace(['code_sage' => $article->id_sage]);
            $id_sage = $requ_external_request->getProductSage($request);
            $product['id'] = $id_sage;
            $product['pvp'] = $service->pvp;
            $array_sage_products[] = $product;
        }

        error_log(print_r($array_sage_products, true));
        //Generamos el albarán en Sage
        $number = Date('ymd').$bill_order->id;
        $request->replace(['array_sage_products' => $array_sage_products, 'customer_id' => $company->id_sage, 'id_bill_order' => $bill_order->id, 'id_order' => $bill_order->id_order, 'amount' => $bill_order->amount, 'number' => $number]);
        $invoice_custom = $requ_external_request->generateDeliveryNoteSage($request);
        error_log('invoice_custom: '.print_r($invoice_custom, true));
        if($id_sage != null && !empty($invoice_custom)){
            $bill_order->id_sage = $invoice_custom['Id'];
            $bill_order->receipt_order_sage = $invoice_custom['receipt_order_sage'];
            $bill_order->save();
        }

        $bill_order->date = $date;
        $bill_order->save();

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Ordernar array por fecha
    function sortFunction( $a, $b ) {
        //return strtotime($a["custom_date_aux"]) + strtotime($b["custom_date_aux"]);
        error_log('a: '.$a['custom_date']);
        error_log('b: '.$b['custom_date']);
        $ad = new DateTime($a['custom_date']);
        $bd = new DateTime($b['custom_date']);

        if ($ad == $bd) {
            return 0;
        }

        return $ad > $bd ? -1 : 1;
    }
}
