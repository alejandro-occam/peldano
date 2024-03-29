<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\BillOrder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportSalesOrdersSignedController extends Controller
{
    //Listado de facturas según el filtro
    function reportsList(Request $request){
        $select_department = $request->get('select_department');
        $select_section = $request->get('select_section');
        $select_channel = $request->get('select_channel');
        $select_project = $request->get('select_project');
        $select_chapter = $request->get('select_chapter');
        $select_consultant = $request->get('select_consultant');
        $select_order = $request->get('select_order');
        $select_compare = $request->get('select_compare');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');

        //Generamos array de fechas
        $array_dates = array();
        $num_months = $this->calculateMonthsNumber($date_from, $date_to);

        $date_from_array = explode("-", $date_from);
        $date_from_custom = $date_from_array[1].'-01-'.$date_from_array[2];
        $date_from_custom_old_time = strtotime($date_from);
        $date_from_custom_old = date('d-m-Y', strtotime("-1 year", $date_from_custom_old_time));
        //error_log('date_from_custom: '.$date_from_custom);
        //error_log('date_from_custom_old: '.$date_from_custom_old);

        $date_to_array = explode("-", $date_to);
        $date_to_custom = $date_to_array[1].'-01-'.$date_to_array[2];
        $date_to_custom_old_time = strtotime($date_to);
        $date_to_custom_old = date('d-m-Y', strtotime("-1 year", $date_to_custom_old_time));
        //error_log('date_to_custom: '.$date_to_custom);
        //error_log('date_to_custom_old: '.$date_to_custom_old);

        //Generamos los arrays de fechas
        $array_dates = $this->generateDateArray($num_months, $date_from_custom, 1);
        $array_dates_old = $this->generateDateArray($num_months, $date_from_custom_old, 2);

        //DIG
        $array_orders_dig = Order::select('orders.*', 'departments.nomenclature as department_nomenclature', 'departments.name as department_name', 'departments.id as id_department', 'proposals.id as id_proposal', 'channels.nomenclature as channel_nomenclature')
                                        ->leftJoin('bills_orders', 'bills_orders.id_order', 'orders.id')                                
                                        ->leftJoin('proposals', 'orders.id_proposal', 'proposals.id')
                                        ->leftJoin('contacts', 'proposals.id_contact', 'contacts.id')
                                        ->leftJoin('services_bills_orders', 'services_bills_orders.id_bill_order', 'bills_orders.id')
                                        ->leftJoin('services', 'services.id', 'services_bills_orders.id_service')
                                        ->leftJoin('articles', 'articles.id', 'services.id_article')
                                        ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                                        ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                                        ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                                        ->leftJoin('channels', 'channels.id', 'projects.id_channel')
                                        ->leftJoin('sections', 'sections.id', 'channels.id_section')
                                        ->leftJoin('departments', 'departments.id', 'sections.id_department');

        //PRINT
        $array_orders_print = Order::select('orders.*', 'departments.nomenclature as department_nomenclature', 'departments.name as department_name', 'departments.id as id_department', 'proposals.id as id_proposal', 'channels.nomenclature as channel_nomenclature')
                                        ->leftJoin('bills_orders', 'bills_orders.id_order', 'orders.id')                                
                                        ->leftJoin('proposals', 'orders.id_proposal', 'proposals.id')
                                        ->leftJoin('contacts', 'proposals.id_contact', 'contacts.id')
                                        ->leftJoin('services_bills_orders', 'services_bills_orders.id_bill_order', 'bills_orders.id')
                                        ->leftJoin('services', 'services.id', 'services_bills_orders.id_service')
                                        ->leftJoin('articles', 'articles.id', 'services.id_article')
                                        ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                                        ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                                        ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                                        ->leftJoin('channels', 'channels.id', 'projects.id_channel')
                                        ->leftJoin('sections', 'sections.id', 'channels.id_section')
                                        ->leftJoin('departments', 'departments.id', 'sections.id_department');
               
        //OTROS
        $array_orders_others = Order::select('orders.*', 'departments.nomenclature as department_nomenclature', 'departments.name as department_name', 'departments.id as id_department', 'proposals.id as id_proposal', 'channels.nomenclature as channel_nomenclature')
                                        ->leftJoin('bills_orders', 'bills_orders.id_order', 'orders.id')                                
                                        ->leftJoin('proposals', 'orders.id_proposal', 'proposals.id')
                                        ->leftJoin('contacts', 'proposals.id_contact', 'contacts.id')
                                        ->leftJoin('services_bills_orders', 'services_bills_orders.id_bill_order', 'bills_orders.id')
                                        ->leftJoin('services', 'services.id', 'services_bills_orders.id_service')
                                        ->leftJoin('articles', 'articles.id', 'services.id_article')
                                        ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                                        ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                                        ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                                        ->leftJoin('channels', 'channels.id', 'projects.id_channel')
                                        ->leftJoin('sections', 'sections.id', 'channels.id_section')
                                        ->leftJoin('departments', 'departments.id', 'sections.id_department');
                                        
        //Filtro departamente
        if($select_department != '0'){
            $array_orders_dig = $array_orders_dig->where('departments.id_department', $select_department);
            $array_orders_print = $array_orders_print->where('departments.id_department', $select_department);
            $array_orders_others = $array_orders_others->where('departments.id_department', $select_department);
        }

        //Filtro sección
        if($select_section != '0'){
            $array_orders_dig = $array_orders_dig->where('sections.id', $select_section);
            $array_orders_print = $array_orders_print->where('sections.id', $select_section);
            $array_orders_others = $array_orders_others->where('sections.id', $select_section);
        }

        //Filtro canal
        if($select_channel != '0'){
            $array_orders_dig = $array_orders_dig->where('channels.id', $select_channel);
            $array_orders_print = $array_orders_print->where('channels.id', $select_channel);
            $array_orders_others = $array_orders_others->where('channels.id', $select_channel);
        }

        //Filtro proyecto
        if($select_project != '0'){
            $array_orders_dig = $array_orders_dig->where('projects.id', $select_project);
            $array_orders_print = $array_orders_print->where('projects.id', $select_project);
            $array_orders_others = $array_orders_others->where('projects.id', $select_project);
        }

        //Filtro chapter
        if($select_chapter != '0'){
            $array_orders_dig = $array_orders_dig->where('chapters.id', $select_chapter);
            $array_orders_print = $array_orders_print->where('chapters.id', $select_chapter);
            $array_orders_others = $array_orders_others->where('chapters.id', $select_chapter);
        }

        //Filtro consultor
        if($select_consultant != '0'){
            $array_orders_dig = $array_orders_dig->where('contacts.id', $select_consultant);
            $array_orders_print = $array_orders_print->where('contacts.id', $select_consultant);
            $array_orders_others = $array_orders_others->where('contacts.id', $select_consultant);
        }

        //Filtro firmada o editando
        if($select_order == '1'){
            $array_orders_dig = $array_orders_dig->where('bills_orders.id_sage', '<>', null)->where('bills_orders.receipt_order_sage', '<>', null);
            $array_orders_print = $array_orders_print->where('bills_orders.id_sage', '<>', null)->where('bills_orders.receipt_order_sage', '<>', null);
            $array_orders_others = $array_orders_others->where('bills_orders.id_sage', '<>', null)->where('bills_orders.receipt_order_sage', '<>', null);
        }

        if($select_order == '2'){
            $array_orders_dig = $array_orders_dig->where('bills_orders.id_sage', null)->where('bills_orders.receipt_order_sage', null);
            $array_orders_print = $array_orders_print->where('bills_orders.id_sage', null)->where('bills_orders.receipt_order_sage', null);
            $array_orders_others = $array_orders_others->where('bills_orders.id_sage', null)->where('bills_orders.receipt_order_sage', null);
        }

        //Facturas DIG
        $array_orders_dig = $array_orders_dig->where('channels.nomenclature', 'DIG')
                                                //->groupBy('orders.id')
                                                ->get();
        error_log('array_orders_dig: '.$array_orders_dig);
        //error_log(count($array_orders_dig));

        //Facturas PRINT
        $array_orders_print = $array_orders_print->where('channels.nomenclature', 'PRINT')
                                                //->groupBy('orders.id', 'channels.nomenclature')
                                                ->get();
        //error_log(count($array_orders_print));
        //error_log('array_orders_print: '.$array_orders_print);

        //Facturas OTROS
        $array_orders_others = $array_orders_others->whereNotIn('channels.nomenclature', ['DIG', 'PRINT'])
                                                //->groupBy('orders.id', 'channels.nomenclature')
                                                ->get();
        //error_log(count($array_orders_others));
        //error_log('array_orders_others: '.$array_orders_others);

        //Creamos el objeto customizado
        $array_bills_orders_custom = array();
        
        //Recorremos el objeto DIG
        $array_aux_id_order_dig = array();
        foreach($array_orders_dig as $key => $order_dig){
            $custom_date_array_aux = explode(" ", $order_dig->created_at);
            $custom_date_array = explode("-", $custom_date_array_aux[0]);
            $custom_date = $custom_date_array[2].'-'.$custom_date_array[1].'-'.$custom_date_array[0];
            $custom_obj = null;
            $custom_obj_new = null;
            $custom_obj_old = null;
            $exist = false;
            if(count($array_aux_id_order_dig) > 0){
                foreach($array_aux_id_order_dig as $id_order){
                    if($id_order == $order_dig->id){
                        $exist = true;
                        error_log('entro');
                    }
                }
            }
            if(!$exist){
                $array_aux_id_order_dig[] = $order_dig->id;
                //Consultamos el amount de la orden
                $amount = BillOrder::where('id_order', $order_dig->id)->sum('amount');

                if($key == 0){
                    $custom_obj['dep'] = $order_dig->department_nomenclature;
                    $custom_obj['dep_name'] = $order_dig->department_name;
                    $custom_obj['id_dep'] = $order_dig->id_department;
                    $custom_obj['type'] = $order_dig->channel_nomenclature;
                    $custom_obj['id_type'] = $order_dig->id_channel;

                    $custom_obj_old['period'] = $date_from_custom_old.' a '.$date_to_custom_old;
                    foreach($array_dates_old as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                        //if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            $custom_obj_old['amounts'][] = round($amount, 2);
                        }else{
                            $custom_obj_old['amounts'][] = 0;
                        }
                    }
                    $custom_obj_new['period'] = $date_from.' a '.$date_to;
                    foreach($array_dates as $key_date => $date){

                        if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                        //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                            $custom_obj_new['amounts'][] = round($amount, 2);
                        }else{
                            $custom_obj_new['amounts'][] = 0;
                        }
                    }

                    $custom_obj_diference['period'] = 'Diferencia%';
                    $custom_obj['old'] = $custom_obj_old;
                    $custom_obj['new'] = $custom_obj_new;
                    $custom_obj['diference'] = $custom_obj_diference;
                    $array_bills_orders_custom[] = $custom_obj;

                }else{
                    $exist = false;
                    $position = 0;
                    foreach($array_bills_orders_custom as $key_array_bills_orders_custom => $bill_order_custom){
                        if($bill_order_custom['dep'] == $order_dig->department_nomenclature && $bill_order_custom['type'] == $order_dig->channel_nomenclature){
                            $exist = true;
                            $position = $key_array_bills_orders_custom;
                        }
                    }

                    if(!$exist){
                        $custom_obj['dep'] = $order_dig->department_nomenclature;
                        $custom_obj['dep_name'] = $order_dig->department_name;
                        $custom_obj['id_dep'] = $order_dig->id_department;
                        $custom_obj['type'] = $order_dig->channel_nomenclature;
                        $custom_obj['id_type'] = $order_dig->id_channel;

                        $custom_obj_old['period'] = $date_from_custom_old.' a '.$date_to_custom_old;
                        foreach($array_dates_old as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                                $custom_obj_old['amounts'][] = round($amount, 2);
                            }else{
                                $custom_obj_old['amounts'][] = 0;
                            }
                            error_log(count($custom_obj_old['amounts']));
                        }

                        $custom_obj_new['period'] = $date_from.' a '.$date_to;
                        foreach($array_dates as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                                $custom_obj_new['amounts'][] = round($amount, 2);
                            }else{
                                $custom_obj_new['amounts'][] = 0;
                            }
                        }
                        $custom_obj_diference['period'] = 'Diferencia%';
                        $custom_obj['old'] = $custom_obj_old;
                        $custom_obj['new'] = $custom_obj_new;
                        $custom_obj['diference'] = $custom_obj_diference;
                        $array_bills_orders_custom[] = $custom_obj;
                    }

                    if($exist){
                        //Aquí sumariamos precios de las ordenes
                        foreach($array_dates_old as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                                $array_bills_orders_custom[$position]['old']['amounts'][$key_date] += round($amount, 2);
                            }
                        }
                        foreach($array_dates as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if($date['last_date_custom'] >= $custom_date && $date['first_date_custom'] <= $custom_date){
                                $array_bills_orders_custom[$position]['new']['amounts'][$key_date] += round($amount, 2);
                            }
                        }
                    }
                }
            }
            $exist = false;
        }

        error_log(print_r($array_bills_orders_custom, true));
        
        //Recorremos el objeto PRINT
        $array_aux_id_order_print = array();
        foreach($array_orders_print as $key => $order_print){
            $exist = false;
            
            $custom_date_array_aux = explode(" ", $order_print->created_at);
            $custom_date_array = explode("-", $custom_date_array_aux[0]);
            $custom_date = $custom_date_array[2].'-'.$custom_date_array[1].'-'.$custom_date_array[0];
            $custom_obj = null;
            $custom_obj_new = null;
            $custom_obj_old = null;
            $custom_obj_diference = null;
            if(count($array_aux_id_order_print) > 0){
                foreach($array_aux_id_order_print as $id_order){
                    if($id_order == $order_print->id){
                        $exist = true;
                    }
                }
            }
            if(!$exist){
                $array_aux_id_order_print[] = $order_print->id;

                //Consultamos el amount de la orden
                $amount = BillOrder::where('id_order', $order_print->id)->sum('amount');

                if($key == 0){
                    $custom_obj['dep'] = $order_print->department_nomenclature;
                    $custom_obj['dep_name'] = $order_print->department_name;
                    $custom_obj['id_dep'] = $order_print->id_department;
                    $custom_obj['type'] = $order_print->channel_nomenclature;
                    $custom_obj['id_type'] = $order_print->id_channel;

                    $custom_obj_old['period'] = $date_from_custom_old.' a '.$date_to_custom_old;
                    foreach($array_dates_old as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            $custom_obj_old['amounts'][] = round($amount, 2);
                        }else{
                            $custom_obj_old['amounts'][] = 0;
                        }
                    }
                    $custom_obj_new['period'] = $date_from.' a '.$date_to;
                    foreach($array_dates as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            $custom_obj_new['amounts'][] = round($amount, 2);
                        }else{
                            $custom_obj_new['amounts'][] = 0;
                        }
                    }

                    $custom_obj_diference['period'] = 'Diferencia%';
                    $custom_obj['old'] = $custom_obj_old;
                    $custom_obj['new'] = $custom_obj_new;
                    $custom_obj['diference'] = $custom_obj_diference;
                    $array_bills_orders_custom[] = $custom_obj;

                }else{
                    $exist = false;
                    $position = 0;
                    foreach($array_bills_orders_custom as $key_array_bills_orders_custom => $bill_order_custom){
                        if($bill_order_custom['dep'] == $order_print->department_nomenclature && $bill_order_custom['type'] == $order_print->channel_nomenclature){
                            $exist = true;
                            $position = $key_array_bills_orders_custom;
                        }
                    }

                    if(!$exist){
                        $custom_obj['dep'] = $order_print->department_nomenclature;
                        $custom_obj['dep_name'] = $order_print->department_name;
                        $custom_obj['id_dep'] = $order_print->id_department;
                        $custom_obj['type'] = $order_print->channel_nomenclature;
                        $custom_obj['id_type'] = $order_print->id_channel;
                        $custom_obj_old['period'] = $date_from_custom_old.' a '.$date_to_custom_old;

                        foreach($array_dates_old as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                                $custom_obj_old['amounts'][] = round($amount, 2);
                            }else{
                                $custom_obj_old['amounts'][] = 0;
                            }
                        }

                        $custom_obj_new['period'] = $date_from.' a '.$date_to;
                        foreach($array_dates as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                                $custom_obj_new['amounts'][] = round($amount, 2);
                            }else{
                                $custom_obj_new['amounts'][] = 0;
                            }
                        }
                        $custom_obj_diference['period'] = 'Diferencia%';
                        $custom_obj['old'] = $custom_obj_old;
                        $custom_obj['new'] = $custom_obj_new;
                        $custom_obj['diference'] = $custom_obj_diference;
                        $array_bills_orders_custom[] = $custom_obj;
                    }

                    if($exist){
                        //Aquí sumariamos precios de las ordenes
                        foreach($array_dates_old as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                                $array_bills_orders_custom[$position]['old']['amounts'][$key_date] += round($amount, 2);
                            }
                        }
                        foreach($array_dates as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if($date['last_date_custom'] >= $custom_date && $date['first_date_custom'] <= $custom_date){
                                $array_bills_orders_custom[$position]['new']['amounts'][$key_date] += round($amount, 2);
                            }
                        }
                    }
                }
            }
            $exist = false;
        }

        //Recorremos el objeto OTROS
        $array_aux_id_order_others = array();
        foreach($array_orders_others as $key => $order_others){
            $custom_obj = null;
            $custom_obj_new = null;
            $custom_obj_old = null;
            $custom_obj_diference = null;
            $custom_date_array_aux = explode(" ", $order_others->created_at);
            $custom_date_array = explode("-", $custom_date_array_aux[0]);
            $custom_date = $custom_date_array[2].'-'.$custom_date_array[1].'-'.$custom_date_array[0];
            $exist = false;
            if(count($array_aux_id_order_others) > 0){
                foreach($array_aux_id_order_others as $id_order){
                    if($id_order == $order_others->id){
                        $exist = true;
                    }
                }
            }
            if(!$exist){
                $array_aux_id_order_others[] = $order_others->id;
                //Consultamos el amount de la orden
                $amount = BillOrder::where('id_order', $order_others->id)->sum('amount');

                if($key == 0){
                    $custom_obj['dep'] = $order_others->department_nomenclature;
                    $custom_obj['dep_name'] = $order_others->department_name;
                    $custom_obj['id_dep'] = $order_others->id_department;
                    $custom_obj['type'] = 'OTROS';
                    $custom_obj['id_type'] = $order_others->id_channel;

                    $custom_obj_old['period'] = $date_from_custom_old.' a '.$date_to_custom_old;
                    foreach($array_dates_old as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                        //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                            $custom_obj_old['amounts'][] = round($amount, 2);
                        }else{
                            $custom_obj_old['amounts'][] = 0;
                        }
                    }
                    $custom_obj_new['period'] = $date_from.' a '.$date_to;
                    foreach($array_dates as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                        //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                            $custom_obj_new['amounts'][] = round($amount, 2);
                        }else{
                            $custom_obj_new['amounts'][] = 0;
                        }
                    }

                    $custom_obj_diference['period'] = 'Diferencia%';
                    $custom_obj['old'] = $custom_obj_old;
                    $custom_obj['new'] = $custom_obj_new;
                    $custom_obj['diference'] = $custom_obj_diference;
                    $array_bills_orders_custom[] = $custom_obj;

                }else{
                    foreach($array_bills_orders_custom as $key_array_bills_orders_custom => $bill_order_custom){
                        //Aquí sumariamos precios de las ordenes
                        foreach($array_dates_old as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                                $array_bills_orders_custom[$key_array_bills_orders_custom]['old']['amounts'][$key_date] += round($amount, 2);
                            }
                        }
                        foreach($array_dates as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if($date['last_date_custom'] >= $custom_date && $date['first_date_custom'] <= $custom_date){
                                $array_bills_orders_custom[$key_array_bills_orders_custom]['new']['amounts'][$key_date] += round($amount, 2);
                            }
                        }
                    }
                }
            }
            $exist = false;
        }

        //Guardamos el objeto diferencia
        if(count($array_bills_orders_custom) > 0){
            if(count($array_bills_orders_custom[0]['new']) > 0){
                $num_registers = count($array_bills_orders_custom[0]['new']['amounts']);
                foreach($array_bills_orders_custom as $key_array_bills_orders_custom => $bill_order_custom){
                    for($i=0; $i<$num_registers; $i++){
                        if($array_bills_orders_custom[$key_array_bills_orders_custom]['new']['amounts'][$i] == 0 || $array_bills_orders_custom[$key_array_bills_orders_custom]['old']['amounts'][$i] == 0){
                            $array_bills_orders_custom[$key_array_bills_orders_custom]['diference']['amounts'][] = '-';
                        }else{
                            $diference = round(($array_bills_orders_custom[$key_array_bills_orders_custom]['new']['amounts'][$i] * 100) / $array_bills_orders_custom[$key_array_bills_orders_custom]['old']['amounts'][$i], 2) - 100;
                            $array_bills_orders_custom[$key_array_bills_orders_custom]['diference']['amounts'][] = round($diference, 2);
                        }
                    }
                }
            }
        }

        //error_log(print_r($array_bills_orders_custom, true));

        //Calculamos los totales de las cantidades de $array_bills_orders_custom
        foreach($array_bills_orders_custom as $key_array_bills_orders_custom => $bill_order_custom){
            $array_amounts_new = $bill_order_custom['new']['amounts'];
            $total_new = 0;
            foreach($array_amounts_new as $amount_new){
                $total_new += round($amount_new, 2);
            }
            
            $array_bills_orders_custom[$key_array_bills_orders_custom]['new']['amounts'][] = round($total_new, 2);

            $array_amounts_old = $bill_order_custom['old']['amounts'];
            $total_old = 0;
            foreach($array_amounts_old as $amount_old){
                $total_old += round($amount_old, 2);
            }
            
            $array_bills_orders_custom[$key_array_bills_orders_custom]['old']['amounts'][] = round($total_old, 2);

            if($total_new == 0 || $total_old == 0){
                $array_bills_orders_custom[$key_array_bills_orders_custom]['diference']['amounts'][] = '-';
            }else{
                $diference = round(($total_new * 100) / $total_old, 2) - 100;
                $array_bills_orders_custom[$key_array_bills_orders_custom]['diference']['amounts'][] = round($diference, 2);
            }
        }

        //Ordenamos el array por departamento y calculamos sus totales
        $custom_array_dep_bills_orders_custom = array();
        $custom_array_type_bills_orders_custom = array();
        $array_bills_orders_custom_aux = array();
        foreach($array_bills_orders_custom as $key_array_bills_orders_custom => $bill_order_custom){
            if($key_array_bills_orders_custom == 0){
                $custom_array_dep_bills_orders_custom[$bill_order_custom['dep']][] = $bill_order_custom;
                $custom_array_type_bills_orders_custom[$bill_order_custom['type']][] = $bill_order_custom;
            }else{
                $exist = false;
                $position = '';
                foreach($custom_array_dep_bills_orders_custom as $key => $custom_bill_order_custom){
                    if($key == $bill_order_custom['dep']){
                        $exist = true;
                        $position = $key;
                    }
                }

                if($exist){
                    $custom_array_dep_bills_orders_custom[$position][] = $bill_order_custom;
                }

                if(!$exist){
                    $custom_array_dep_bills_orders_custom[$bill_order_custom['dep']][] = $bill_order_custom;
                }

                $exist = false;
                $position = '';
                foreach($custom_array_type_bills_orders_custom as $key => $custom_bill_order_custom){
                    if($key == $bill_order_custom['type']){
                        $exist = true;
                        $position = $key;
                    }
                }

                if($exist){
                    $custom_array_type_bills_orders_custom[$position][] = $bill_order_custom;
                }

                if(!$exist){
                    $custom_array_type_bills_orders_custom[$bill_order_custom['type']][] = $bill_order_custom;
                }
            }
        }

        //error_log(print_r($custom_array_dep_bills_orders_custom, true));

        foreach($custom_array_dep_bills_orders_custom as $key => $custom_bill_order_custom){
            $amounts_new = array();
            $amounts_old = array();
            $amounts_diference = array();
            $cont = 0;
            foreach($custom_bill_order_custom as $key => $cboc){
                if($cont == 0){
                    foreach($cboc['new']['amounts'] as $amount){
                        $amounts_new[] = $amount;
                    }
                    foreach($cboc['old']['amounts'] as $amount){
                        $amounts_old[] = $amount;
                    }
                }else{
                    foreach($amounts_new as $key_amount_new => $amount_new){
                        $amounts_new[$key_amount_new] += $cboc['new']['amounts'][$key_amount_new];
                    }
                    foreach($amounts_old as $key_amounts_old => $amount_old){
                        $amounts_old[$key_amounts_old] += $cboc['old']['amounts'][$key_amounts_old];
                    }
                }
                $cboc['type_obj'] = 1;
                $array_bills_orders_custom_aux[] = $cboc;
                $cont++;
                if($cont == count($custom_bill_order_custom)){
                    $custom_obj['dep'] = $cboc['dep_name'];
                    $custom_obj['type_obj'] = 2;
                    $custom_obj['new']['period'] = $cboc['new']['period'];
                    $custom_obj['new']['amounts'] = $amounts_new;
                    $custom_obj['old']['period'] = $cboc['old']['period'];
                    $custom_obj['old']['amounts'] = $amounts_old;

                    //Calculamos la cantidad de diferencia
                    $num_registers = count($amounts_new);
                    for($i=0; $i<$num_registers; $i++){
                        if($amounts_new[$i] == '0' || $amounts_old[$i] == '0'){
                            $amounts_diference[] = '-';
                        }else{
                            $diference = round((($amounts_new[$i] * 100) / $amounts_old[$i]) - 100, 2);
                            $amounts_diference[] = $diference;
                        }
                    }

                    $custom_obj['diference']['period'] = 'Diferencia%';
                    $custom_obj['diference']['amounts'] = $amounts_diference;
                    $array_bills_orders_custom_aux[] = $custom_obj;
                }
            }  
        }

        $total = 0;
        $amounts_new_total = array();
        $amounts_old_total = array();
        $amounts_diference_total = array();
        $cont_total = 0;
        $custom_obj_total;
        foreach($custom_array_type_bills_orders_custom as $key => $custom_bill_order_custom){
            $amounts_new = array();
            $amounts_old = array();
            $amounts_diference = array();
            $cont = 0;
            foreach($custom_bill_order_custom as $key => $cboc){
                if($cont == 0){
                    foreach($cboc['new']['amounts'] as $amount){
                        $amounts_new[] = round($amount, 2);
                    }
                    foreach($cboc['old']['amounts'] as $amount){
                        $amounts_old[] = round($amount, 2);
                    }
                }else{
                    foreach($amounts_new as $key_amount_new => $amount_new){
                        $amounts_new[$key_amount_new] += round($cboc['new']['amounts'][$key_amount_new], 2);
                    }
                    foreach($amounts_old as $key_amounts_old => $amount_old){
                        $amounts_old[$key_amounts_old] += round($cboc['old']['amounts'][$key_amounts_old], 2);
                    }
                }

                $cont++;
                if($cont == count($custom_bill_order_custom)){
                    $custom_obj['dep'] = $cboc['type'];
                    $custom_obj['type_obj'] = 3;
                    $custom_obj['new']['period'] = $cboc['new']['period'];
                    $custom_obj['new']['amounts'] = $amounts_new;
                    $custom_obj['old']['period'] = $cboc['old']['period'];
                    $custom_obj['old']['amounts'] = $amounts_old;

                    //Calculamos la cantidad de diferencia
                    $num_registers = count($amounts_new);
                    for($i=0; $i<$num_registers; $i++){
                        if($amounts_new[$i] == '0' || $amounts_old[$i] == '0'){
                            $amounts_diference[] = '-';
                        }else{
                            $diference = round((($amounts_new[$i] * 100) / $amounts_old[$i]) - 100, 2);
                            $amounts_diference[] = $diference;
                        }
                    }

                    $custom_obj['diference']['amounts'] = $amounts_diference;
                    $array_bills_orders_custom_aux[] = $custom_obj;

                    if($cont_total == 0){
                        $custom_obj_total['dep'] = 'TOTAL';
                        $custom_obj_total['type_obj'] = 4;
                        $custom_obj_total['new']['period'] = $cboc['new']['period'];
                        
                        $custom_obj_total['old']['period'] = $cboc['old']['period'];
                        
                        $amounts_new_total = $amounts_new;
                        $amounts_old_total = $amounts_old;
                    }else{
                        //Calculamos la cantidad de diferencia
                        $num_registers = count($amounts_new_total);
                        for($i=0; $i<$num_registers; $i++){
                            $amounts_new_total[$i] += round($amounts_new[$i], 2);
                            $amounts_old_total[$i] += round($amounts_old[$i], 2);
                        }
                    }
                }
            }  
            $cont_total++;
        }

        $custom_obj_total['new']['amounts'] = $amounts_new_total;
        $custom_obj_total['old']['amounts'] = $amounts_old_total;
       
        //Calculamos la cantidad de diferencia
        $num_registers = count($amounts_new_total);
        for($i=0; $i<$num_registers; $i++){
            if($amounts_new_total[$i] == '0' || $amounts_old_total[$i] == '0'){
                $amounts_diference_total[] = '-';
            }else{
                $diference = round((($amounts_new_total[$i] * 100) / $amounts_old_total[$i]) - 100, 2);
                $amounts_diference_total[] = $diference;
            }
        }

        $percent_old = '';
        $percent_new = '';
        $custom_obj_total['diference']['period'] = 'Diferencia%';
        $custom_obj_total['diference']['amounts'] = $amounts_diference_total;
        if(count($array_bills_orders_custom_aux) > 0){
            $array_bills_orders_custom_aux[] = $custom_obj_total;

            //CALCULAMOS PORCENTAJES PARA LA GRÁFICA
            $num_bills = count($array_bills_orders_custom_aux);
            $num_amounts = count($array_dates);

            $total_total_new = 0;
            $total_total_old = 0;
            $total_others_new = 0;
            $total_others_old = 0;
            $total_print_new = 0;
            $total_print_old = 0;
            $total_dig_new = 0;
            $total_dig_old = 0;

            foreach ($array_bills_orders_custom_aux as $key => $order_custom_aux) {
                if($order_custom_aux['dep'] == 'TOTAL'){
                    $total_total_new = $order_custom_aux['new']['amounts'][$num_amounts];
                    $total_total_old = $order_custom_aux['old']['amounts'][$num_amounts];
                }

                if($order_custom_aux['dep'] == 'OTROS'){
                    $total_others_new = $order_custom_aux['new']['amounts'][$num_amounts];
                    $total_others_old = $order_custom_aux['old']['amounts'][$num_amounts];
                }

                if($order_custom_aux['dep'] == 'PRINT'){
                    $total_print_new = $order_custom_aux['new']['amounts'][$num_amounts];
                    $total_print_old = $order_custom_aux['old']['amounts'][$num_amounts];
                }

                if($order_custom_aux['dep'] == 'DIG'){
                    $total_dig_new = $order_custom_aux['new']['amounts'][$num_amounts];
                    $total_dig_old = $order_custom_aux['old']['amounts'][$num_amounts];
                }
            }

            $percent_others_new = 0;
            $percent_print_new = 0;
            $percent_dig_new = 0;
            $percent_new = array();

            if($total_total_new > 0){
                $percent_others_new = round((100 * $total_others_new) / $total_total_new, 2);
                error_log('percent_others_new: '.$percent_others_new);
                $percent_print_new = round((100 * $total_print_new) / $total_total_new, 2);
                error_log('percent_print_new: '.$percent_print_new);
                $percent_dig_new = round((100 * $total_dig_new) / $total_total_new, 2);
                error_log('percent_dig_new: '.$percent_dig_new);
                $percent_new = [$percent_others_new, 0, $percent_dig_new, $percent_print_new];
            }

            $percent_others_old = 0;
            $percent_print_old = 0;
            $percent_dig_old = 0;
            $percent_old = array();

            if($total_total_old > 0){
                $percent_others_old = round((100 * $total_others_old) / $total_total_old, 2);
                $percent_print_old = round((100 * $total_print_old) / $total_total_old, 2);
                $percent_dig_old = round((100 * $total_dig_old) / $total_total_old, 2);
                $percent_old = [$percent_others_old, 0, $percent_dig_old, $percent_print_old];
            }


            $response['period_new'] = $array_bills_orders_custom_aux[$num_bills - 1]['new']['period'];
            $response['period_old'] = $array_bills_orders_custom_aux[$num_bills - 1]['old']['period'];
            $response['percent_old'] = $percent_old;
            $response['percent_new'] = $percent_new;
        }


        //error_log('array_bills_orders_custom_aux: '.print_r($array_bills_orders_custom_aux, true));
        $response['code'] = 1000;
        $response['array_dates'] = $array_dates;
        $response['array_bills_orders_custom'] = $array_bills_orders_custom_aux;
        return response()->json($response);
    }

    //Generar array de fechas
    function generateDateArray($num_months, $date_from_custom, $type){
        if($type == 2){
            $date_from_array = explode("-", $date_from_custom);
            $date_from_custom = $date_from_array[1].'-01-'.$date_from_array[2];
        }
        $array_dates = array();
        for($i=0; $i<=$num_months; $i++){
            $time = strtotime($date_from_custom);
            if($i == 0){
                $newformat = date('M-y',$time);
                $first_newformat_custom = date('m-d-Y', strtotime("+".$i." months", $time));
                $last_newformat_custom = date('m-t-Y', strtotime("+".$i." months", $time));
                $first_newformat_custom2 = date('d-m-Y', strtotime("+".$i." months", $time));
                $last_newformat_custom2 = date('t-m-Y', strtotime("+".$i." months", $time));
                $date_obj['date'] = '*'.$newformat;
                $date_obj['first_date_custom'] = $first_newformat_custom;
                $date_obj['last_date_custom'] = $last_newformat_custom;
                $date_obj['first_date_custom2'] = $first_newformat_custom2;
                $date_obj['last_date_custom2'] = $last_newformat_custom2;
                $array_dates[] = $date_obj;

            }else if($num_months == $i){
                $newformat = date('M-y', strtotime("+".$i." months", $time));
                $first_newformat_custom = date('m-d-Y', strtotime("+".$i." months", $time));
                $last_newformat_custom = date('m-t-Y', strtotime("+".$i." months", $time));
                $first_newformat_custom2 = date('d-m-Y', strtotime("+".$i." months", $time));
                $last_newformat_custom2 = date('t-m-Y', strtotime("+".$i." months", $time));
                $date_obj['date'] = '*'.$newformat;
                $date_obj['first_date_custom'] = $first_newformat_custom;
                $date_obj['last_date_custom'] = $last_newformat_custom;
                $date_obj['first_date_custom2'] = $first_newformat_custom2;
                $date_obj['last_date_custom2'] = $last_newformat_custom2;
                $array_dates[] = $date_obj;
            }else{
                $newformat = date('M-y', strtotime("+".$i." months", $time));
                $first_newformat_custom = date('m-d-Y', strtotime("+".$i." months", $time));
                $last_newformat_custom = date('m-t-Y', strtotime("+".$i." months", $time));
                $first_newformat_custom2 = date('d-m-Y', strtotime("+".$i." months", $time));
                $last_newformat_custom2 = date('t-m-Y', strtotime("+".$i." months", $time));
                $date_obj['date'] = $newformat;
                $date_obj['first_date_custom'] = $first_newformat_custom;
                $date_obj['last_date_custom'] = $last_newformat_custom;
                $date_obj['first_date_custom2'] = $first_newformat_custom2;
                $date_obj['last_date_custom2'] = $last_newformat_custom2;
                $array_dates[] = $date_obj;
            }
        }
        return $array_dates;
    }

    //Calcular número de meses entre dos fechas
    function calculateMonthsNumber($date_from, $date_to){
        $ts1 = strtotime($date_from);
        $ts2 = strtotime($date_to);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        return $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
    }

    //Descargar tabla de listado de informes csv
    function downloadReportsListCsv(Request $request){ 
        $select_department = $request->get('select_department');
        $select_section = $request->get('select_section');
        $select_channel = $request->get('select_channel');
        $select_project = $request->get('select_project');
        $select_chapter = $request->get('select_chapter');
        $select_consultant = $request->get('select_consultant');
        $select_order = $request->get('select_order');
        $select_compare = $request->get('select_compare');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');

        //Generamos array de fechas
        $array_dates = array();
        $num_months = $this->calculateMonthsNumber($date_from, $date_to);

        $date_from_array = explode("-", $date_from);
        $date_from_custom = $date_from_array[1].'-01-'.$date_from_array[2];
        $date_from_custom_old_time = strtotime($date_from);
        $date_from_custom_old = date('d-m-Y', strtotime("-".$select_compare." year", $date_from_custom_old_time));
        //error_log('date_from_custom: '.$date_from_custom);
        //error_log('date_from_custom_old: '.$date_from_custom_old);

        $date_to_array = explode("-", $date_to);
        $date_to_custom = $date_to_array[1].'-01-'.$date_to_array[2];
        $date_to_custom_old_time = strtotime($date_to);
        $date_to_custom_old = date('d-m-Y', strtotime("-".$select_compare." year", $date_to_custom_old_time));
        //error_log('date_to_custom: '.$date_to_custom);
        //error_log('date_to_custom_old: '.$date_to_custom_old);

        //Generamos los arrays de fechas
        $array_dates = $this->generateDateArray($num_months, $date_from_custom, 1);
        $array_dates_old = $this->generateDateArray($num_months, $date_from_custom_old, 2);

        //DIG
        $array_orders_dig = Order::select('orders.*', 'departments.nomenclature as department_nomenclature', 'departments.name as department_name', 'departments.id as id_department', 'proposals.id as id_proposal', 'channels.nomenclature as channel_nomenclature')
                                        ->leftJoin('bills_orders', 'bills_orders.id_order', 'orders.id')                                
                                        ->leftJoin('proposals', 'orders.id_proposal', 'proposals.id')
                                        ->leftJoin('contacts', 'proposals.id_contact', 'contacts.id')
                                        ->leftJoin('services_bills_orders', 'services_bills_orders.id_bill_order', 'bills_orders.id')
                                        ->leftJoin('services', 'services.id', 'services_bills_orders.id_service')
                                        ->leftJoin('articles', 'articles.id', 'services.id_article')
                                        ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                                        ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                                        ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                                        ->leftJoin('channels', 'channels.id', 'projects.id_channel')
                                        ->leftJoin('sections', 'sections.id', 'channels.id_section')
                                        ->leftJoin('departments', 'departments.id', 'sections.id_department');

        //PRINT
        $array_orders_print = Order::select('orders.*', 'departments.nomenclature as department_nomenclature', 'departments.name as department_name', 'departments.id as id_department', 'proposals.id as id_proposal', 'channels.nomenclature as channel_nomenclature')
                                        ->leftJoin('bills_orders', 'bills_orders.id_order', 'orders.id')                                
                                        ->leftJoin('proposals', 'orders.id_proposal', 'proposals.id')
                                        ->leftJoin('contacts', 'proposals.id_contact', 'contacts.id')
                                        ->leftJoin('services_bills_orders', 'services_bills_orders.id_bill_order', 'bills_orders.id')
                                        ->leftJoin('services', 'services.id', 'services_bills_orders.id_service')
                                        ->leftJoin('articles', 'articles.id', 'services.id_article')
                                        ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                                        ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                                        ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                                        ->leftJoin('channels', 'channels.id', 'projects.id_channel')
                                        ->leftJoin('sections', 'sections.id', 'channels.id_section')
                                        ->leftJoin('departments', 'departments.id', 'sections.id_department');
               
        //OTROS
        $array_orders_others = Order::select('orders.*', 'departments.nomenclature as department_nomenclature', 'departments.name as department_name', 'departments.id as id_department', 'proposals.id as id_proposal', 'channels.nomenclature as channel_nomenclature')
                                        ->leftJoin('bills_orders', 'bills_orders.id_order', 'orders.id')                                
                                        ->leftJoin('proposals', 'orders.id_proposal', 'proposals.id')
                                        ->leftJoin('contacts', 'proposals.id_contact', 'contacts.id')
                                        ->leftJoin('services_bills_orders', 'services_bills_orders.id_bill_order', 'bills_orders.id')
                                        ->leftJoin('services', 'services.id', 'services_bills_orders.id_service')
                                        ->leftJoin('articles', 'articles.id', 'services.id_article')
                                        ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                                        ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                                        ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                                        ->leftJoin('channels', 'channels.id', 'projects.id_channel')
                                        ->leftJoin('sections', 'sections.id', 'channels.id_section')
                                        ->leftJoin('departments', 'departments.id', 'sections.id_department');
                                        
        //Filtro departamente
        if($select_department != '0'){
            $array_orders_dig = $array_orders_dig->where('departments.id_department', $select_department);
            $array_orders_print = $array_orders_print->where('departments.id_department', $select_department);
            $array_orders_others = $array_orders_others->where('departments.id_department', $select_department);
        }

        //Filtro sección
        if($select_section != '0'){
            $array_orders_dig = $array_orders_dig->where('sections.id', $select_section);
            $array_orders_print = $array_orders_print->where('sections.id', $select_section);
            $array_orders_others = $array_orders_others->where('sections.id', $select_section);
        }

        //Filtro canal
        if($select_channel != '0'){
            $array_orders_dig = $array_orders_dig->where('channels.id', $select_channel);
            $array_orders_print = $array_orders_print->where('channels.id', $select_channel);
            $array_orders_others = $array_orders_others->where('channels.id', $select_channel);
        }

        //Filtro proyecto
        if($select_project != '0'){
            $array_orders_dig = $array_orders_dig->where('projects.id', $select_project);
            $array_orders_print = $array_orders_print->where('projects.id', $select_project);
            $array_orders_others = $array_orders_others->where('projects.id', $select_project);
        }

        //Filtro chapter
        if($select_chapter != '0'){
            $array_orders_dig = $array_orders_dig->where('chapters.id', $select_chapter);
            $array_orders_print = $array_orders_print->where('chapters.id', $select_chapter);
            $array_orders_others = $array_orders_others->where('chapters.id', $select_chapter);
        }

        //Filtro consultor
        if($select_consultant != '0'){
            $array_orders_dig = $array_orders_dig->where('contacts.id', $select_consultant);
            $array_orders_print = $array_orders_print->where('contacts.id', $select_consultant);
            $array_orders_others = $array_orders_others->where('contacts.id', $select_consultant);
        }

        //Filtro firmada o editando
        if($select_order == '1'){
            $array_orders_dig = $array_orders_dig->where('bills_orders.id_sage', '<>', null)->where('bills_orders.receipt_order_sage', '<>', null);
            $array_orders_print = $array_orders_print->where('bills_orders.id_sage', '<>', null)->where('bills_orders.receipt_order_sage', '<>', null);
            $array_orders_others = $array_orders_others->where('bills_orders.id_sage', '<>', null)->where('bills_orders.receipt_order_sage', '<>', null);
        }

        if($select_order == '2'){
            $array_orders_dig = $array_orders_dig->where('bills_orders.id_sage', null)->where('bills_orders.receipt_order_sage', null);
            $array_orders_print = $array_orders_print->where('bills_orders.id_sage', null)->where('bills_orders.receipt_order_sage', null);
            $array_orders_others = $array_orders_others->where('bills_orders.id_sage', null)->where('bills_orders.receipt_order_sage', null);
        }

        //Facturas DIG
        $array_orders_dig = $array_orders_dig->where('channels.nomenclature', 'DIG')
                                                //->groupBy('orders.id')
                                                ->get();
        error_log('array_orders_dig: '.$array_orders_dig);
        //error_log(count($array_orders_dig));

        //Facturas PRINT
        $array_orders_print = $array_orders_print->where('channels.nomenclature', 'PRINT')
                                                //->groupBy('orders.id', 'channels.nomenclature')
                                                ->get();
        //error_log(count($array_orders_print));
        //error_log('array_orders_print: '.$array_orders_print);

        //Facturas OTROS
        $array_orders_others = $array_orders_others->whereNotIn('channels.nomenclature', ['DIG', 'PRINT'])
                                                //->groupBy('orders.id', 'channels.nomenclature')
                                                ->get();
        //error_log(count($array_orders_others));
        //error_log('array_orders_others: '.$array_orders_others);

        //Creamos el objeto customizado
        $array_bills_orders_custom = array();
        
        //Recorremos el objeto DIG
        $array_aux_id_order_dig = array();
        foreach($array_orders_dig as $key => $order_dig){
            $custom_date_array_aux = explode(" ", $order_dig->created_at);
            $custom_date_array = explode("-", $custom_date_array_aux[0]);
            $custom_date = $custom_date_array[2].'-'.$custom_date_array[1].'-'.$custom_date_array[0];
            $custom_obj = null;
            $custom_obj_new = null;
            $custom_obj_old = null;
            $exist = false;
            if(count($array_aux_id_order_dig) > 0){
                foreach($array_aux_id_order_dig as $id_order){
                    if($id_order == $order_dig->id){
                        $exist = true;
                        error_log('entro');
                    }
                }
            }
            if(!$exist){
                $array_aux_id_order_dig[] = $order_dig->id;
                //Consultamos el amount de la orden
                $amount = BillOrder::where('id_order', $order_dig->id)->sum('amount');

                if($key == 0){
                    $custom_obj['dep'] = $order_dig->department_nomenclature;
                    $custom_obj['dep_name'] = $order_dig->department_name;
                    $custom_obj['id_dep'] = $order_dig->id_department;
                    $custom_obj['type'] = $order_dig->channel_nomenclature;
                    $custom_obj['id_type'] = $order_dig->id_channel;

                    $custom_obj_old['period'] = $date_from_custom_old.' a '.$date_to_custom_old;
                    foreach($array_dates_old as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                        //if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            $custom_obj_old['amounts'][] = round($amount, 2);
                        }else{
                            $custom_obj_old['amounts'][] = 0;
                        }
                    }
                    $custom_obj_new['period'] = $date_from.' a '.$date_to;
                    foreach($array_dates as $key_date => $date){

                        if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                        //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                            $custom_obj_new['amounts'][] = round($amount, 2);
                        }else{
                            $custom_obj_new['amounts'][] = 0;
                        }
                    }

                    $custom_obj_diference['period'] = 'Diferencia%';
                    $custom_obj['old'] = $custom_obj_old;
                    $custom_obj['new'] = $custom_obj_new;
                    $custom_obj['diference'] = $custom_obj_diference;
                    $array_bills_orders_custom[] = $custom_obj;

                }else{
                    $exist = false;
                    $position = 0;
                    foreach($array_bills_orders_custom as $key_array_bills_orders_custom => $bill_order_custom){
                        if($bill_order_custom['dep'] == $order_dig->department_nomenclature && $bill_order_custom['type'] == $order_dig->channel_nomenclature){
                            $exist = true;
                            $position = $key_array_bills_orders_custom;
                        }
                    }

                    if(!$exist){
                        $custom_obj['dep'] = $order_dig->department_nomenclature;
                        $custom_obj['dep_name'] = $order_dig->department_name;
                        $custom_obj['id_dep'] = $order_dig->id_department;
                        $custom_obj['type'] = $order_dig->channel_nomenclature;
                        $custom_obj['id_type'] = $order_dig->id_channel;

                        $custom_obj_old['period'] = $date_from_custom_old.' a '.$date_to_custom_old;
                        foreach($array_dates_old as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                                $custom_obj_old['amounts'][] = round($amount, 2);
                            }else{
                                $custom_obj_old['amounts'][] = 0;
                            }
                            error_log(count($custom_obj_old['amounts']));
                        }

                        $custom_obj_new['period'] = $date_from.' a '.$date_to;
                        foreach($array_dates as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                                $custom_obj_new['amounts'][] = round($amount, 2);
                            }else{
                                $custom_obj_new['amounts'][] = 0;
                            }
                        }
                        $custom_obj_diference['period'] = 'Diferencia%';
                        $custom_obj['old'] = $custom_obj_old;
                        $custom_obj['new'] = $custom_obj_new;
                        $custom_obj['diference'] = $custom_obj_diference;
                        $array_bills_orders_custom[] = $custom_obj;
                    }

                    if($exist){
                        //Aquí sumariamos precios de las ordenes
                        foreach($array_dates_old as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                                $array_bills_orders_custom[$position]['old']['amounts'][$key_date] += round($amount, 2);
                            }
                        }
                        foreach($array_dates as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if($date['last_date_custom'] >= $custom_date && $date['first_date_custom'] <= $custom_date){
                                $array_bills_orders_custom[$position]['new']['amounts'][$key_date] += round($amount, 2);
                            }
                        }
                    }
                }
            }
            $exist = false;
        }

        error_log(print_r($array_bills_orders_custom, true));
        
        //Recorremos el objeto PRINT
        $array_aux_id_order_print = array();
        foreach($array_orders_print as $key => $order_print){
            $exist = false;
            
            $custom_date_array_aux = explode(" ", $order_print->created_at);
            $custom_date_array = explode("-", $custom_date_array_aux[0]);
            $custom_date = $custom_date_array[2].'-'.$custom_date_array[1].'-'.$custom_date_array[0];
            $custom_obj = null;
            $custom_obj_new = null;
            $custom_obj_old = null;
            $custom_obj_diference = null;
            if(count($array_aux_id_order_print) > 0){
                foreach($array_aux_id_order_print as $id_order){
                    if($id_order == $order_print->id){
                        $exist = true;
                    }
                }
            }
            if(!$exist){
                $array_aux_id_order_print[] = $order_print->id;

                //Consultamos el amount de la orden
                $amount = BillOrder::where('id_order', $order_print->id)->sum('amount');

                if($key == 0){
                    $custom_obj['dep'] = $order_print->department_nomenclature;
                    $custom_obj['dep_name'] = $order_print->department_name;
                    $custom_obj['id_dep'] = $order_print->id_department;
                    $custom_obj['type'] = $order_print->channel_nomenclature;
                    $custom_obj['id_type'] = $order_print->id_channel;

                    $custom_obj_old['period'] = $date_from_custom_old.' a '.$date_to_custom_old;
                    foreach($array_dates_old as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            $custom_obj_old['amounts'][] = round($amount, 2);
                        }else{
                            $custom_obj_old['amounts'][] = 0;
                        }
                    }
                    $custom_obj_new['period'] = $date_from.' a '.$date_to;
                    foreach($array_dates as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            $custom_obj_new['amounts'][] = round($amount, 2);
                        }else{
                            $custom_obj_new['amounts'][] = 0;
                        }
                    }

                    $custom_obj_diference['period'] = 'Diferencia%';
                    $custom_obj['old'] = $custom_obj_old;
                    $custom_obj['new'] = $custom_obj_new;
                    $custom_obj['diference'] = $custom_obj_diference;
                    $array_bills_orders_custom[] = $custom_obj;

                }else{
                    $exist = false;
                    $position = 0;
                    foreach($array_bills_orders_custom as $key_array_bills_orders_custom => $bill_order_custom){
                        if($bill_order_custom['dep'] == $order_print->department_nomenclature && $bill_order_custom['type'] == $order_print->channel_nomenclature){
                            $exist = true;
                            $position = $key_array_bills_orders_custom;
                        }
                    }

                    if(!$exist){
                        $custom_obj['dep'] = $order_print->department_nomenclature;
                        $custom_obj['dep_name'] = $order_print->department_name;
                        $custom_obj['id_dep'] = $order_print->id_department;
                        $custom_obj['type'] = $order_print->channel_nomenclature;
                        $custom_obj['id_type'] = $order_print->id_channel;
                        $custom_obj_old['period'] = $date_from_custom_old.' a '.$date_to_custom_old;

                        foreach($array_dates_old as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                                $custom_obj_old['amounts'][] = round($amount, 2);
                            }else{
                                $custom_obj_old['amounts'][] = 0;
                            }
                        }

                        $custom_obj_new['period'] = $date_from.' a '.$date_to;
                        foreach($array_dates as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                                $custom_obj_new['amounts'][] = round($amount, 2);
                            }else{
                                $custom_obj_new['amounts'][] = 0;
                            }
                        }
                        $custom_obj_diference['period'] = 'Diferencia%';
                        $custom_obj['old'] = $custom_obj_old;
                        $custom_obj['new'] = $custom_obj_new;
                        $custom_obj['diference'] = $custom_obj_diference;
                        $array_bills_orders_custom[] = $custom_obj;
                    }

                    if($exist){
                        //Aquí sumariamos precios de las ordenes
                        foreach($array_dates_old as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                                $array_bills_orders_custom[$position]['old']['amounts'][$key_date] += round($amount, 2);
                            }
                        }
                        foreach($array_dates as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if($date['last_date_custom'] >= $custom_date && $date['first_date_custom'] <= $custom_date){
                                $array_bills_orders_custom[$position]['new']['amounts'][$key_date] += round($amount, 2);
                            }
                        }
                    }
                }
            }
            $exist = false;
        }

        //Recorremos el objeto OTROS
        $array_aux_id_order_others = array();
        foreach($array_orders_others as $key => $order_others){
            $custom_obj = null;
            $custom_obj_new = null;
            $custom_obj_old = null;
            $custom_obj_diference = null;
            $custom_date_array_aux = explode(" ", $order_others->created_at);
            $custom_date_array = explode("-", $custom_date_array_aux[0]);
            $custom_date = $custom_date_array[2].'-'.$custom_date_array[1].'-'.$custom_date_array[0];
            $exist = false;
            if(count($array_aux_id_order_others) > 0){
                foreach($array_aux_id_order_others as $id_order){
                    if($id_order == $order_others->id){
                        $exist = true;
                    }
                }
            }
            if(!$exist){
                $array_aux_id_order_others[] = $order_others->id;
                //Consultamos el amount de la orden
                $amount = BillOrder::where('id_order', $order_others->id)->sum('amount');

                if($key == 0){
                    $custom_obj['dep'] = $order_others->department_nomenclature;
                    $custom_obj['dep_name'] = $order_others->department_name;
                    $custom_obj['id_dep'] = $order_others->id_department;
                    $custom_obj['type'] = 'OTROS';
                    $custom_obj['id_type'] = $order_others->id_channel;

                    $custom_obj_old['period'] = $date_from_custom_old.' a '.$date_to_custom_old;
                    foreach($array_dates_old as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                        //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                            $custom_obj_old['amounts'][] = round($amount, 2);
                        }else{
                            $custom_obj_old['amounts'][] = 0;
                        }
                    }
                    $custom_obj_new['period'] = $date_from.' a '.$date_to;
                    foreach($array_dates as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                        //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                            $custom_obj_new['amounts'][] = round($amount, 2);
                        }else{
                            $custom_obj_new['amounts'][] = 0;
                        }
                    }

                    $custom_obj_diference['period'] = 'Diferencia%';
                    $custom_obj['old'] = $custom_obj_old;
                    $custom_obj['new'] = $custom_obj_new;
                    $custom_obj['diference'] = $custom_obj_diference;
                    $array_bills_orders_custom[] = $custom_obj;

                }else{
                    foreach($array_bills_orders_custom as $key_array_bills_orders_custom => $bill_order_custom){
                        //Aquí sumariamos precios de las ordenes
                        foreach($array_dates_old as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                                $array_bills_orders_custom[$key_array_bills_orders_custom]['old']['amounts'][$key_date] += round($amount, 2);
                            }
                        }
                        foreach($array_dates as $key_date => $date){
                            if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                            //if($date['last_date_custom'] >= $custom_date && $date['first_date_custom'] <= $custom_date){
                                $array_bills_orders_custom[$key_array_bills_orders_custom]['new']['amounts'][$key_date] += round($amount, 2);
                            }
                        }
                    }
                }
            }
            $exist = false;
        }

        //Guardamos el objeto diferencia
        if(count($array_bills_orders_custom) > 0){
            if(count($array_bills_orders_custom[0]['new']) > 0){
                $num_registers = count($array_bills_orders_custom[0]['new']['amounts']);
                foreach($array_bills_orders_custom as $key_array_bills_orders_custom => $bill_order_custom){
                    for($i=0; $i<$num_registers; $i++){
                        if($array_bills_orders_custom[$key_array_bills_orders_custom]['new']['amounts'][$i] == 0 || $array_bills_orders_custom[$key_array_bills_orders_custom]['old']['amounts'][$i] == 0){
                            $array_bills_orders_custom[$key_array_bills_orders_custom]['diference']['amounts'][] = '-';
                        }else{
                            $diference = round(($array_bills_orders_custom[$key_array_bills_orders_custom]['new']['amounts'][$i] * 100) / $array_bills_orders_custom[$key_array_bills_orders_custom]['old']['amounts'][$i], 2) - 100;
                            $array_bills_orders_custom[$key_array_bills_orders_custom]['diference']['amounts'][] = round($diference, 2);
                        }
                    }
                }
            }
        }

        //error_log(print_r($array_bills_orders_custom, true));

        //Calculamos los totales de las cantidades de $array_bills_orders_custom
        foreach($array_bills_orders_custom as $key_array_bills_orders_custom => $bill_order_custom){
            $array_amounts_new = $bill_order_custom['new']['amounts'];
            $total_new = 0;
            foreach($array_amounts_new as $amount_new){
                $total_new += round($amount_new, 2);
            }
            
            $array_bills_orders_custom[$key_array_bills_orders_custom]['new']['amounts'][] = round($total_new, 2);

            $array_amounts_old = $bill_order_custom['old']['amounts'];
            $total_old = 0;
            foreach($array_amounts_old as $amount_old){
                $total_old += round($amount_old, 2);
            }
            
            $array_bills_orders_custom[$key_array_bills_orders_custom]['old']['amounts'][] = round($total_old, 2);

            if($total_new == 0 || $total_old == 0){
                $array_bills_orders_custom[$key_array_bills_orders_custom]['diference']['amounts'][] = '-';
            }else{
                $diference = round(($total_new * 100) / $total_old, 2) - 100;
                $array_bills_orders_custom[$key_array_bills_orders_custom]['diference']['amounts'][] = round($diference, 2);
            }
        }

        //Ordenamos el array por departamento y calculamos sus totales
        $custom_array_dep_bills_orders_custom = array();
        $custom_array_type_bills_orders_custom = array();
        $array_bills_orders_custom_aux = array();
        foreach($array_bills_orders_custom as $key_array_bills_orders_custom => $bill_order_custom){
            if($key_array_bills_orders_custom == 0){
                $custom_array_dep_bills_orders_custom[$bill_order_custom['dep']][] = $bill_order_custom;
                $custom_array_type_bills_orders_custom[$bill_order_custom['type']][] = $bill_order_custom;
            }else{
                $exist = false;
                $position = '';
                foreach($custom_array_dep_bills_orders_custom as $key => $custom_bill_order_custom){
                    if($key == $bill_order_custom['dep']){
                        $exist = true;
                        $position = $key;
                    }
                }

                if($exist){
                    $custom_array_dep_bills_orders_custom[$position][] = $bill_order_custom;
                }

                if(!$exist){
                    $custom_array_dep_bills_orders_custom[$bill_order_custom['dep']][] = $bill_order_custom;
                }

                $exist = false;
                $position = '';
                foreach($custom_array_type_bills_orders_custom as $key => $custom_bill_order_custom){
                    if($key == $bill_order_custom['type']){
                        $exist = true;
                        $position = $key;
                    }
                }

                if($exist){
                    $custom_array_type_bills_orders_custom[$position][] = $bill_order_custom;
                }

                if(!$exist){
                    $custom_array_type_bills_orders_custom[$bill_order_custom['type']][] = $bill_order_custom;
                }
            }
        }

        //error_log(print_r($custom_array_dep_bills_orders_custom, true));

        foreach($custom_array_dep_bills_orders_custom as $key => $custom_bill_order_custom){
            $amounts_new = array();
            $amounts_old = array();
            $amounts_diference = array();
            $cont = 0;
            foreach($custom_bill_order_custom as $key => $cboc){
                if($cont == 0){
                    foreach($cboc['new']['amounts'] as $amount){
                        $amounts_new[] = $amount;
                    }
                    foreach($cboc['old']['amounts'] as $amount){
                        $amounts_old[] = $amount;
                    }
                }else{
                    foreach($amounts_new as $key_amount_new => $amount_new){
                        $amounts_new[$key_amount_new] += $cboc['new']['amounts'][$key_amount_new];
                    }
                    foreach($amounts_old as $key_amounts_old => $amount_old){
                        $amounts_old[$key_amounts_old] += $cboc['old']['amounts'][$key_amounts_old];
                    }
                }
                $cboc['type_obj'] = 1;
                $array_bills_orders_custom_aux[] = $cboc;
                $cont++;
                if($cont == count($custom_bill_order_custom)){
                    $custom_obj['dep'] = $cboc['dep_name'];
                    $custom_obj['type_obj'] = 2;
                    $custom_obj['new']['period'] = $cboc['new']['period'];
                    $custom_obj['new']['amounts'] = $amounts_new;
                    $custom_obj['old']['period'] = $cboc['old']['period'];
                    $custom_obj['old']['amounts'] = $amounts_old;

                    //Calculamos la cantidad de diferencia
                    $num_registers = count($amounts_new);
                    for($i=0; $i<$num_registers; $i++){
                        if($amounts_new[$i] == '0' || $amounts_old[$i] == '0'){
                            $amounts_diference[] = '-';
                        }else{
                            $diference = round((($amounts_new[$i] * 100) / $amounts_old[$i]) - 100, 2);
                            $amounts_diference[] = $diference;
                        }
                    }

                    $custom_obj['diference']['period'] = 'Diferencia%';
                    $custom_obj['diference']['amounts'] = $amounts_diference;
                    $array_bills_orders_custom_aux[] = $custom_obj;
                }
            }  
        }

        $total = 0;
        $amounts_new_total = array();
        $amounts_old_total = array();
        $amounts_diference_total = array();
        $cont_total = 0;
        $custom_obj_total;
        foreach($custom_array_type_bills_orders_custom as $key => $custom_bill_order_custom){
            $amounts_new = array();
            $amounts_old = array();
            $amounts_diference = array();
            $cont = 0;
            foreach($custom_bill_order_custom as $key => $cboc){
                if($cont == 0){
                    foreach($cboc['new']['amounts'] as $amount){
                        $amounts_new[] = round($amount, 2);
                    }
                    foreach($cboc['old']['amounts'] as $amount){
                        $amounts_old[] = round($amount, 2);
                    }
                }else{
                    foreach($amounts_new as $key_amount_new => $amount_new){
                        $amounts_new[$key_amount_new] += round($cboc['new']['amounts'][$key_amount_new], 2);
                    }
                    foreach($amounts_old as $key_amounts_old => $amount_old){
                        $amounts_old[$key_amounts_old] += round($cboc['old']['amounts'][$key_amounts_old], 2);
                    }
                }

                $cont++;
                if($cont == count($custom_bill_order_custom)){
                    $custom_obj['dep'] = $cboc['type'];
                    $custom_obj['type_obj'] = 3;
                    $custom_obj['new']['period'] = $cboc['new']['period'];
                    $custom_obj['new']['amounts'] = $amounts_new;
                    $custom_obj['old']['period'] = $cboc['old']['period'];
                    $custom_obj['old']['amounts'] = $amounts_old;

                    //Calculamos la cantidad de diferencia
                    $num_registers = count($amounts_new);
                    for($i=0; $i<$num_registers; $i++){
                        if($amounts_new[$i] == '0' || $amounts_old[$i] == '0'){
                            $amounts_diference[] = '-';
                        }else{
                            $diference = round((($amounts_new[$i] * 100) / $amounts_old[$i]) - 100, 2);
                            $amounts_diference[] = $diference;
                        }
                    }

                    $custom_obj['diference']['amounts'] = $amounts_diference;
                    $array_bills_orders_custom_aux[] = $custom_obj;

                    if($cont_total == 0){
                        $custom_obj_total['dep'] = 'TOTAL';
                        $custom_obj_total['type_obj'] = 4;
                        $custom_obj_total['new']['period'] = $cboc['new']['period'];
                        
                        $custom_obj_total['old']['period'] = $cboc['old']['period'];
                        
                        $amounts_new_total = $amounts_new;
                        $amounts_old_total = $amounts_old;
                    }else{
                        //Calculamos la cantidad de diferencia
                        $num_registers = count($amounts_new_total);
                        for($i=0; $i<$num_registers; $i++){
                            $amounts_new_total[$i] += round($amounts_new[$i], 2);
                            $amounts_old_total[$i] += round($amounts_old[$i], 2);
                        }
                    }
                }
            }  
            $cont_total++;
        }

        $custom_obj_total['new']['amounts'] = $amounts_new_total;
        $custom_obj_total['old']['amounts'] = $amounts_old_total;
       
        //Calculamos la cantidad de diferencia
        $num_registers = count($amounts_new_total);
        for($i=0; $i<$num_registers; $i++){
            if($amounts_new_total[$i] == '0' || $amounts_old_total[$i] == '0'){
                $amounts_diference_total[] = '-';
            }else{
                $diference = round((($amounts_new_total[$i] * 100) / $amounts_old_total[$i]) - 100, 2);
                $amounts_diference_total[] = $diference;
            }
        }

        $percent_old = '';
        $percent_new = '';
        $custom_obj_total['diference']['period'] = 'Diferencia%';
        $custom_obj_total['diference']['amounts'] = $amounts_diference_total;
        if(count($array_bills_orders_custom_aux) > 0){
            $array_bills_orders_custom_aux[] = $custom_obj_total;

            //CALCULAMOS PORCENTAJES PARA LA GRÁFICA
            $num_bills = count($array_bills_orders_custom_aux);
            $num_amounts = count($array_dates);

            $total_total_new = 0;
            $total_total_old = 0;
            $total_others_new = 0;
            $total_others_old = 0;
            $total_print_new = 0;
            $total_print_old = 0;
            $total_dig_new = 0;
            $total_dig_old = 0;

            foreach ($array_bills_orders_custom_aux as $key => $order_custom_aux) {
                if($order_custom_aux['dep'] == 'TOTAL'){
                    $total_total_new = $order_custom_aux['new']['amounts'][$num_amounts];
                    $total_total_old = $order_custom_aux['old']['amounts'][$num_amounts];
                }

                if($order_custom_aux['dep'] == 'OTROS'){
                    $total_others_new = $order_custom_aux['new']['amounts'][$num_amounts];
                    $total_others_old = $order_custom_aux['old']['amounts'][$num_amounts];
                }

                if($order_custom_aux['dep'] == 'PRINT'){
                    $total_print_new = $order_custom_aux['new']['amounts'][$num_amounts];
                    $total_print_old = $order_custom_aux['old']['amounts'][$num_amounts];
                }

                if($order_custom_aux['dep'] == 'DIG'){
                    $total_dig_new = $order_custom_aux['new']['amounts'][$num_amounts];
                    $total_dig_old = $order_custom_aux['old']['amounts'][$num_amounts];
                }
            }

            $percent_others_new = 0;
            $percent_print_new = 0;
            $percent_dig_new = 0;
            $percent_new = array();

            if($total_total_new > 0){
                $percent_others_new = round((100 * $total_others_new) / $total_total_new, 2);
                error_log('percent_others_new: '.$percent_others_new);
                $percent_print_new = round((100 * $total_print_new) / $total_total_new, 2);
                error_log('percent_print_new: '.$percent_print_new);
                $percent_dig_new = round((100 * $total_dig_new) / $total_total_new, 2);
                error_log('percent_dig_new: '.$percent_dig_new);
                $percent_new = [$percent_others_new, 0, $percent_dig_new, $percent_print_new];
            }

            $percent_others_old = 0;
            $percent_print_old = 0;
            $percent_dig_old = 0;
            $percent_old = array();

            if($total_total_old > 0){
                $percent_others_old = round((100 * $total_others_old) / $total_total_old, 2);
                $percent_print_old = round((100 * $total_print_old) / $total_total_old, 2);
                $percent_dig_old = round((100 * $total_dig_old) / $total_total_old, 2);
                $percent_old = [$percent_others_old, 0, $percent_dig_old, $percent_print_old];
            }


            $response['period_new'] = $array_bills_orders_custom_aux[$num_bills - 1]['new']['period'];
            $response['period_old'] = $array_bills_orders_custom_aux[$num_bills - 1]['old']['period'];
            $response['percent_old'] = $percent_old;
            $response['percent_new'] = $percent_new;
        }

        if(count($array_bills_orders_custom_aux) > 0){
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $array_celdas = array();
            //Creamos las cabeceras
            $sheet->setCellValue('A1', 'Dep');
            $array_celdas[] = 'A';
            $sheet->setCellValue('B1', 'Tipo');
            $array_celdas[] = 'B';
            $sheet->setCellValue('C1', 'Periodo');
            $array_celdas[] = 'C';
            $letter = 'C';
            for($i=0;$i<count($array_dates);$i++){
                $letter++;
                $array_celdas[] = $letter;
                $sheet->setCellValue($letter.'1', $array_dates[$i]['date']);
            }
            $letter++;
            $array_celdas[] = $letter;
            $sheet->setCellValue($letter.'1', 'Total');

            $normal_key = 2;
            $old_key = 2;
            $new_key = 3;
            $diference_key = 4;

            foreach ($array_bills_orders_custom_aux as $key => $bill_order_custom) {
                if($bill_order_custom['type_obj'] == 1){
                    foreach($array_celdas as $key_celda => $celda){
                        if($celda == 'A'){
                            if(isset($bill_order_custom['dep'])){
                                $sheet->setCellValue($celda.($normal_key), $bill_order_custom['dep']);
                            }
                        }
                        if($celda == 'B'){
                            if(isset($bill_order_custom['type'])){
                                $sheet->setCellValue($celda.($normal_key), $bill_order_custom['type']);
                            }
                        }

                        if($celda != 'A' && $celda != 'B'){
                            if($celda == 'C'){
                                $sheet->setCellValue($celda.($old_key), $bill_order_custom['old']['period']);
                                $sheet->setCellValue($celda.($new_key), $bill_order_custom['new']['period']);
                                $sheet->setCellValue($celda.($diference_key), $bill_order_custom['diference']['period']);
                            }
                            if($celda != 'C'){
                                $sheet->setCellValue($celda.($old_key), $bill_order_custom['old']['amounts'][$key_celda - 3]);
                                $sheet->setCellValue($celda.($new_key), $bill_order_custom['new']['amounts'][$key_celda - 3]);
                                $sheet->setCellValue($celda.($diference_key), $bill_order_custom['diference']['amounts'][$key_celda - 3]);
                            }
                        }
                    }
                }

                if($bill_order_custom['type_obj'] == 2){
                    foreach($array_celdas as $key_celda => $celda){
                        if($celda == 'A'){
                            if(isset($bill_order_custom['dep'])){
                                $sheet->setCellValue($celda.($normal_key), $bill_order_custom['dep']);
                            }
                        }

                        if($celda != 'A' && $celda != 'B'){
                            if($celda == 'C'){
                                $sheet->setCellValue($celda.($old_key), $bill_order_custom['old']['period']);
                                $sheet->setCellValue($celda.($new_key), $bill_order_custom['new']['period']);
                                $sheet->setCellValue($celda.($diference_key), $bill_order_custom['diference']['period']);
                            }
                            if($celda != 'C'){
                                $sheet->setCellValue($celda.($old_key), $bill_order_custom['old']['amounts'][$key_celda - 3]);
                                $sheet->setCellValue($celda.($new_key), $bill_order_custom['new']['amounts'][$key_celda - 3]);
                                $sheet->setCellValue($celda.($diference_key), $bill_order_custom['diference']['amounts'][$key_celda - 3]);
                            }
                        }
                    }
                }

                if($bill_order_custom['type_obj'] == 3){
                    foreach($array_celdas as $key_celda => $celda){
                        if($celda == 'A'){
                            if(isset($bill_order_custom['dep'])){
                                $sheet->setCellValue($celda.($normal_key), $bill_order_custom['dep']);
                            }
                        }

                        if($celda != 'A' && $celda != 'B'){
                            if($celda == 'C'){
                                $sheet->setCellValue($celda.($old_key), $bill_order_custom['old']['period']);
                                $sheet->setCellValue($celda.($new_key), $bill_order_custom['new']['period']);
                                $sheet->setCellValue($celda.($diference_key), $bill_order_custom['diference']['period']);
                            }
                            if($celda != 'C'){
                                $sheet->setCellValue($celda.($old_key), $bill_order_custom['old']['amounts'][$key_celda - 3]);
                                $sheet->setCellValue($celda.($new_key), $bill_order_custom['new']['amounts'][$key_celda - 3]);
                                $sheet->setCellValue($celda.($diference_key), $bill_order_custom['diference']['amounts'][$key_celda - 3]);
                            }
                        }
                    }
                }
                
                if($bill_order_custom['type_obj'] == 4){
                    foreach($array_celdas as $key_celda => $celda){
                        if($celda == 'A'){
                            if(isset($bill_order_custom['dep'])){
                                $sheet->setCellValue($celda.($normal_key), $bill_order_custom['dep']);
                            }
                        }

                        if($celda != 'A' && $celda != 'B'){
                            if($celda == 'C'){
                                $sheet->setCellValue($celda.($old_key), $bill_order_custom['old']['period']);
                                $sheet->setCellValue($celda.($new_key), $bill_order_custom['new']['period']);
                                $sheet->setCellValue($celda.($diference_key), $bill_order_custom['diference']['period']);
                            }
                            if($celda != 'C'){
                                $sheet->setCellValue($celda.($old_key), $bill_order_custom['old']['amounts'][$key_celda - 3]);
                                $sheet->setCellValue($celda.($new_key), $bill_order_custom['new']['amounts'][$key_celda - 3]);
                                $sheet->setCellValue($celda.($diference_key), $bill_order_custom['diference']['amounts'][$key_celda - 3]);
                            }
                        }
                    }
                }

                $normal_key += 3;
                $old_key += 3;
                $new_key += 3;
                $diference_key += 3;
            }

            $writer = new Xlsx($spreadsheet);
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'.'propuestas.xlsx');
            $writer->save('php://output');
        }
    }
}
