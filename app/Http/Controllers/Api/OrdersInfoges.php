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
        if(count($array_bills_orders) == 0){
            $response['code'] = 1002;
            return response()->json($response);
        }
        $date_now = Date('Y-m-d');
        $will_delete = true;

        foreach($array_bills_orders as $bill_order){
            $array_date_custom_bill = explode("-", $bill_order->date);
            $date_custom_bill = $array_date_custom_bill[2].'-'.$array_date_custom_bill[1].'-'.$array_date_custom_bill[0];
            if ($date_custom_bill < $date_now){
                $will_delete = false;
            }
        }

        //Según las comprobaciones miramos si podemos eliminar la orden o no
        //No podemos eliminar la orden
        if(!$will_delete){
            $response['code'] = 1003;
            return response()->json($response);
        }

        //Podemos eliminar la orden
        if($will_delete){
            BillOrder::where('id_order', $order->id)->delete();

            //Cambiamos el estado a la orden
            //Order::where('id', $order->id)->delete();
            $order = Order::find($order->id);
            $order->status = 3;
            $order->save();
        }

        $response['code'] = 1000;
        return response()->json($response);
    }
}