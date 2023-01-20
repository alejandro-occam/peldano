<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Proposal;
use App\Models\Contact;
use App\Models\ProposalBill;
use App\Models\BillOrder;
use App\Models\Department;
use App\Models\Bill;
use App\Models\Service;
use App\Models\Batch;
use App\Models\Chapter;
use App\Models\Article;
use App\Models\User;
use DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OrdersController extends Controller
{
    //Listar ordenes
    function listOrders(Request $request){
        //Elementos para la paginación 
        $pagination = $request->get('pagination');
        $query = $request->get('query');
        $start = 0;
        $skip = $pagination['perpage'];
        if ($pagination['page'] != 1) {
            $start = ($pagination['page'] - 1) * $pagination['perpage'];
            //Consultamos si hay tantos registros como para empezar en el numero de $start
            $num_proposals = Order::count();
            if ($start >= $num_proposals) {
                $skip = $skip - 1;
                $start = $start - 10;
                if ($start < 0) {
                    $start = 0;
                }
            }
        }

        $array_orders = Order::select('orders.id as id_order', 'proposals.*', 'departments.name as department_name')
                        ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')  
                        ->leftJoin('departments', 'departments.id', 'proposals.id_department')
                        ->leftJoin('proposals_bills', 'proposals.id', 'proposals_bills.id_proposal')
                        ->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill');

        if($request->get('type') == 1){
            if($request->get('num_order') != ''){
                $array_orders = $array_orders->where('proposals.id_proposal_custom', $request->get('num_order'));
            }

            if($request->get('select_consultant') != ''){
                $array_orders = $array_orders->where('proposals.id_user', $request->get('select_consultant'));
            }

            if($request->get('select_department') != ''){
                $array_orders = $array_orders->where('proposals.id_department', $request->get('select_department'));
            }

            if($request->get('date_from') != '' && $request->get('date_from') != 'Invalid Date-undefined-undefined'){
                $array_orders = $array_orders->where('proposals.date_proyect', '>=', $request->get('date_from'));
            }

            if($request->get('date_to') != '' && $request->get('date_to') != 'Invalid Date-undefined-undefined'){
                $array_orders = $array_orders->where('proposals.date_proyect', '<=', $request->get('date_to'));
            }
        }

        $array_orders = $array_orders->groupBy('orders.id')
                                            ->skip($start)
                                            ->take($skip)
                                            ->get();

                                        error_log($array_orders);

        $total_orders = $array_orders->groupBy('orders.id')
                                        ->count();

        foreach($array_orders as $order){
            //Consultamos el nombre del contacto
            $contact = Contact::find($order->id_contact);
            $order['name_contact'] = $contact->name.' '.$contact->surnames;

            //Consultamos el numero de la propuesta
            $id_proposal_custom_aux = sprintf('%08d', $order->id_proposal_custom);
            $date_aux = explode("-", $order->date_proyect);
            $order['proposal_custom'] = 'EP'.$date_aux[2].$date_aux[1].'-'.$id_proposal_custom_aux;

            //Consultamos el total 
            $total = 0;
            $proposal_bill = ProposalBill::select('bills.amount')->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill')->where('proposals_bills.id_proposal', $order->id)->get();
            foreach($proposal_bill as $bill){
                $total += $bill->amount;
            }
           
            $order['total_amount'] = number_format($total, 2);
        }

        //Devolución de la llamada con la paginación
        $meta['page'] = $pagination['page'];

        if ($total_orders < 1) {
            $meta['page'] = 1;
        }

        $meta['pages'] = 1;
        if (isset($pagination['pages'])) {
            $meta['pages'] = $pagination['pages'];
        }
        $meta['perpage'] = $pagination['perpage'];
        $meta['total'] = $total_orders;
        $meta['sort'] = 'asc';
        $meta['field'] = 'id';
        $response['meta'] = $meta;
        $response['data'] = $array_orders;
        return response()->json($response);
    }

    //Listar ordenes para exportar
    function listOrdersToExport(Request $request){
        $array_orders = Order::select('proposals.*', 'departments.name as department_name')
                        ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')  
                        ->leftJoin('departments', 'departments.id', 'proposals.id_department')
                        ->leftJoin('proposals_bills', 'proposals.id', 'proposals_bills.id_proposal')
                        ->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill');

        if($request->get('type') == 1){
            if($request->get('num_proposal') != ''){
                $array_orders = $array_orders->where('proposals.id_proposal_custom', $request->get('num_proposal'));
            }

            if($request->get('select_consultant') != ''){
                $array_orders = $array_orders->where('proposals.id_user', $request->get('select_consultant'));
            }

            if($request->get('select_department') != ''){
                $array_orders = $array_orders->where('proposals.id_department', $request->get('select_department'));
            }

            if($request->get('date_from') != ''){
                $array_orders = $array_orders->where('proposals.date_proyect', '>=', $request->get('date_from'));
            }

            if($request->get('date_to') != ''){
                $array_orders = $array_orders->where('proposals.date_proyect', '<=', $request->get('date_to'));
            }
        }

        $array_orders = $array_orders->groupBy('proposals.id')
                                            ->get();

        foreach($array_orders as $proposal){
            //Consultamos el nombre del contacto
            $contact = Contact::find($proposal->id_contact);
            $proposal['name_contact'] = $contact->name.' '.$contact->surnames;

            //Consultamos el numero de la propuesta
            $id_proposal_custom_aux = sprintf('%08d', $proposal->id_proposal_custom);
            $date_aux = explode("-", $proposal->date_proyect);
            $proposal['proposal_custom'] = 'EP'.$date_aux[2].$date_aux[1].'-'.$id_proposal_custom_aux;

            //Consultamos el total 
            $total = 0;
            $proposal_bill = ProposalBill::select('bills.amount')->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill')->where('proposals_bills.id_proposal', $proposal->id)->get();
            foreach($proposal_bill as $bill){
                $total += $bill->amount;
            }
           
            $proposal['total_amount'] = $total;
        }
        
        $html = '';
        foreach($array_orders as $order){
            $html .= '<tr data-row="0" class="datatable-row" style="left: 0px;">
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#id_user" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-dark">'.$order->id_user.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#proposal_custom" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-dark">'.$order['proposal_custom'].'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#code" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-dark">010414</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#type" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">NP</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#name_contact" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.$order['name_contact'].'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#date" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.$order->date_proyect.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#editio" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">--</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#status" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">CERRADA</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#ctrl" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">NO</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#total" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.$order['total_amount'].'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#dto" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.$order->discount.'%</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#department_name" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.strtoupper($order->department_name).'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#new_recovered" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">SÍ</span>
                            </span>
                        </td>
                    </tr>';
        }

        $response['code'] = 1000;
        $response['array_orders'] = $html;
        return response()->json($response);
    }

    //Descargar tabla órdenes csv
    function downloadListOrdersCsv(Request $request){    
        //Creamos las columnas del fichero
        $array_custom_calendars = array (
            array('Consult.', 'Propuesta', 'Código', 'Tipo', 'Nombre del cliente', 'Fecha', 'Edición', 'Estado', 'Ctrl', 'Total', 'Dto', 'Departamento', 'Nuevo recuperado')
        );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //Creamos las cabeceras
        $sheet->setCellValue('A1', 'Consult.');
        $sheet->setCellValue('B1', 'Propuesta');
        $sheet->setCellValue('C1', 'Código');
        $sheet->setCellValue('D1', 'Tipo');
        $sheet->setCellValue('E1', 'Nombre del cliente');
        $sheet->setCellValue('F1', 'Fecha');
        $sheet->setCellValue('G1', 'Edición');
        $sheet->setCellValue('H1', 'Estado');
        $sheet->setCellValue('I1', 'Ctrl');
        $sheet->setCellValue('J1', 'Total');
        $sheet->setCellValue('K1', 'Dto');
        $sheet->setCellValue('L1', 'Departamento');
        $sheet->setCellValue('M1', 'Nuevo recuperado');

        //Consultamos las ordenes
        $array_orders = Order::select('proposals.*', 'departments.name as department_name')
                        ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')  
                        ->leftJoin('departments', 'departments.id', 'proposals.id_department')
                        ->leftJoin('proposals_bills', 'proposals.id', 'proposals_bills.id_proposal')
                        ->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill');

        if($request->get('type') == 1){
            if($request->get('num_proposal') != ''){
                $array_orders = $array_orders->where('proposals.id_proposal_custom', $request->get('num_proposal'));
            }

            if($request->get('select_consultant') != ''){
                $array_orders = $array_orders->where('proposals.id_user', $request->get('select_consultant'));
            }

            if($request->get('select_sector') != ''){
                $array_orders = $array_orders->where('proposals.id_sector', $request->get('select_sector'));
            }

            if($request->get('date_from') != ''){
                $array_orders = $array_orders->where('proposals.date_proyect', '>=', $request->get('date_from'));
            }

            if($request->get('date_to') != ''){
                $array_orders = $array_orders->where('proposals.date_proyect', '<=', $request->get('date_to'));
            }
        }

        $array_orders = $array_orders->groupBy('proposals.id')
                                            ->get();
        

        foreach($array_orders as $order){
            //Consultamos el nombre del contacto
            $contact = Contact::find($order->id_contact);
            $order['name_contact'] = $contact->name.' '.$contact->surnames;

            //Consultamos el numero de la propuesta
            $id_proposal_custom_aux = sprintf('%08d', $order->id_proposal_custom);
            $date_aux = explode("-", $order->date_proyect);
            $order['proposal_custom'] = 'EP'.$date_aux[2].$date_aux[1].'-'.$id_proposal_custom_aux;

            //Consultamos el total 
            $total = 0;
            $proposal_bill = ProposalBill::select('bills.amount')->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill')->where('proposals_bills.id_proposal', $order->id)->get();
            foreach($proposal_bill as $bill){
                $total += $bill->amount;
            }
            
            $order['total_amount'] = $total;
        }

        foreach($array_orders as $key => $order){
            $sheet->setCellValue('A'.($key+2), $order->id_user);
            $sheet->setCellValue('B'.($key+2), $order['proposal_custom']);
            $sheet->setCellValue('C'.($key+2), '010414');
            $sheet->setCellValue('D'.($key+2), 'NP');
            $sheet->setCellValue('E'.($key+2), $order['name_contact']);
            $sheet->setCellValue('F'.($key+2), $order->date_proyect);
            $sheet->setCellValue('G'.($key+2), '--');
            $sheet->setCellValue('H'.($key+2), 'CERRADA');
            $sheet->setCellValue('I'.($key+2), 'NO');
            $sheet->setCellValue('J'.($key+2), $order['total_amount']);
            $sheet->setCellValue('K'.($key+2), $order->discount);
            $sheet->setCellValue('L'.($key+2), strtoupper($order->sector_name));
            $sheet->setCellValue('M'.($key+2), 'SÍ');
        }

        $writer = new Xlsx($spreadsheet);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.'ordenes.xlsx');
        $writer->save('php://output');
    }

    //Mostrar información de la orden
    function getInfoOrder($id){
        //Consultamos si existe la orden
        $order = Order::find($id);
        if(!$order){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //Consultamos si existe la propuesta
        $proposal = Proposal::select('proposals.*', 'contacts.name as contact_name', 'contacts.surnames as contact_surnames', 'contacts.email as contact_email', 'contacts.phone as contact_phone', 'contacts.id_company')
                                ->leftJoin('contacts', 'contacts.id', 'proposals.id_contact')
                                ->where('proposals.id', $order->id_proposal)
                                ->with('department')
                                ->first();

        $proposal['id_proposal_custom_aux'] = sprintf('%08d', $proposal->id_proposal_custom);
        $proposal['department_obj'] = Department::find($proposal->id_department);

        if(!$proposal){
            $response['code'] = 1002;
            return response()->json($response);
        }

        //Consultamos el array de facturas
        $proposal_bills = Bill::select('bills.*')
                            ->leftJoin('proposals_bills', 'proposals_bills.id_bill', 'bills.id')
                            ->where('proposals_bills.id_proposal', '=', $proposal->id)
                            ->get();

        if(count($proposal_bills) <= 0){
            $response['code'] = 1003;
            return response()->json($response);
        }

        //Consultamos la facturas de la orden

        //Modificamos las formas de pago y los vencimientos de la factura de la propuesta por los de la orden. Comprobamos también si se pueden modificar
        $date_now = Date('Y-m-d');
        foreach($proposal_bills as $bill){
            $bill_order = BillOrder::where('id_order', $order->id)->where('amount', $bill->amount)->where('number', $bill->id_bill_internal)->first();
            if($bill_order){
                $bill->way_to_pay = $bill_order->way_to_pay;
                $bill->expiration = $bill_order->expiration;
                $bill->date = $bill_order->date;

                $array_date_custom_bill = explode("-", $bill_order->date);
                $date_custom_bill = $array_date_custom_bill[2].'-'.$array_date_custom_bill[1].'-'.$array_date_custom_bill[0];
                $bill['will_update'] = true;
                if ($date_custom_bill < $date_now){
                    $bill['will_update'] = false;
                }
            }
        }

        //Consultamos el array de servicios
        $array_services = array();
        $is_read = 0;
        foreach($proposal_bills as $bill){
            if($proposal->is_custom){
                if(!$is_read){
                    $array_articles = [];
                    $array_services_obj = Service::select('services.*')
                                            ->leftJoin('services_bills', 'services.id', 'services_bills.id_service')
                                            ->where('services_bills.id_bill', $bill->id)
                                            ->with('article')
                                            ->get();
                    foreach($array_services_obj as $service){
                        //Consultamos el capitulo
                        $batch = Batch::find($service->article->id_batch);
                        if(!$batch){
                            $response['code'] = 1003;
                            return response()->json($response);
                        }
                        
                        $chapter = Chapter::find($batch->id_chapter);
                        if(!$chapter){
                            $response['code'] = 1004;
                            return response()->json($response);
                        }

                        $service['chapter'] = $chapter;
                        $array_services[] = $service;
                        $article = Article::find($service->id_article);
                        $array_articles[] = $article;
                    }
                    $bill['array_articles'] = $array_articles;
                    $is_read = 1;
                }
            }else{
                $array_articles = [];
                $array_services_obj = Service::select('services.*')
                                        ->leftJoin('services_bills', 'services.id', 'services_bills.id_service')
                                        ->where('services_bills.id_bill', $bill->id)
                                        ->with('article')
                                        ->get();
                foreach($array_services_obj as $service){
                    //Consultamos el capitulo
                    $batch = Batch::find($service->article->id_batch);
                    if(!$batch){
                        $response['code'] = 1003;
                        return response()->json($response);
                    }
                    
                    $chapter = Chapter::find($batch->id_chapter);
                    if(!$chapter){
                        $response['code'] = 1004;
                        return response()->json($response);
                    }

                    $service['chapter'] = $chapter;
                    $array_services[] = $service;
                    $article = Article::find($service->id_article);
                    $array_articles[] = $article;
                }
                $bill['array_articles'] = $array_articles;
            }
        }

        //Consultamos el usuario que ha creado la propuesta
        $user = User::find($proposal->id_user);

        //Consultamos la empresa
        $array_companies = Contact::select('contacts.*', 'companies.name', 'companies.nif', DB::raw('CONCAT(contacts.name, " ", contacts.surnames) as fullname', 'contacts.id as id_contact'), 'contacts.email')
                             ->leftJoin('companies', 'contacts.id_company', 'companies.id')
                             ->where('contacts.id', $proposal->id_contact)
                             ->get();

        //Adjuntamos el id_order al objeto proposal
        $proposal['id_order'] = $order->id;
        
        $response['company_aux'] = $array_companies;
        $response['consultant'] = $user;
        $response['array_services'] = $array_services;
        $response['proposal'] = $proposal;
        $response['proposal_bills'] = $proposal_bills;
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
        $bill_obj = $request->get('bill_obj');
        foreach(json_decode($bill_obj)->array_bills as $key => $bill_obj){
            if($bill_obj->will_update){
                $bill_order = BillOrder::where('id_order', $order->id)->where('amount', $bill_obj->amount)->where('date', $bill_obj->date)->first();
                if($bill_order){
                    $bill_order->expiration = $bill_obj->select_expiration;
                    $bill_order->way_to_pay = $bill_obj->select_way_to_pay;
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
            Order::where('id', $order->id)->delete();
        }

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Copiar orden
    function copyOrder($id){
        //Consultamos si existe la orden
        $order_old = Order::find($id);
        if(!$order_old){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //Creamos la orden
        $order = Order::create([
            'id_company' => $order_old->id_company,
            'id_proposal' => $order_old->id_proposal
        ]);

        //Consultamos las facturas de la propuesta
        $array_bills = ProposalBill::select('bills.*')->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill')->where('proposals_bills.id_proposal', $order->id_proposal)->get();

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

            //Consultamos las facturas de la orden
            $bill_order = BillOrder::where('id_order', $order_old->id)->where('date', $bill->date)->first();
            $way_to_pay = $bill->way_to_pay;
            $expiration = $bill->expiration;
            if($bill_order){
                $way_to_pay = $bill_order->way_to_pay;
                $expiration = $bill_order->expiration;
            }

            $bill_order = BillOrder::create([
                'number' => $bill_order->number,
                'date' => $bill_order->date,
                'way_to_pay' => $way_to_pay,
                'expiration' => $expiration,
                'amount' => $bill_order->amount,
                'iva' => $bill_order->iva,
                'id_sage' => '',
                'id_order' => $order->id
            ]);
        }

        $response['code'] = 1000;
        return response()->json($response);
    }
}
