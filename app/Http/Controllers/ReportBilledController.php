<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillOrder; 
use App\Models\Section; 
use App\Models\Channel; 
use App\Models\ProposalBill;
use App\Models\ServiceBill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportBilledController extends Controller
{
    //Listado de facturas según el filtro
    function reportsList(Request $request){
        $select_department = $request->get('select_department');
        $select_section = $request->get('select_section');
        $select_channel = $request->get('select_channel');
        $select_consultant = $request->get('select_consultant');
        $select_order = $request->get('select_order');
        $select_compare = 1; //Es un año sí o sí
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

        //Canales DIG Y PRINT
        $array_bills_orders_dig = BillOrder::select('proposals.id_user', 'bills_orders.*', 'departments.nomenclature as department_nomenclature', 'departments.name as department_name', 'departments.id as id_department', 'sections.nomenclature as section_nomenclature', 'channels.nomenclature as channel_nomenclature', 'projects.nomenclature as project_nomenclature')
                                        ->leftJoin('orders', 'orders.id', 'bills_orders.id_order')
                                        ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')
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

        /*$array_bills_orders_print = BillOrder::select('bills_orders.*', 'departments.nomenclature as department_nomenclature', 'departments.name as department_name', 'departments.id as id_department', 'proposals.id as id_proposal', 'channels.nomenclature as channel_nomenclature')
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
                                    
        $array_bills_orders_others = BillOrder::select('bills_orders.*', 'departments.nomenclature as department_nomenclature', 'departments.name as department_name', 'departments.id as id_department', 'proposals.id as id_proposal', 'channels.nomenclature as channel_nomenclature')
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
                                        ->leftJoin('channels', 'channels.id', 'projects.id_channel');*/
                    
        //Filtro departamente
        if($select_department != '0'){
            $array_bills_orders_dig = $array_bills_orders_dig->where('proposals.id_department', $select_department);
            /*$array_bills_orders_print = $array_bills_orders_print->where('proposals.id_department', $select_department);
            $array_bills_orders_others = $array_bills_orders_others->where('proposals.id_department', $select_department);*/
        }

        //Filtro sección
        if($select_section != '0'){
            $array_bills_orders_dig = $array_bills_orders_dig->where('sections.id', $select_section);
            /*$array_bills_orders_print = $array_bills_orders_print->where('proposals.id_department', $select_department);
            $array_bills_orders_others = $array_bills_orders_others->where('proposals.id_department', $select_department);*/
        }

        //Filtro canal
        if($select_channel != '0'){
            $array_bills_orders_dig = $array_bills_orders_dig->where('channels.id', $select_channel);
            /*$array_bills_orders_print = $array_bills_orders_print->where('proposals.id_department', $select_department);
            $array_bills_orders_others = $array_bills_orders_others->where('proposals.id_department', $select_department);*/
        }

        //Filtro consultor
        if($select_consultant != '0'){
            $array_bills_orders_dig = $array_bills_orders_dig->where('contacts.id', $select_consultant);
            /*$array_bills_orders_print = $array_bills_orders_print->where('contacts.id', $select_consultant);
            $array_bills_orders_others = $array_bills_orders_others->where('contacts.id', $select_consultant);*/
        }

        //Filtro firmada o editando
        if($select_order == '1'){
            $array_bills_orders_dig = $array_bills_orders_dig->where('bills_orders.id_sage', '<>', null)->where('bills_orders.receipt_order_sage', '<>', null);
            /*$array_bills_orders_print = $array_bills_orders_print->where('bills_orders.id_sage', '<>', null)->where('bills_orders.receipt_order_sage', '<>', null);
            $array_bills_orders_others = $array_bills_orders_others->where('bills_orders.id_sage', '<>', null)->where('bills_orders.receipt_order_sage', '<>', null);*/
        }

        if($select_order == '2'){
            $array_bills_orders_dig = $array_bills_orders_dig->where('bills_orders.id_sage', null)->where('bills_orders.receipt_order_sage', null);
            /*$array_bills_orders_print = $array_bills_orders_print->where('bills_orders.id_sage', null)->where('bills_orders.receipt_order_sage', null);
            $array_bills_orders_others = $array_bills_orders_others->where('bills_orders.id_sage', null)->where('bills_orders.receipt_order_sage', null);*/
        }

        //Facturas DIG
        $array_bills_orders_dig = $array_bills_orders_dig//->where('channels.nomenclature', 'DIG')
                                                //->groupBy('bills_orders.id')
                                                ->get();

        error_log('array_bills_orders_dig: '.$array_bills_orders_dig);
        //error_log(count($array_bills_orders_dig));
        //error_log('array_bills_orders_dig: '.$array_bills_orders_dig);s

        //Creamos el objeto customizado
        $array_bills_orders_custom = array();
        
        //Recorremos el objeto DIG
        foreach($array_bills_orders_dig as $key => $bill_order_dig){
            $custom_date_array = explode("-", $bill_order_dig->date);
            $custom_date = $custom_date_array[1].'-'.$custom_date_array[0].'-'.$custom_date_array[2];
            $custom_obj = null;
            $custom_obj_new = null;
            $custom_obj_old = null;

            if($key == 0){
                //Consultamos el departamento, la sección y el canal de la factura
                //$service_bill = ServiceBill::where('id_bill', $bill_order_dig->id)->first()

                $custom_obj['dep'] = $bill_order_dig->department_nomenclature;
                $custom_obj['dep_name'] = $bill_order_dig->department_name;
                $custom_obj['id_dep'] = $bill_order_dig->id_department;
                $custom_obj['type'] = $bill_order_dig->channel_nomenclature;
                $custom_obj['id_type'] = $bill_order_dig->id_channel;
                $custom_obj['sec_name'] = $bill_order_dig->section_nomenclature;
                $custom_obj['pro_name'] = $bill_order_dig->project_nomenclature;
                
                $custom_obj_old['period'] = 'Hace 1 año';
                foreach($array_dates_old as $key_date => $date){
                    if(strtotime($date['last_date_custom2']) >= strtotime($bill_order_dig->date) && strtotime($date['first_date_custom2']) <= strtotime($bill_order_dig->date)){
                    //if(strtotime($date['last_date_custom2']) >= strtotime($custom_date) && strtotime($date['first_date_custom2']) <= strtotime($custom_date)){
                        $custom_obj_old['amounts'][] = round($bill_order_dig->amount, 2);
                    }else{
                        $custom_obj_old['amounts'][] = 0;
                    }
                }
                $custom_obj_new['period'] = 'Selección';
                foreach($array_dates as $key_date => $date){
                    if(strtotime($date['last_date_custom2']) >= strtotime($bill_order_dig->date) && strtotime($date['first_date_custom2']) <= strtotime($bill_order_dig->date)){
                    //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
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
                    if($bill_order_custom['dep'] == $bill_order_dig->department_nomenclature && $bill_order_custom['type'] == $bill_order_dig->channel_nomenclature && $bill_order_custom['pro_name'] == $bill_order_dig->project_nomenclature){
                        $exist = true;
                        $position = $key_array_bills_orders_custom;
                    }
                }

                if(!$exist){
                    error_log($bill_order_custom['pro_name']);
                    $custom_obj['dep'] = $bill_order_dig->department_nomenclature;
                    $custom_obj['dep_name'] = $bill_order_dig->department_name;
                    $custom_obj['id_dep'] = $bill_order_dig->id_department;
                    $custom_obj['type'] = $bill_order_dig->channel_nomenclature;
                    $custom_obj['id_type'] = $bill_order_dig->id_channel;
                    $custom_obj['sec_name'] = $bill_order_dig->section_nomenclature;
                    $custom_obj['pro_name'] = $bill_order_dig->project_nomenclature;

                    $custom_obj_old['period'] ='Hace 1 año';
                    foreach($array_dates_old as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($bill_order_dig->date) && strtotime($date['first_date_custom2']) <= strtotime($bill_order_dig->date)){
                            $custom_obj_old['amounts'][] = round($bill_order_dig->amount, 2);
                        }else{
                            $custom_obj_old['amounts'][] = 0;
                        }
                    }

                    $custom_obj_new['period'] = 'Selección';
                    foreach($array_dates as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($bill_order_dig->date) && strtotime($date['first_date_custom2']) <= strtotime($bill_order_dig->date)){
                        //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
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
                    foreach($array_dates_old as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($bill_order_dig->date) && strtotime($date['first_date_custom2']) <= strtotime($bill_order_dig->date)){
                            $array_bills_orders_custom[$position]['old']['amounts'][$key_date] += round($bill_order_dig->amount, 2);
                        }
                    }
                    foreach($array_dates as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($bill_order_dig->date) && strtotime($date['first_date_custom2']) <= strtotime($bill_order_dig->date)){
                        //if($date['last_date_custom'] >= $custom_date && $date['first_date_custom'] <= $custom_date){
                            $array_bills_orders_custom[$position]['new']['amounts'][$key_date] += round($bill_order_dig->amount, 2);
                        }
                    }
                }
            }
        }

        //error_log(print_r($array_bills_orders_custom, true));
        
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
                    $custom_array_type_bills_orders_custom[$bill_order_custom['pro_name']][] = $bill_order_custom;
                }
            }
        }

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
                    $custom_obj['new']['period'] = 'Selección';
                    $custom_obj['new']['amounts'] = $amounts_new;
                    $custom_obj['old']['period'] = 'Hace 1 año';
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
                    $custom_obj['diference']['period'] = 'Diferencia%';
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
                    $custom_obj['new']['period'] = 'Selección';
                    $custom_obj['new']['amounts'] = $amounts_new;
                    $custom_obj['old']['period'] = 'Hace 1 año';
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

                    if($cont_total == 0){
                        $custom_obj_total['dep'] = 'TOTAL';
                        $custom_obj_total['type_obj'] = 4;
                        $custom_obj_total['new']['period'] = 'Selección';
                        
                        $custom_obj_total['old']['period'] = 'Hace 1 año';
                        
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

        $response['code'] = 1000;
        $response['array_dates'] = $array_dates;
        $response['array_bills_orders_custom'] = $array_bills_orders_custom_aux;

        //error_log('array_bills_orders_custom: '.print_r($array_bills_orders_custom, true));

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
}
