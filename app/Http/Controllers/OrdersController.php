<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Proposal;
use App\Models\Contact;
use App\Models\ProposalBill;
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

        //Barra de busqueda
        $search = '';
        if (isset($query['search_users'])) {
            $search = $query['search_users'];
        }

        $array_orders = Order::select('proposals.*', 'sectors.name as sector_name')
                        ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')  
                        ->leftJoin('sectors', 'sectors.id', 'proposals.id_sector')
                        ->leftJoin('proposals_bills', 'proposals.id', 'proposals_bills.id_proposal')
                        ->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill');

        if($request->get('type') == 1){
            if($request->get('num_order') != ''){
                $array_orders = $array_orders->where('proposals.id_proposal_custom', $request->get('num_order'));
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
                                            ->skip($start)
                                            ->take($skip)
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

        $total_orders = Order::count();

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
        $array_orders = Order::select('proposals.*', 'sectors.name as sector_name')
                        ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')  
                        ->leftJoin('sectors', 'sectors.id', 'proposals.id_sector')
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
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#sector_name" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.strtoupper($order->sector_name).'</span>
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
            array('Consult.', 'Propuesta', 'Código', 'Tipo', 'Nombre del cliente', 'Fecha', 'Edición', 'Estado', 'Ctrl', 'Total', 'Dto', 'Sector', 'Nuevo recuperado')
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
        $sheet->setCellValue('L1', 'Sector');
        $sheet->setCellValue('M1', 'Nuevo recuperado');

        //Consultamos los usuarios
        $array_orders = Order::select('proposals.*', 'sectors.name as sector_name')
                        ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')  
                        ->leftJoin('sectors', 'sectors.id', 'proposals.id_sector')
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
}
