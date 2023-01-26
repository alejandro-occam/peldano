<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Company;
use App\Models\Order;
use App\Models\ProposalBill;
use App\Models\BillOrder;
use App\Models\Service;
use App\Models\ServiceBillOrder;
use App\Models\Article;
use App\Models\Batch;

class OrdersInfoges extends Controller
{
    //Crear orden
    function createOrder(Request $request){
        //Comprobamos si existe la propuesta
        if (!$request->has('id_proposal')){
            $response['code'] = 1001;
            return response()->json($response);
        }

        $id_proposal = $request->get('id_proposal');

        if(empty($id_proposal)){
            $response['code'] = 1002;
            return response()->json($response);
        }

        //Consultamos la propuesta
        $proposal = Proposal::find($id_proposal);
        if(!$proposal){
            $response['code'] = 1003;
            return response()->json($response);
        }

        //Consultamos la empresa a la que pertenece la propuesta
        $company = Company::select('companies.*')->leftJoin('contacts', 'contacts.id_company', 'companies.id')->where('contacts.id', $proposal->id_contact)->first();

        if(empty($company->nif) || empty($company->address) || empty($company->id_hubspot) || $company->id_sage == null){
            $response['code'] = 1004;
            return response()->json($response);
        }

        //Creamos las orden
        $order = Order::create([
            'id_company' => $company->id,
            'id_proposal' => $proposal->id
        ]);

        //Consultamos las facturas de la propuesta
        $array_bills = ProposalBill::select('bills.*')->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill')->where('proposals_bills.id_proposal', $proposal->id)->get();

        //Creamos un array de factuas de ordenes para más tarde crearlas en sage
        $array_bills_orders = array();

        //Recorremos las facturas y creamos las facturas de la orden
        foreach($array_bills as $bill){
            //Consultamos los articulos de la facturar para ver si tienen IVA o no
            $iva = 0;
            $array_service_bill = Service::select('services.*')->leftJoin('services_bills', 'services.id', 'services_bills.id_service')->where('services_bills.id_bill', $bill->id)->with('article')->get();
            foreach($array_service_bill as $service){
                $article = $service['article'];
                if(!$article->is_exempt){
                    $iva += $service->pvp * 0.21;
                }
            }

            $bill_order = BillOrder::create([
                'number' => $bill->id_bill_internal,
                'date' => $bill->date,
                'way_to_pay' => $bill->way_to_pay,
                'expiration' => $bill->expiration,
                'amount' => $bill->amount,
                'iva' => round($iva, 2),
                'id_sage' => '',
                'id_order' => $order->id
            ]);

            $array_bills_orders[] = $bill_order;
        }

        $response['id_order'] = $order->id;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Actualizar orden
    function updateOrder(Request $request){
        //Comprobamos si existe la propuesta
        if (!$request->has('id_order') || !$request->has('discount')){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //Consultamos si existe la orden
        $id_order = $request->get('id_order');
        $discount = $request->get('discount');

        $order = Order::find($id_order);
        if(!$order){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //Limpiamos la bd de los datos anteriores
        $array_bills = array();
        $array_orders_bills = BillOrder::where('id_order', $order->id)->get();
        foreach($array_orders_bills as $order_bill){
            $array_bills[] = $order_bill->id;
        }

        $array_services = array();
        $is_save = 0;
        foreach($array_bills as $bill){
            $array_services_bills_orders = ServiceBillOrder::where('id_bill_order', $bill)->get();
            foreach($array_services_bills_orders as $service_bill_order){
                if(($order->is_custom && !$is_save) || (!$service_bill_order->is_custom)){
                    $array_services[] = $service_bill_order->id_service;
                }
                $service_bill_order->delete();
            }
            $is_save = 1;
            BillOrder::find($bill)->delete();
        }
        //Hay que duplicar los servicios para la ordenes
        foreach($array_services as $service){
            Service::find($service)->delete();
        }

        //Guardamos el objeto
        $bill_obj = $request->get('bill_obj');

        $array_services_aux = array();
        //Consultamos los artículos
        foreach($bill_obj['articles'] as $article){
            //Consultamo el id del artículo
            $article_custom = Article::where('id_sage', $article['id_sage_article'])->first();
            error_log($article['id_sage_article']);
            $service = Service::create([
                'pvp' => $article['amount'],
                'date' => $article['date'],
                'id_article' => $article_custom->id
            ]);
            $array_services_aux[] = $service;
        }

        $array_bills_aux = array();

        //Consultamos las facturas
        foreach($bill_obj['array_bills'] as $key => $bill_obj){
            $bill = BillOrder::create([
                'number' => $key + 1,
                'amount' => $bill_obj['amount'],
                'date' => $bill_obj['date'],
                'observations' => $bill_obj['observations'],
                'num_order' => $bill_obj['order_number'],
                'internal_observations' => $bill_obj['internal_observations'],
                'way_to_pay' => $bill_obj['select_way_to_pay'],
                'expiration' => $bill_obj['select_expiration'],
                'id_order' => $order->id
            ]);

            $array_bills_aux[] = $bill;
            $nun_custom_invoices = $request->get('nun_custom_invoices');
            $custom_bill = false;
            if(isset($nun_custom_invoices)){
                if($nun_custom_invoices > 0){
                    $custom_bill = true;
                }
            }
            if(!$custom_bill){
                //Creamos la relación entre las facturas y los artículos
                foreach($array_services_aux as $service){
                    //Consultamos el capitulo del servicio
                    $article = Article::find($service->id_article);
                    if(!$article){
                        $response['code'] = 1002;
                        return response()->json($response);
                    }

                    $batch = Batch::find($article->id_batch);
                    if(!$batch){
                        $response['code'] = 1003;
                        return response()->json($response);
                    }

                    if($service->date == $bill->date){
                        foreach($bill_obj['array_id_sage_articles'] as $id_sage_article){
                            if($id_sage_article == $article->id_sage){
                                $service_bill = ServiceBillOrder::where('id_service', $service->id)->where('id_bill_order', $bill->id)->first();
                                if(!$service_bill){
                                    ServiceBillOrder::create([
                                        'id_service' => $service->id,
                                        'id_bill_order' => $bill->id,
                                    ]);
                                }
                            }
                        }
                    }

                }
            }

            if($custom_bill){
                foreach($array_services_aux as $service){
                    ServiceBill::create([
                        'id_service' => $service->id,
                        'id_bill_order' => $bill->id,
                    ]);
                }
            }
        }
        
        $order->discount = $discount;
        $order->status = 0;
        $order->save();

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Actualizar orden old
    function updateOrderOld(Request $request){
        //Consultamos si existe la orden
        $id_order = $request->get('id_order');
        $order = Order::find($id_order);
        if(!$order){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //Guardamos el objeto
        $array_bills = $request->get('array_bills');

        $date_now = Date('Y-m-d');
        foreach($array_bills as $bill_obj){
            $array_date_custom_bill = explode("-", $bill_obj['date']);
            $date_custom_bill = $array_date_custom_bill[2].'-'.$array_date_custom_bill[1].'-'.$array_date_custom_bill[0];
            $bill_obj['will_update'] = true;
            if ($date_custom_bill < $date_now){
                $bill_obj['will_update'] = false;
            }
            if($bill_obj['will_update']){
                $bill_order = BillOrder::where('id_order', $order->id)->where('date', $bill_obj['date'])->first();
                if($bill_order){
                    
                    $bill_order->expiration = $bill_obj['select_expiration'];
                    $bill_order->way_to_pay = $bill_obj['select_way_to_pay'];
                    $bill_order->save();
                }
            }
        }

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Eliminar orden
    function deleteOrder($id){
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

            //Consultamos los servicios asociados a esta factura
            $array_services_bills_orders = ServiceBillOrder::where('id_bill_order', $bill_order->id)->get();
            $array_services = array();
            foreach($array_services_bills_orders as $service_bill_order){
                $array_services[] = $service_bill_order->id_service;
            }
            ServiceBillOrder::where('id_bill_order', $bill_order->id)->delete();
            
            //Eliminamos los servicios
            foreach($array_services as $service){
                Service::find($service)->delete();
            }
        }

        //Según las comprobaciones miramos si podemos eliminar la orden o no
        //No podemos eliminar la orden
        if(!$will_delete){
            $response['code'] = 1002;
            return response()->json($response);
        }

        //Podemos eliminar la orden
        if($will_delete){
            BillOrder::where('id_order', $order->id)->delete();

            //Cambiamos el estado a la orden y borramos el descuento
            $order = Order::find($order->id);
            $order->status = 3;
            $order->discount = '0.00';
            $order->save();
        }

        $response['code'] = 1000;
        return response()->json($response);
    }
}