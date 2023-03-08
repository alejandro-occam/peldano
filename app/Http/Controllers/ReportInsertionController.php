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
        //Recogemos los filtros
        $select_department = $request->get('select_department');
        $select_section = $request->get('select_section');
        $select_channel = $request->get('select_channel');
        $select_project = $request->get('select_project');
        $select_chapter = $request->get('select_chapter');
        $select_batch = $request->get('select_batch');
        $select_article = $request->get('select_article');
        $select_sort_by = $request->get('select_sort_by');
        $select_consultant = $request->get('select_consultant');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');


        //Incializamos el contador
        DB::statement(DB::raw('SET @counter := 0'));

        //Consultamos los servicios
        $array_services_bills_orders_custom = ServiceBillOrder::select(DB::raw('(@counter := @counter+1) as `index`'), 'articles.*', 'companies.name as client_name', 'orders.advertiser as advertise', 
                                                            'orders.id as id_order', 'orders.type_proposal', 'proposals.is_custom as type_order', 'services.pvp as amount', 
                                                            'proposals.id_user as consultant', 'services_bills_orders.id as id_services_bills_orders', 'bills_orders.date as bill_order_date')
                                                            ->leftJoin('services', 'services.id', 'services_bills_orders.id_service')
                                                            ->leftJoin('articles', 'articles.id', 'services.id_article')
                                                            ->leftJoin('bills_orders', 'bills_orders.id', 'services_bills_orders.id_bill_order')
                                                            ->leftJoin('orders', 'orders.id', 'bills_orders.id_order')
                                                            ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')
                                                            ->leftJoin('contacts', 'contacts.id', 'proposals.id_contact')
                                                            ->leftJoin('companies', 'companies.id', 'contacts.id_company')
                                                            ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                                                            ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                                                            ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                                                            ->leftJoin('channels', 'channels.id', 'projects.id_channel')
                                                            ->leftJoin('sections', 'sections.id', 'channels.id_section')
                                                            ->leftJoin('departments', 'departments.id', 'sections.id_department');
                                                           
        //Filtro departamente
        if($select_department != '0'){
            $array_services_bills_orders_custom = $array_services_bills_orders_custom->where('proposals.id_department', $select_department);
        }

        //Filtro sección
        if($select_section != '0'){
            $array_services_bills_orders_custom = $array_services_bills_orders_custom->where('sections.id', $select_section);
        }

        //Filtro canal
        if($select_channel != '0'){
            $array_services_bills_orders_custom = $array_services_bills_orders_custom->where('channels.id', $select_channel);
        }

        //Filtro proyecto
        if($select_project != '0'){
            $array_services_bills_orders_custom = $array_services_bills_orders_custom->where('projects.id', $select_project);
        }

        //Filtro capítulo
        if($select_chapter != '0'){
            $array_services_bills_orders_custom = $array_services_bills_orders_custom->where('chapters.id', $select_chapter);
        }

        //Filtro lote
        if($select_batch != '0'){
            $array_services_bills_orders_custom = $array_services_bills_orders_custom->where('batchs.id', $select_batch);
        }

        //Filtro artículo
        if($select_article != '0'){
            $array_services_bills_orders_custom = $array_services_bills_orders_custom->where('articles.id', $select_article);
        }

        //Filtro consultor
        if($select_consultant != '0'){
            $array_services_bills_orders_custom = $array_services_bills_orders_custom->where('proposals.id_user', $select_consultant);
        }
        
        //Filtro orden
        if($select_sort_by != 2){
            if($select_sort_by == 1){
                $array_services_bills_orders_custom = $array_services_bills_orders_custom->orderBy('articles.name');
            }
            if($select_sort_by == 3){
                $array_services_bills_orders_custom = $array_services_bills_orders_custom->orderBy('bills_orders.date');
            }
            if($select_sort_by == 4){
                $array_services_bills_orders_custom = $array_services_bills_orders_custom->orderBy('companies.name');
            }
            if($select_sort_by == 5){
                $array_services_bills_orders_custom = $array_services_bills_orders_custom->orderBy('orders.type_proposal');
            }
        }

        $array_services_bills_orders_custom = $array_services_bills_orders_custom->groupBy('services_bills_orders.id')->get();

        if($date_from != '' || $date_to != ''){
            foreach($array_services_bills_orders_custom as $index => $bill_order){
                $is_delete = false;
                //Filtro fecha desde
                if($date_from != ''){
                    $array_date_order1 = explode("-", $bill_order['bill_order_date']);
                    $date_order1 = $array_date_order1[2].'-'.$array_date_order1[1].'-'.$array_date_order1[0];
                    $array_date_front1 = explode("-", $date_from);
                    $date_custom1 = $array_date_front1[2].'-'.$array_date_front1[1].'-'.$array_date_front1[0];

                    if($date_order1 < $date_custom1){
                        unset($array_services_bills_orders_custom[$index]);
                        $is_delete = true;
                    }
                }

                //Filtro fecha hasta
                if($date_to != '' && !$is_delete){
                    $array_date_order2 = explode("-", $bill_order['bill_order_date']);
                    $date_order2 = $array_date_order2[2].'-'.$array_date_order2[1].'-'.$array_date_order2[0];
                    $array_date_front2= explode("-", $date_to);
                    $date_custom2 = $array_date_front2[2].'-'.$array_date_front2[1].'-'.$array_date_front2[0];

                    if($date_custom2 < $date_order2){
                        //error_log('date_custom: '.$date_custom2);
                        //error_log('date_order: '.$date_order2);
                        //error_log('eliminado2');
                        unset($array_services_bills_orders_custom[$index]);
                    }
                }
            }
        }

        $array_services_bills_orders = array();
        $cont = 1;
        $total = 0;
        foreach($array_services_bills_orders_custom as $bill){
            $bill['index'] = $cont;
            $cont++;
            $array_services_bills_orders[] = $bill;
            $total += $bill['amount'];
        }

        $response['array_articles'] = $array_services_bills_orders;
        $response['total_amount'] = $total;
        return response()->json($response);
    }
}
