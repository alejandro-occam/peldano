<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillOrder; 
use App\Models\Section; 
use App\Models\Channel; 
use App\Models\ProposalBill;
use App\Models\ServiceBill;

class ReportRecruimentByChannel extends Controller
{
    //Listado de facturas según el filtro
    function reportsList(Request $request){
        $select_department = $request->get('select_department');
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
        error_log('date_from_custom: '.$date_from_custom);
        error_log('date_from_custom_old: '.$date_from_custom_old);

        $date_to_array = explode("-", $date_to);
        $date_to_custom = $date_to_array[1].'-01-'.$date_to_array[2];
        $date_to_custom_old_time = strtotime($date_to);
        $date_to_custom_old = date('d-m-Y', strtotime("-1 year", $date_to_custom_old_time));
        error_log('date_to_custom: '.$date_to_custom);
        error_log('date_to_custom_old: '.$date_to_custom_old);

        $array_dates = $this->generateDateArray($num_months, $date_from_custom);

        //Canales DIG Y PRINT
        $array_bills_orders_dig = BillOrder::select('bills_orders.*', 'departments.nomenclature as department_nomenclature', 'departments.id as id_department', 'proposals.id as id_proposal', 'channels.nomenclature as channel_nomenclature')
                                        ->leftJoin('orders', 'orders.id', 'bills_orders.id_order')
                                        ->leftJoin('proposals', 'orders.id_proposal', 'proposals.id')
                                        ->leftJoin('contacts', 'proposals.id_contact', 'contacts.id')
                                        ->leftJoin('departments', 'proposals.id_department', 'departments.id')
                                        ->leftJoin('proposals_bills', 'proposals_bills.id_proposal', 'proposals.id')
                                        ->leftJoin('services_bills', 'services_bills.id_bill', 'proposals_bills.id_bill')
                                        ->leftJoin('bills', 'bills.id', 'services_bills.id_bill')
                                        ->leftJoin('services', 'services.id', 'services_bills.id_service')
                                        ->leftJoin('articles', 'articles.id', 'services.id_article')
                                        ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                                        ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                                        ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                                        ->leftJoin('channels', 'channels.id', 'projects.id_channel');

        $array_bills_orders_print = BillOrder::select('bills_orders.*', 'departments.nomenclature as department_nomenclature', 'departments.id as id_department', 'proposals.id as id_proposal', 'channels.nomenclature as channel_nomenclature')
                                        ->leftJoin('orders', 'orders.id', 'bills_orders.id_order')
                                        ->leftJoin('proposals', 'orders.id_proposal', 'proposals.id')
                                        ->leftJoin('contacts', 'proposals.id_contact', 'contacts.id')
                                        ->leftJoin('departments', 'proposals.id_department', 'departments.id')
                                        ->leftJoin('proposals_bills', 'proposals_bills.id_proposal', 'proposals.id')
                                        ->leftJoin('services_bills', 'services_bills.id_bill', 'proposals_bills.id_bill')
                                        ->leftJoin('bills', 'bills.id', 'services_bills.id_bill')
                                        ->leftJoin('services', 'services.id', 'services_bills.id_service')
                                        ->leftJoin('articles', 'articles.id', 'services.id_article')
                                        ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                                        ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                                        ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                                        ->leftJoin('channels', 'channels.id', 'projects.id_channel');
                                    
        $array_bills_orders_others = BillOrder::select('bills_orders.*', 'departments.nomenclature as department_nomenclature', 'departments.id as id_department', 'proposals.id as id_proposal', 'channels.nomenclature as channel_nomenclature')
                                        ->leftJoin('orders', 'orders.id', 'bills_orders.id_order')
                                        ->leftJoin('proposals', 'orders.id_proposal', 'proposals.id')
                                        ->leftJoin('contacts', 'proposals.id_contact', 'contacts.id')
                                        ->leftJoin('departments', 'proposals.id_department', 'departments.id')
                                        ->leftJoin('proposals_bills', 'proposals_bills.id_proposal', 'proposals.id')
                                        ->leftJoin('services_bills', 'services_bills.id_bill', 'proposals_bills.id_bill')
                                        ->leftJoin('bills', 'bills.id', 'services_bills.id_bill')
                                        ->leftJoin('services', 'services.id', 'services_bills.id_service')
                                        ->leftJoin('articles', 'articles.id', 'services.id_article')
                                        ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                                        ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                                        ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                                        ->leftJoin('channels', 'channels.id', 'projects.id_channel');
                                        
        //Filtro departamente
        if($select_department != '0'){
            $array_bills_orders_dig = $array_bills_orders_dig->where('proposals.id_department', '<>', $select_department);
            $array_bills_orders_print = $array_bills_orders_print->where('proposals.id_department', '<>', $select_department);
            $array_bills_orders_others = $array_bills_orders_others->where('proposals.id_department', '<>', $select_department);
        }

        //Filtro consultor
        if($select_consultant != '0'){
            $array_bills_orders_dig = $array_bills_orders_dig->where('contacts.id', '<>', $select_consultant);
            $array_bills_orders_print = $array_bills_orders_print->where('contacts.id', '<>', $select_consultant);
            $array_bills_orders_others = $array_bills_orders_others->where('contacts.id', '<>', $select_consultant);
        }

        //Filtro firmada o editando
        if($select_order == '1'){
            $array_bills_orders_dig = $array_bills_orders_dig->where('bills_orders.id_sage', '<>', null)->where('bills_orders.receipt_order_sage', '<>', null);
            $array_bills_orders_print = $array_bills_orders_print->where('bills_orders.id_sage', '<>', null)->where('bills_orders.receipt_order_sage', '<>', null);
            $array_bills_orders_others = $array_bills_orders_others->where('bills_orders.id_sage', '<>', null)->where('bills_orders.receipt_order_sage', '<>', null);
        }

        if($select_order == '2'){
            $array_bills_orders_dig = $array_bills_orders_dig->where('bills_orders.id_sage', null)->where('bills_orders.receipt_order_sage', null);
            $array_bills_orders_print = $array_bills_orders_print->where('bills_orders.id_sage', null)->where('bills_orders.receipt_order_sage', null);
            $array_bills_orders_others = $array_bills_orders_others->where('bills_orders.id_sage', null)->where('bills_orders.receipt_order_sage', null);
        }

        //Facturas DIG
        $array_bills_orders_dig = $array_bills_orders_dig->where('channels.nomenclature', 'DIG')
                                                ->groupBy('bills_orders.id', 'channels.nomenclature')
                                                ->get();
        //error_log(count($array_bills_orders_dig));
        //error_log($array_bills_orders_dig);

        //Facturas PRINT
        $array_bills_orders_print = $array_bills_orders_print->where('channels.nomenclature', 'PRINT')
                                                ->groupBy('bills_orders.id', 'channels.nomenclature')
                                                ->get();
        //error_log(count($array_bills_orders_print));
        //error_log($array_bills_orders_print);

        //Facturas OTROS
        $array_bills_orders_others = $array_bills_orders_others->whereNotIn('channels.nomenclature', ['DIG', 'PRINT'])
                                                ->groupBy('bills_orders.id', 'channels.nomenclature')
                                                ->get();
        //error_log(count($array_bills_orders_others));
        //error_log($array_bills_orders_others);

        //Creamos el objeto customizado
        $array_bills_orders_custom = array();
        
        foreach($array_bills_orders_dig as $key => $bill_order_dig){
            $custom_date_array = explode("-", $bill_order_dig->date);
            $custom_date = $custom_date_array[1].'-'.$custom_date_array[0].'-'.$custom_date_array[2];

            if($key == 0){
                $custom_obj['dep'] = $bill_order_dig->department_nomenclature;
                $custom_obj['id_dep'] = $bill_order_dig->id_department;
                $custom_obj['type'] = $bill_order_dig->channel_nomenclature;
                $custom_obj['id_type'] = $bill_order_dig->id_channel;
                $custom_obj_old['period'] = $date_from_custom_old.' a '.$date_to_custom_old;
                $custom_obj_old['amount'] = $bill_order_dig->amount;
                $custom_obj_new['period'] = $date_from.' a '.$date_to;
                foreach($array_dates as $key_date => $date){
                    if($date['last_date_custom'] >= $custom_date && $date['first_date_custom'] <= $custom_date){
                        $custom_obj_new['amounts'][] = round($bill_order_dig->amount, 2);
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
                    if($bill_order_custom['dep'] == $bill_order_dig->department_nomenclature && $bill_order_custom['type'] == $bill_order_dig->channel_nomenclature){
                        $exist = true;
                        $position = $key_array_bills_orders_custom;
                    }
                }

                if(!$exist){
                    $custom_obj['dep'] = $bill_order_dig->department_nomenclature;
                    $custom_obj['id_dep'] = $bill_order_dig->id_department;
                    $custom_obj['type'] = $bill_order_dig->channel_nomenclature;
                    $custom_obj['id_type'] = $bill_order_dig->id_channel;
                    $custom_obj_old['period'] = $date_from_custom_old.' a '.$date_to_custom_old;
                    $custom_obj_old['amount'] = $bill_order_dig->amount;
                    $custom_obj_new['period'] = $date_from.' a '.$date_to;
                    foreach($array_dates as $key_date => $date){
                        if($date['last_date_custom'] >= $custom_date && $date['first_date_custom'] <= $custom_date){
                            $custom_obj_new['amounts'][] = round($bill_order_dig->amount, 2);
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
                    $custom_obj_old['amount'] += $bill_order_dig->amount;
                    foreach($array_dates as $key_date => $date){
                        if($date['last_date_custom'] >= $custom_date && $date['first_date_custom'] <= $custom_date){
                            $array_bills_orders_custom[$position]['new']['amounts'][$key_date] += round($bill_order_dig->amount, 2);
                        }
                    }
                }
            }
        }
        
        /*foreach($array_bills_orders_print as $bill_order_print){
            $custom_obj['dep'] = $bill_order_print->department_nomenclature;
            $custom_obj['type'] = $bill_order_print->channel_nomenclature;
            $custom_obj_old['period'] = $date_to.' a '.$date_from;
            $custom_obj_new['period'] = $date_to.' a '.$date_from;
            $custom_obj_diference['period'] = 'Diferencia%';
            $custom_obj['old'] = $custom_obj_old;
            $custom_obj['new'] = $custom_obj_new;
            $array_bills_orders_custom[] = $custom_obj;
        }
        foreach($array_bills_orders_others as $bill_order_others){
            $custom_obj['dep'] = $bill_order_others->department_nomenclature;
            $custom_obj['type'] = 'OTROS';
            $custom_obj_old['period'] = $date_to.' a '.$date_from;
            $custom_obj_new['period'] = $date_to.' a '.$date_from;
            $custom_obj_diference['period'] = 'Diferencia%';
            $custom_obj['old'] = $custom_obj_old;
            $custom_obj['new'] = $custom_obj_new;
            $array_bills_orders_custom[] = $custom_obj;
        }*/

        //Calculamos los totales de las cantidades de $array_bills_orders_custom
        foreach($array_bills_orders_custom as $key_array_bills_orders_custom => $bill_order_custom){
            $array_amounts_new = $bill_order_custom['new']['amounts'];
            $total = 0;
            foreach($array_amounts_new as $amount_new){
                $total += $amount_new;
            }
            
            $array_bills_orders_custom[$key_array_bills_orders_custom]['new']['amounts'][] = $total;
        }

        $response['code'] = 1000;
        $response['array_dates'] = $array_dates;
        $response['array_bills_orders_custom'] = $array_bills_orders_custom;
        return response()->json($response);
    }

    //Generar array de fechas
    function generateDateArray($num_months, $date_from_custom){
        $array_dates = array();
        for($i=0; $i<=$num_months; $i++){
            $time = strtotime($date_from_custom);
            if($i == 0){
                $newformat = date('M-y',$time);
                $first_newformat_custom = date('m-d-Y', strtotime("+".$i." months", $time));
                $last_newformat_custom = date('m-t-Y', strtotime("+".$i." months", $time));
                $date_obj['date'] = '*'.$newformat;
                $date_obj['first_date_custom'] = $first_newformat_custom;
                $date_obj['last_date_custom'] = $last_newformat_custom;
                $array_dates[] = $date_obj;

            }else if($num_months == $i){
                $newformat = date('M-y', strtotime("+".$i." months", $time));
                $first_newformat_custom = date('m-d-Y', strtotime("+".$i." months", $time));
                $last_newformat_custom = date('m-t-Y', strtotime("+".$i." months", $time));
                $date_obj['date'] = '*'.$newformat;
                $date_obj['first_date_custom'] = $first_newformat_custom;
                $date_obj['last_date_custom'] = $last_newformat_custom;
                $array_dates[] = $date_obj;
            }else{
                $newformat = date('M-y', strtotime("+".$i." months", $time));
                $first_newformat_custom = date('m-d-Y', strtotime("+".$i." months", $time));
                $last_newformat_custom = date('m-t-Y', strtotime("+".$i." months", $time));
                $date_obj['date'] = $newformat;
                $date_obj['first_date_custom'] = $first_newformat_custom;
                $date_obj['last_date_custom'] = $last_newformat_custom;
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
}
