<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillOrder;
use App\Models\UserObjetive;
use App\Models\Department;
use App\Models\ServiceBillOrder;

class ReportGoalController extends Controller
{
    //Listado de facturas según el filtro Old
    function reportsListOld(Request $request){
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
        $date_from = '01-01-2023';
        $date_to = '31-12-2023';
        $num_months = $this->calculateMonthsNumber($date_from, $date_to);

        $date_from_array = explode("-", $date_from);
        $date_from_custom = $date_from_array[1].'-01-'.$date_from_array[2];
        $date_from_custom_old_time = strtotime($date_from);
        $date_from_custom_old = date('d-m-Y', strtotime("-".$select_compare." year", $date_from_custom_old_time));

        $date_to_array = explode("-", $date_to);
        $date_to_custom = $date_to_array[1].'-01-'.$date_to_array[2];
        $date_to_custom_old_time = strtotime($date_to);
        $date_to_custom_old = date('d-m-Y', strtotime("-".$select_compare." year", $date_to_custom_old_time));

        //Generamos los arrays de fechas
        $array_dates = $this->generateDateArray($num_months, $date_from_custom, 1, false);
        $array_dates_old = $this->generateDateArray($num_months, $date_from_custom_old, 2, false);

        //Sacar unicamente los usuario que tenemos que mostrar en la tabla con susdepartamentos correspondientes 

        //Cantidades facturadas
        $array_bills_orders_dig = BillOrder::select('bills_orders.*', 'proposals.id_user', 'departments.nomenclature as department_nomenclature', 'departments.name as department_name', 'departments.id as id_department', 'sections.nomenclature as section_nomenclature', 'channels.nomenclature as channel_nomenclature', 'projects.nomenclature as project_nomenclature')
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
                                        ->leftJoin('departments', 'departments.id', 'sections.id_department')
                                        ->leftJoin('users_objetives', 'users_objetives.id_user', 'proposals.id_user')
                                        ->where('users_objetives.id', '<>', null)
                                        ->whereIn('channels.nomenclature', ['DIG', 'PRINT', 'EVE']);

        if(isset($select_consultant) && !empty($select_consultant)){
            $array_bills_orders_dig = $array_bills_orders_dig->where('proposals.id_user', $select_consultant);
        }

        $array_bills_orders_dig = $array_bills_orders_dig->groupBy('bills_orders.id')->get();
        error_log($array_bills_orders_dig);

        //Creamos el objeto customizado
        $array_bills_orders_custom = array();

        foreach($array_bills_orders_dig as $key => $bill_order_dig){
            $custom_date_array = explode("-", $bill_order_dig->date);
            $custom_date = $custom_date_array[1].'-'.$custom_date_array[0].'-'.$custom_date_array[2];
            $custom_obj = null;
            $custom_obj_men = null;
            $custom_fac_men = null;
            $custom_cum_men = null;
            $custom_obj_total = null;
            $custom_fac_total = null;
            $custom_cum_total = null;

            if($key == 0){
                //Consultamos el departamento, la sección y el canal de la factura
                $custom_obj['dep'] = $bill_order_dig->department_nomenclature;
                $custom_obj['dep_name'] = $bill_order_dig->department_name;
                $custom_obj['id_dep'] = $bill_order_dig->id_department;
                $custom_obj['type'] = $bill_order_dig->channel_nomenclature;
                $custom_obj['id_type'] = $bill_order_dig->id_channel;
                $custom_obj['sec_name'] = $bill_order_dig->section_nomenclature;
                $custom_obj['pro_name'] = $bill_order_dig->project_nomenclature;
                
                $custom_obj_men['period'] = 'Obj. mensual';
                $custom_fac_men['period'] = 'Fac. mensual';
                $custom_cum_men['period'] = 'Cum. mensual%';

                $custom_obj_total['period'] = 'Obj. acumulado';
                $custom_fac_total['period'] = 'Fac. acumulado';
                $custom_cum_total['period'] = 'Cum. acumulado%';

                //Consultamos el objetivo mensual del canal para este usuario
                $user_objetive = UserObjetive::where('id_user', $bill_order_dig->id_user)->where('year', 2023)->first();

                //Obj. mensual
                $trim = 0;
                $total = 0;
                foreach($array_dates as $key_date => $date){
                    $custom_obj_men['amounts'][] = round(($bill_order_dig->obj_print + $bill_order_dig->obj_dig + $bill_order_dig->obj_eve) / 12, 2);
                    $trim += round(($bill_order_dig->obj_print + $bill_order_dig->obj_dig + $bill_order_dig->obj_eve) / 12, 2);


                    if($key_date == 2 || $key_date == 5 || $key_date == 8 || $key_date == 11){
                        $custom_obj_men['amounts'][] = $trim;
                        $trim = 0;
                    }

                    $total += round(($bill_order_dig->obj_print + $bill_order_dig->obj_dig + $bill_order_dig->obj_eve) / 12, 2);
                }
                $custom_obj_men['amounts'][] = ($bill_order_dig->obj_print + $bill_order_dig->obj_dig + $bill_order_dig->obj_eve);

                //Fac. mensual
                $trim = 0;
                $total = 0;
                foreach($array_dates as $key_date => $date){
                    if(strtotime($date['last_date_custom2']) >= strtotime($bill_order_dig->date) && strtotime($date['first_date_custom2']) <= strtotime($bill_order_dig->date)){
                        $custom_fac_men['amounts'][] = round($bill_order_dig->amount, 2);
                        $trim += round($bill_order_dig->amount, 2);
                        $total += round($bill_order_dig->amount, 2);

                    }else{
                        $custom_fac_men['amounts'][] = 0;
                    }
                    if($key_date == 2 || $key_date == 5 || $key_date == 8 || $key_date == 11){
                        $custom_fac_men['amounts'][] = $trim;
                        $trim = 0;
                    }
                    
                }
                $custom_fac_men['amounts'][] = $total;

                //Cum. mensual
                $trim = 0;
                foreach($custom_fac_men['amounts'] as $key_date => $date){
                    if(isset($custom_obj_men['amounts'][$key_date]) && isset($custom_fac_men['amounts'][$key_date])){
                        if($custom_obj_men['amounts'][$key_date] > 0 && $custom_fac_men['amounts'][$key_date] > 0){
                            $cum_total_obj['amount'] = round(($custom_fac_men['amounts'][$key_date] * 100) / $custom_obj_men['amounts'][$key_date], 2);
                            $cum_total_obj['situation'] = '+';
                            if($custom_obj_men['amounts'][$key_date] > $custom_fac_men['amounts'][$key_date]){
                                $cum_total_obj['situation'] = '-';
                            }
                            $custom_cum_men['amounts'][] = $cum_total_obj;

                        }else{
                            $cum_total_obj['amount'] = '-';
                            $cum_total_obj['situation'] = '';
                            $custom_cum_men['amounts'][] = $cum_total_obj;
                        }
                    }else{
                        $cum_total_obj['amount'] = '-';
                        $cum_total_obj['situation'] = '';
                        $custom_cum_men['amounts'][] = $cum_total_obj;
                    }
                }

                //Obj. acumulado
                $trim = 0;
                $total = 0;
                foreach($array_dates as $key_date => $date){
                    /*if(strtotime($date['last_date_custom2']) >= strtotime($bill_order_dig->date) && strtotime($date['first_date_custom2']) <= strtotime($bill_order_dig->date)){
                    //if(strtotime($date['last_date_custom']) >= strtotime($custom_date) && strtotime($date['first_date_custom']) <= strtotime($custom_date)){
                        $custom_obj_total['amounts'][] = round($bill_order_dig->amount, 2);
                        $trim += round($bill_order_dig->amount, 2);

                    }else{
                        $custom_obj_total['amounts'][] = 0;
                    }*/

                    $total += round(($bill_order_dig->obj_print + $bill_order_dig->obj_dig + $bill_order_dig->obj_eve) / 12, 2);
                    $custom_obj_total['amounts'][] = round($total, 2);
                    

                    if($key_date == 2 || $key_date == 5 || $key_date == 8 || $key_date == 11){
                        $custom_obj_total['amounts'][] = round($total, 2);
                        //$trim = 0;
                    }
                }
                $custom_obj_total['amounts'][] = round($total, 2);

                $custom_obj['obj_men'] = $custom_obj_men;
                $custom_obj['fac_men'] = $custom_fac_men;
                $custom_obj['cum_men'] = $custom_cum_men;
                $custom_obj['obj_total'] = $custom_obj_total;
                $custom_obj['fac_total'] = $custom_fac_total;
                $custom_obj['cum_total'] = $custom_cum_total;
                $custom_obj['type_obj'] = 1;
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
                    //Consultamos el departamento, la sección y el canal de la factura
                    $custom_obj['dep'] = $bill_order_dig->department_nomenclature;
                    $custom_obj['dep_name'] = $bill_order_dig->department_name;
                    $custom_obj['id_dep'] = $bill_order_dig->id_department;
                    $custom_obj['type'] = $bill_order_dig->channel_nomenclature;
                    $custom_obj['id_type'] = $bill_order_dig->id_channel;
                    $custom_obj['sec_name'] = $bill_order_dig->section_nomenclature;
                    $custom_obj['pro_name'] = $bill_order_dig->project_nomenclature;
                    
                    $custom_obj_men['period'] = 'Obj. mensual';
                    $custom_fac_men['period'] = 'Fac. mensual';
                    $custom_cum_men['period'] = 'Cum. mensual%';

                    $custom_obj_total['period'] = 'Obj. acumulado';
                    $custom_fac_total['period'] = 'Fac. acumulado';
                    $custom_cum_total['period'] = 'Cum. acumulado%';

                    //Obj. mensual
                    $trim = 0;
                    $total = 0;
                    foreach($array_dates as $key_date => $date){
                        $custom_obj_men['amounts'][] = round(($bill_order_dig->obj_print + $bill_order_dig->obj_dig + $bill_order_dig->obj_eve) / 12, 2);
                        $trim += round(($bill_order_dig->obj_print + $bill_order_dig->obj_dig + $bill_order_dig->obj_eve) / 12, 2);


                        if($key_date == 2 || $key_date == 5 || $key_date == 8 || $key_date == 11){
                            $custom_obj_men['amounts'][] = $trim;
                            $trim = 0;
                        }

                        $total += round(($bill_order_dig->obj_print + $bill_order_dig->obj_dig + $bill_order_dig->obj_eve) / 12, 2);
                    }
                    $custom_obj_men['amounts'][] = ($bill_order_dig->obj_print + $bill_order_dig->obj_dig + $bill_order_dig->obj_eve);

                    //Fac. mensual
                    $trim = 0;
                    $total = 0;
                    foreach($array_dates as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($bill_order_dig->date) && strtotime($date['first_date_custom2']) <= strtotime($bill_order_dig->date)){
                            $custom_fac_men['amounts'][] = round($bill_order_dig->amount, 2);
                            $trim += round($bill_order_dig->amount, 2);
                            $total += round($bill_order_dig->amount, 2);

                        }else{
                            $custom_fac_men['amounts'][] = 0;
                        }
                        if($key_date == 2 || $key_date == 5 || $key_date == 8 || $key_date == 11){
                            $custom_fac_men['amounts'][] = $trim;
                            $trim = 0;
                        }
                        
                    }
                    $custom_fac_men['amounts'][] = $total;

                    //Cum. mensual
                    $trim = 0;
                    foreach($custom_fac_men['amounts'] as $key_date => $date){
                        if(isset($custom_obj_men['amounts'][$key_date]) && isset($custom_fac_men['amounts'][$key_date])){
                            if($custom_obj_men['amounts'][$key_date] > 0 && $custom_fac_men['amounts'][$key_date] > 0){
                                $cum_total_obj['amount'] = round(($custom_fac_men['amounts'][$key_date] * 100) / $custom_obj_men['amounts'][$key_date], 2);
                                $cum_total_obj['situation'] = '+';
                                if($custom_obj_men['amounts'][$key_date] > $custom_fac_men['amounts'][$key_date]){
                                    $cum_total_obj['situation'] = '-';
                                }
                                $custom_cum_men['amounts'][] = $cum_total_obj;

                            }else{
                                $cum_total_obj['amount'] = '-';
                                $cum_total_obj['situation'] = '';
                                $custom_cum_men['amounts'][] = $cum_total_obj;
                            }
                        }else{
                            $cum_total_obj['amount'] = '-';
                            $cum_total_obj['situation'] = '';
                            $custom_cum_men['amounts'][] = $cum_total_obj;
                        }
                    }

                    //Obj. acumulado
                    $trim = 0;
                    $total = 0;
                    foreach($array_dates as $key_date => $date){
                        $total += round(($bill_order_dig->obj_print + $bill_order_dig->obj_dig + $bill_order_dig->obj_eve) / 12, 2);
                        $custom_obj_total['amounts'][] = round($total, 2);
                        

                        if($key_date == 2 || $key_date == 5 || $key_date == 8 || $key_date == 11){
                            $custom_obj_total['amounts'][] = round($total, 2);
                        }
                    }
                    $custom_obj_total['amounts'][] = round($total, 2);

                    $custom_obj['obj_men'] = $custom_obj_men;
                    $custom_obj['fac_men'] = $custom_fac_men;
                    $custom_obj['cum_men'] = $custom_cum_men;
                    $custom_obj['obj_total'] = $custom_obj_total;
                    $custom_obj['fac_total'] = $custom_fac_total;
                    $custom_obj['cum_total'] = $custom_cum_total;
                    $custom_obj['type_obj'] = 1;
                    $array_bills_orders_custom[] = $custom_obj;

                }

                if($exist){
                    //Fac. mensual
                    $trim = 0;
                    $total = 0;
                    foreach($array_dates as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($bill_order_dig->date) && strtotime($date['first_date_custom2']) <= strtotime($bill_order_dig->date)){
                            $array_bills_orders_custom[$position]['fac_men']['amounts'][$key_date] += round($bill_order_dig->amount, 2);
                            $trim += round($bill_order_dig->amount, 2);
                            $total += round($bill_order_dig->amount, 2);
                        }

                        if($key_date == 2 || $key_date == 5 || $key_date == 8 || $key_date == 11){
                            $array_bills_orders_custom[$position]['fac_men']['amounts'][$key_date + 1] += round($trim, 2);
                            $trim = 0;
                        }
                    }
                    $array_bills_orders_custom[$position]['fac_men']['amounts'][count($array_bills_orders_custom[$position]['fac_men']['amounts']) - 1] += $total;

                    //Cum. mensual
                    $trim = 0;
                    foreach($array_bills_orders_custom[$position]['fac_men']['amounts'] as $key_date => $date){
                        if(isset($array_bills_orders_custom[$position]['obj_men']['amounts'][$key_date]) && isset($array_bills_orders_custom[$position]['fac_men']['amounts'][$key_date])){
                            if($array_bills_orders_custom[$position]['obj_men']['amounts'][$key_date] > 0 && $array_bills_orders_custom[$position]['fac_men']['amounts'][$key_date] > 0){
                                $cum_total_obj['amount'] = round(($array_bills_orders_custom[$position]['fac_men']['amounts'][$key_date] * 100) / $array_bills_orders_custom[$position]['obj_men']['amounts'][$key_date], 2);
                                $cum_total_obj['situation'] = '+';
                                if($array_bills_orders_custom[$position]['obj_men']['amounts'][$key_date] > $array_bills_orders_custom[$position]['fac_men']['amounts'][$key_date]){
                                    $cum_total_obj['situation'] = '-';
                                }
                                $array_bills_orders_custom[$position]['cum_men']['amounts'][$key_date] = $cum_total_obj;

                            }else{
                                $cum_total_obj['amount'] = '-';
                                $cum_total_obj['situation'] = '';
                                $array_bills_orders_custom[$position]['cum_men']['amounts'][$key_date] = $cum_total_obj;
                            }
                        }else{
                            $cum_total_obj['amount'] = '-';
                            $cum_total_obj['situation'] = '';
                            $array_bills_orders_custom[$position]['cum_men']['amounts'][$key_date] = $cum_total_obj;
                        }
                    }
                }
            }
        }

        //Fac. acumulado
        $total = 0;
        $custom_array_fac_total = array();
        $is_empty = false;
        foreach($array_bills_orders_custom as $key_bill_order => $bill_order){
            $is_empty = true;
            $custom_array_fac_total = array();
            $aux = 0;
            foreach($bill_order['fac_men']['amounts'] as $key_amount => $amount){
                if($key_amount == 3 || $key_amount == 7 || $key_amount == 11 || $key_amount == 15 || $key_amount == 16){
                    if($key_amount != 16){
                        $custom_array_fac_total[] = $aux;
                    }

                }else{
                    $aux += $amount;
                    $custom_array_fac_total[] = $aux;
                }
            }
            $is_empty = false;
            $custom_array_fac_total[] = $aux;
            $array_bills_orders_custom[$key_bill_order]['fac_total']['amounts'] = $custom_array_fac_total;

            //Cum. acumulado
            $trim = 0;
            $custom_array_cum_total = array();
            foreach($array_bills_orders_custom[$key_bill_order]['fac_total']['amounts'] as $key_date => $date){
                if(isset($array_bills_orders_custom[$key_bill_order]['obj_total']['amounts'][$key_date]) && isset($array_bills_orders_custom[$key_bill_order]['fac_total']['amounts'][$key_date])){
                    if($array_bills_orders_custom[$key_bill_order]['obj_total']['amounts'][$key_date] > 0 && $array_bills_orders_custom[$key_bill_order]['fac_total']['amounts'][$key_date] > 0){
                        $cum_total_obj['amount'] = round(($array_bills_orders_custom[$key_bill_order]['fac_total']['amounts'][$key_date] * 100) / $array_bills_orders_custom[$key_bill_order]['obj_total']['amounts'][$key_date], 2);
                        $cum_total_obj['situation'] = '+';
                        if($array_bills_orders_custom[$key_bill_order]['obj_total']['amounts'][$key_date] > $array_bills_orders_custom[$key_bill_order]['fac_total']['amounts'][$key_date]){
                            $cum_total_obj['situation'] = '-';
                        }
                        $custom_array_cum_total[] = $cum_total_obj;

                    }else{
                        $cum_total_obj['amount'] = '-';
                        $cum_total_obj['situation'] = '';
                        $custom_array_cum_total[] = $cum_total_obj;
                    }
                }else{
                    $cum_total_obj['amount'] = '-';
                    $cum_total_obj['situation'] = '';
                    $custom_array_cum_total[] = $cum_total_obj;
                }
            }
            $array_bills_orders_custom[$key_bill_order]['cum_total']['amounts'] = $custom_array_cum_total;

        }
        $custom_fac_total['amounts'][] = round($total, 2);

        $array_dates_finish = $this->generateDateArray($num_months, $date_from_custom, 1, true);

        $response['code'] = 1000;
        $response['array_dates'] = $array_dates_finish;
        //$response['array_bills_orders_custom'] = $array_bills_orders_custom_aux;
        $response['array_bills_orders_custom'] = $array_bills_orders_custom;

        return response()->json($response);

    }

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
        $date_from = '01-01-2023';
        $date_to = '31-12-2023';
        $num_months = $this->calculateMonthsNumber($date_from, $date_to);

        $date_from_array = explode("-", $date_from);
        $date_from_custom = $date_from_array[1].'-01-'.$date_from_array[2];
        $date_from_custom_old_time = strtotime($date_from);
        $date_from_custom_old = date('d-m-Y', strtotime("-".$select_compare." year", $date_from_custom_old_time));

        $date_to_array = explode("-", $date_to);
        $date_to_custom = $date_to_array[1].'-01-'.$date_to_array[2];
        $date_to_custom_old_time = strtotime($date_to);
        $date_to_custom_old = date('d-m-Y', strtotime("-".$select_compare." year", $date_to_custom_old_time));

        //Generamos los arrays de fechas
        $array_dates = $this->generateDateArray($num_months, $date_from_custom, 1, false);
        $array_dates_old = $this->generateDateArray($num_months, $date_from_custom_old, 2, false);

        //Cantidades facturadas
        $array_users_objetives = UserObjetive::select('users_objetives.*', 'departments.nomenclature as department_nomenclature')
                                                ->leftJoin('proposals', 'proposals.id_user', 'users_objetives.id_user')
                                                ->leftJoin('departments', 'departments.id', 'users_objetives.id_department')
                                                ->where('proposals.id_user', '<>', null);

        if(isset($select_consultant) && !empty($select_consultant)){
            $array_users_objetives = $array_users_objetives->where('proposals.id_user', $select_consultant);
        }
        
        $array_users_objetives = $array_users_objetives->groupBy('users_objetives.id')
                                                        ->get();

        $array_bill_orders_custom_object = array();
        foreach($array_users_objetives as $user_objetive){
            $array_bill_orders = BillOrder::select('bills_orders.*')
                                        ->leftJoin('orders', 'orders.id', 'bills_orders.id_order')
                                        ->leftJoin('proposals', 'proposals.id', 'orders.id_proposal')
                                        ->where('proposals.id_user', $user_objetive->id_user)
                                        ->groupBy('bills_orders.id')
                                        ->get();

            foreach($array_bill_orders as $bill_order){
                //Consultamos el departamento, sección, canal y projeto al que pertence la factura
                $option_bill = ServiceBillOrder::select('departments.nomenclature as department_nomenclature', 'departments.name as department_name', 'departments.id as id_department', 
                                                'sections.nomenclature as section_nomenclature', 'channels.nomenclature as channel_nomenclature', 
                                                'projects.nomenclature as project_nomenclature')
                                                ->leftJoin('services', 'services.id', 'services_bills_orders.id_service')
                                                ->leftJoin('articles', 'articles.id', 'services.id_article')
                                                ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                                                ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                                                ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                                                ->leftJoin('channels', 'channels.id', 'projects.id_channel')
                                                ->leftJoin('sections', 'sections.id', 'channels.id_section')
                                                ->leftJoin('departments', 'departments.id', 'sections.id_department')
                                                ->whereIn('channels.nomenclature', ['DIG', 'PRINT', 'EVE'])
                                                ->where('id_bill_order', $bill_order->id)
                                                ->first();

                if($option_bill){
                    $bill_order['department_nomenclature'] = $option_bill['department_nomenclature'];
                    $bill_order['department_name'] = $option_bill['department_name'];
                    $bill_order['department_noid_departmentmenclature'] = $option_bill['id_department'];
                    $bill_order['section_nomenclature'] = $option_bill['section_nomenclature'];
                    $bill_order['channel_nomenclature'] = $option_bill['channel_nomenclature'];
                    $bill_order['project_nomenclature'] = $option_bill['project_nomenclature'];
                    $array_bill_orders_custom_object[] = $bill_order;
                }
            }
        }
  
        //Creamos el objeto customizado
        $array_bills_orders_custom = array();

        foreach($array_bill_orders_custom_object as $key => $bill_order){
            $custom_date_array = explode("-", $bill_order->date);
            $custom_date = $custom_date_array[1].'-'.$custom_date_array[0].'-'.$custom_date_array[2];
            $custom_obj = null;
            $custom_obj_men = null;
            $custom_fac_men = null;
            $custom_cum_men = null;
            $custom_obj_total = null;
            $custom_fac_total = null;
            $custom_cum_total = null;

            if($key == 0){
                //Consultamos el departamento, la sección y el canal de la factura
                $custom_obj['dep'] = $bill_order->department_nomenclature;
                $custom_obj['dep_name'] = $bill_order->department_name;
                $custom_obj['id_dep'] = $bill_order->id_department;
                $custom_obj['type'] = $bill_order->channel_nomenclature;
                $custom_obj['id_type'] = $bill_order->id_channel;
                $custom_obj['sec_name'] = $bill_order->section_nomenclature;
                $custom_obj['pro_name'] = $bill_order->project_nomenclature;
                $custom_obj['date'] = $bill_order->date;
                
                $custom_obj_men['period'] = 'Obj. mensual';
                $custom_fac_men['period'] = 'Fac. mensual';
                $custom_cum_men['period'] = 'Cum. mensual%';

                $custom_obj_total['period'] = 'Obj. acumulado';
                $custom_fac_total['period'] = 'Fac. acumulado';
                $custom_cum_total['period'] = 'Cum. acumulado%';

                //Consultamos el objetivo mensual del canal para este usuario
                $user_objetive = UserObjetive::where('id_user', $bill_order->id_user)->where('year', 2023)->first();

                //Obj. mensual
                $trim = 0;
                $total = 0;
                foreach($array_dates as $key_date => $date){
                    $custom_obj_men['amounts'][] = round(($bill_order->obj_print + $bill_order->obj_dig + $bill_order->obj_eve) / 12, 2);
                    $trim += round(($bill_order->obj_print + $bill_order->obj_dig + $bill_order->obj_eve) / 12, 2);


                    if($key_date == 2 || $key_date == 5 || $key_date == 8 || $key_date == 11){
                        $custom_obj_men['amounts'][] = $trim;
                        $trim = 0;
                    }

                    $total += round(($bill_order->obj_print + $bill_order->obj_dig + $bill_order->obj_eve) / 12, 2);
                }
                $custom_obj_men['amounts'][] = ($bill_order->obj_print + $bill_order->obj_dig + $bill_order->obj_eve);

                //Fac. mensual
                $trim = 0;
                $total = 0;
                foreach($array_dates as $key_date => $date){
                    /*error_log('=========================');
                    error_log('last_date_custom2: '.$date['last_date_custom2']);
                    error_log('bill_order->date: '.$bill_order->date);
                    error_log('first_date_custom2: '.$date['first_date_custom2']);
                    error_log('bill_order->date: '.$bill_order->date);*/
                    
                    if(strtotime($date['last_date_custom2']) >= strtotime($bill_order->date) && strtotime($date['first_date_custom2']) <= strtotime($bill_order->date)){
                        error_log('entro');
                        $custom_fac_men['amounts'][] = round($bill_order->amount, 2);
                        $trim += round($bill_order->amount, 2);
                        $total += round($bill_order->amount, 2);

                    }else{
                        $custom_fac_men['amounts'][] = 0;
                    }
                    if($key_date == 2 || $key_date == 5 || $key_date == 8 || $key_date == 11){
                        $custom_fac_men['amounts'][] = $trim;
                        $trim = 0;
                    }
                    //error_log('=========================');
                    
                }
                $custom_fac_men['amounts'][] = $total;
        
                //Cum. mensual
                $trim = 0;
                foreach($custom_fac_men['amounts'] as $key_date => $date){
                    if(isset($custom_obj_men['amounts'][$key_date]) && isset($custom_fac_men['amounts'][$key_date])){
                        if($custom_obj_men['amounts'][$key_date] > 0 && $custom_fac_men['amounts'][$key_date] > 0){
                            $cum_total_obj['amount'] = round(($custom_fac_men['amounts'][$key_date] * 100) / $custom_obj_men['amounts'][$key_date], 2);
                            $cum_total_obj['situation'] = '+';
                            if($custom_obj_men['amounts'][$key_date] > $custom_fac_men['amounts'][$key_date]){
                                $cum_total_obj['situation'] = '-';
                            }
                            $custom_cum_men['amounts'][] = $cum_total_obj;

                        }else{
                            $cum_total_obj['amount'] = '-';
                            $cum_total_obj['situation'] = '';
                            $custom_cum_men['amounts'][] = $cum_total_obj;
                        }
                    }else{
                        $cum_total_obj['amount'] = '-';
                        $cum_total_obj['situation'] = '';
                        $custom_cum_men['amounts'][] = $cum_total_obj;
                    }
                }

                //Obj. acumulado
                $trim = 0;
                $total = 0;
                foreach($array_dates as $key_date => $date){
                    $total += round(($bill_order->obj_print + $bill_order->obj_dig + $bill_order->obj_eve) / 12, 2);
                    $custom_obj_total['amounts'][] = round($total, 2);
                    

                    if($key_date == 2 || $key_date == 5 || $key_date == 8 || $key_date == 11){
                        $custom_obj_total['amounts'][] = round($total, 2);
                        //$trim = 0;
                    }
                }
                $custom_obj_total['amounts'][] = round($total, 2);

                $custom_obj['obj_men'] = $custom_obj_men;
                $custom_obj['fac_men'] = $custom_fac_men;

                $custom_obj['cum_men'] = $custom_cum_men;
                $custom_obj['obj_total'] = $custom_obj_total;
                $custom_obj['fac_total'] = $custom_fac_total;
                $custom_obj['cum_total'] = $custom_cum_total;
                $custom_obj['type_obj'] = 1;
                $array_bills_orders_custom[] = $custom_obj;

            }else{
                $exist = false;
                $position = 0;
                foreach($array_bills_orders_custom as $key_array_bills_orders_custom => $bill_order_custom){
                    if($bill_order_custom['dep'] == $bill_order->department_nomenclature && $bill_order_custom['type'] == $bill_order->channel_nomenclature && $bill_order_custom['pro_name'] == $bill_order->project_nomenclature){
                        $exist = true;
                        $position = $key_array_bills_orders_custom;
                    }
                }

                if(!$exist){
                    //Consultamos el departamento, la sección y el canal de la factura
                    $custom_obj['dep'] = $bill_order->department_nomenclature;
                    $custom_obj['dep_name'] = $bill_order->department_name;
                    $custom_obj['id_dep'] = $bill_order->id_department;
                    $custom_obj['type'] = $bill_order->channel_nomenclature;
                    $custom_obj['id_type'] = $bill_order->id_channel;
                    $custom_obj['sec_name'] = $bill_order->section_nomenclature;
                    $custom_obj['pro_name'] = $bill_order->project_nomenclature;
                    $custom_obj['date'] = $bill_order->date;
                    
                    $custom_obj_men['period'] = 'Obj. mensual';
                    $custom_fac_men['period'] = 'Fac. mensual';
                    $custom_cum_men['period'] = 'Cum. mensual%';

                    $custom_obj_total['period'] = 'Obj. acumulado';
                    $custom_fac_total['period'] = 'Fac. acumulado';
                    $custom_cum_total['period'] = 'Cum. acumulado%';

                    //Obj. mensual
                    $trim = 0;
                    $total = 0;
                    foreach($array_dates as $key_date => $date){
                        $custom_obj_men['amounts'][] = round(($bill_order->obj_print + $bill_order->obj_dig + $bill_order->obj_eve) / 12, 2);
                        $trim += round(($bill_order->obj_print + $bill_order->obj_dig + $bill_order->obj_eve) / 12, 2);


                        if($key_date == 2 || $key_date == 5 || $key_date == 8 || $key_date == 11){
                            $custom_obj_men['amounts'][] = $trim;
                            $trim = 0;
                        }

                        $total += round(($bill_order->obj_print + $bill_order->obj_dig + $bill_order->obj_eve) / 12, 2);
                    }
                    $custom_obj_men['amounts'][] = ($bill_order->obj_print + $bill_order->obj_dig + $bill_order->obj_eve);

                    //Fac. mensual
                    $trim = 0;
                    $total = 0;
                    foreach($array_dates as $key_date => $date){
                        if(strtotime($date['last_date_custom2']) >= strtotime($bill_order->date) && strtotime($date['first_date_custom2']) <= strtotime($bill_order->date)){
                            $custom_fac_men['amounts'][] = round($bill_order->amount, 2);
                            $trim += round($bill_order->amount, 2);
                            $total += round($bill_order->amount, 2);

                        }else{
                            $custom_fac_men['amounts'][] = 0;
                        }
                        if($key_date == 2 || $key_date == 5 || $key_date == 8 || $key_date == 11){
                            $custom_fac_men['amounts'][] = $trim;
                            $trim = 0;
                        }
                        
                    }
                    $custom_fac_men['amounts'][] = $total;

                    //Cum. mensual
                    $trim = 0;
                    foreach($custom_fac_men['amounts'] as $key_date => $date){
                        if(isset($custom_obj_men['amounts'][$key_date]) && isset($custom_fac_men['amounts'][$key_date])){
                            if($custom_obj_men['amounts'][$key_date] > 0 && $custom_fac_men['amounts'][$key_date] > 0){
                                $cum_total_obj['amount'] = round(($custom_fac_men['amounts'][$key_date] * 100) / $custom_obj_men['amounts'][$key_date], 2);
                                $cum_total_obj['situation'] = '+';
                                if($custom_obj_men['amounts'][$key_date] > $custom_fac_men['amounts'][$key_date]){
                                    $cum_total_obj['situation'] = '-';
                                }
                                $custom_cum_men['amounts'][] = $cum_total_obj;

                            }else{
                                $cum_total_obj['amount'] = '-';
                                $cum_total_obj['situation'] = '';
                                $custom_cum_men['amounts'][] = $cum_total_obj;
                            }
                        }else{
                            $cum_total_obj['amount'] = '-';
                            $cum_total_obj['situation'] = '';
                            $custom_cum_men['amounts'][] = $cum_total_obj;
                        }
                    }

                    //Obj. acumulado
                    $trim = 0;
                    $total = 0;
                    foreach($array_dates as $key_date => $date){
                        $total += round(($bill_order->obj_print + $bill_order->obj_dig + $bill_order->obj_eve) / 12, 2);
                        $custom_obj_total['amounts'][] = round($total, 2);
                        

                        if($key_date == 2 || $key_date == 5 || $key_date == 8 || $key_date == 11){
                            $custom_obj_total['amounts'][] = round($total, 2);
                        }
                    }
                    $custom_obj_total['amounts'][] = round($total, 2);

                    $custom_obj['obj_men'] = $custom_obj_men;
                    $custom_obj['fac_men'] = $custom_fac_men;
                    $custom_obj['cum_men'] = $custom_cum_men;
                    $custom_obj['obj_total'] = $custom_obj_total;
                    $custom_obj['fac_total'] = $custom_fac_total;
                    $custom_obj['cum_total'] = $custom_cum_total;
                    $custom_obj['type_obj'] = 1;
                    $array_bills_orders_custom[] = $custom_obj;

                }

                if($exist){
                    //Fac. mensual
                    $trim = 0;
                    $total = 0;
                    foreach($array_dates as $key_date => $date){
                        /*error_log('=========================');
                        error_log('last_date_custom2: '.$date['last_date_custom2']);
                        error_log('bill_order->date: '.$bill_order->date);
                        error_log('first_date_custom2: '.$date['first_date_custom2']);
                        error_log('bill_order->date: '.$bill_order->date);*/  

                        if(strtotime($date['last_date_custom2']) >= strtotime($bill_order->date) && strtotime($date['first_date_custom2']) <= strtotime($bill_order->date)){
                            $trim += round($bill_order->amount, 2);
                            $total += round($bill_order->amount, 2);
                            if($key_date == 0 || $key_date == 1 || $key_date == 2){
                                $array_bills_orders_custom[$position]['fac_men']['amounts'][$key_date] += round($bill_order->amount, 2);

                            }else{
                                $array_bills_orders_custom[$position]['fac_men']['amounts'][$key_date + 1] += round($bill_order->amount, 2);
                            }
                        }

                        if($key_date == 2 || $key_date == 5 || $key_date == 8 || $key_date == 11){
                            if($key_date == 2){
                                $array_bills_orders_custom[$position]['fac_men']['amounts'][3] += round($trim, 2);
                            }

                            if($key_date == 5){
                                $array_bills_orders_custom[$position]['fac_men']['amounts'][7] += round($trim, 2);
                            }

                            if($key_date == 8){
                                $array_bills_orders_custom[$position]['fac_men']['amounts'][11] += round($trim, 2);
                            }

                            if($key_date == 11){
                                $array_bills_orders_custom[$position]['fac_men']['amounts'][15] += round($trim, 2);
                            }
                            
                            $trim = 0;

                        }
                    }
                    $array_bills_orders_custom[$position]['fac_men']['amounts'][count($array_bills_orders_custom[$position]['fac_men']['amounts']) - 1] += $total;

                    //Cum. mensual
                    $trim = 0;
                    foreach($array_bills_orders_custom[$position]['fac_men']['amounts'] as $key_date => $date){
                        if(isset($array_bills_orders_custom[$position]['obj_men']['amounts'][$key_date]) && isset($array_bills_orders_custom[$position]['fac_men']['amounts'][$key_date])){
                            if($array_bills_orders_custom[$position]['obj_men']['amounts'][$key_date] > 0 && $array_bills_orders_custom[$position]['fac_men']['amounts'][$key_date] > 0){
                                $cum_total_obj['amount'] = round(($array_bills_orders_custom[$position]['fac_men']['amounts'][$key_date] * 100) / $array_bills_orders_custom[$position]['obj_men']['amounts'][$key_date], 2);
                                $cum_total_obj['situation'] = '+';
                                if($array_bills_orders_custom[$position]['obj_men']['amounts'][$key_date] > $array_bills_orders_custom[$position]['fac_men']['amounts'][$key_date]){
                                    $cum_total_obj['situation'] = '-';
                                }
                                $array_bills_orders_custom[$position]['cum_men']['amounts'][$key_date] = $cum_total_obj;

                            }else{
                                $cum_total_obj['amount'] = '-';
                                $cum_total_obj['situation'] = '';
                                $array_bills_orders_custom[$position]['cum_men']['amounts'][$key_date] = $cum_total_obj;
                            }
                        }else{
                            $cum_total_obj['amount'] = '-';
                            $cum_total_obj['situation'] = '';
                            $array_bills_orders_custom[$position]['cum_men']['amounts'][$key_date] = $cum_total_obj;
                        }
                    }
                }
            }
        }

        //Fac. acumulado
        $total = 0;
        $custom_array_fac_total = array();
        $is_empty = false;
        foreach($array_bills_orders_custom as $key_bill_order => $bill_order){
            $is_empty = true;
            $custom_array_fac_total = array();
            $aux = 0;
            foreach($bill_order['fac_men']['amounts'] as $key_amount => $amount){
                if($key_amount == 3 || $key_amount == 7 || $key_amount == 11 || $key_amount == 15 || $key_amount == 16){
                    if($key_amount != 16){
                        $custom_array_fac_total[] = $aux;
                    }

                }else{
                    $aux += $amount;
                    $custom_array_fac_total[] = $aux;
                }
            }
            $is_empty = false;
            $custom_array_fac_total[] = $aux;
            $array_bills_orders_custom[$key_bill_order]['fac_total']['amounts'] = $custom_array_fac_total;

            //Cum. acumulado
            $trim = 0;
            $custom_array_cum_total = array();
            foreach($array_bills_orders_custom[$key_bill_order]['fac_total']['amounts'] as $key_date => $date){
                if(isset($array_bills_orders_custom[$key_bill_order]['obj_total']['amounts'][$key_date]) && isset($array_bills_orders_custom[$key_bill_order]['fac_total']['amounts'][$key_date])){
                    if($array_bills_orders_custom[$key_bill_order]['obj_total']['amounts'][$key_date] > 0 && $array_bills_orders_custom[$key_bill_order]['fac_total']['amounts'][$key_date] > 0){
                        $cum_total_obj['amount'] = round(($array_bills_orders_custom[$key_bill_order]['fac_total']['amounts'][$key_date] * 100) / $array_bills_orders_custom[$key_bill_order]['obj_total']['amounts'][$key_date], 2);
                        $cum_total_obj['situation'] = '+';
                        if($array_bills_orders_custom[$key_bill_order]['obj_total']['amounts'][$key_date] > $array_bills_orders_custom[$key_bill_order]['fac_total']['amounts'][$key_date]){
                            $cum_total_obj['situation'] = '-';
                        }
                        $custom_array_cum_total[] = $cum_total_obj;

                    }else{
                        $cum_total_obj['amount'] = '-';
                        $cum_total_obj['situation'] = '';
                        $custom_array_cum_total[] = $cum_total_obj;
                    }
                }else{
                    $cum_total_obj['amount'] = '-';
                    $cum_total_obj['situation'] = '';
                    $custom_array_cum_total[] = $cum_total_obj;
                }
            }
            $array_bills_orders_custom[$key_bill_order]['cum_total']['amounts'] = $custom_array_cum_total;

        }

        //Rows por departamentos
        $array_dep_custom = array();
        foreach($array_bills_orders_custom as $key => $bill_order){
            if($key == 0){
                $custom_obj['dep_name'] = $bill_order['dep_name'];

                $custom_obj_men['period'] = 'Obj. mensual';
                $custom_fac_men['period'] = 'Fac. mensual';
                $custom_cum_men['period'] = 'Cum. mensual%';

                $custom_obj_total['period'] = 'Obj. acumulado';
                $custom_fac_total['period'] = 'Fac. acumulado';
                $custom_cum_total['period'] = 'Cum. acumulado%';
                //error_log(print_r($bill_order, true));

                //Obj. mensual
                $custom_obj_men['amounts'] = array();
                foreach($bill_order['obj_men']['amounts'] as $obj_men){
                    $custom_obj_men['amounts'][] = $obj_men;
                }

                //Fac. mensual
                $custom_fac_men['amounts'] = array();
                foreach($bill_order['fac_men']['amounts'] as $fac_men){
                    $custom_fac_men['amounts'][] = $fac_men;
                }

                //Cum. mensual
                $custom_cum_men['amounts'] = array();
                foreach($bill_order['cum_men']['amounts'] as $cum_men){
                    $custom_cum_men['amounts'][]['amount'] = 0;
                }

                //Obj. total
                $custom_obj_total['amounts'] = array();
                foreach($bill_order['obj_total']['amounts'] as $obj_total){
                    $custom_obj_total['amounts'][] = $obj_total;
                }

                //Fac. total
                $custom_fac_total['amounts'] = array();
                foreach($bill_order['fac_total']['amounts'] as $fac_total){
                    $custom_fac_total['amounts'][] = $fac_total;
                }

                //Cum. total
                $custom_cum_total['amounts'] = array();
                foreach($bill_order['cum_total']['amounts'] as $cum_total){
                    $custom_cum_total['amounts'][]['amount'] = 0;
                }

                $custom_obj['obj_men'] = $custom_obj_men;
                $custom_obj['fac_men'] = $custom_fac_men;
                $custom_obj['cum_men'] = $custom_cum_men;
                $custom_obj['obj_total'] = $custom_obj_total;
                $custom_obj['fac_total'] = $custom_fac_total;
                $custom_obj['cum_total'] = $custom_cum_total;
                $custom_obj['type_obj'] = 2;
                $array_dep_custom[] = $custom_obj;

            }else{
                $exist = false;
                $position = 0;
                foreach($array_dep_custom as $key_array_bills_orders_custom => $bill_order_custom){
                    if($bill_order_custom['dep_name'] == $bill_order['dep_name']){
                        $exist = true;
                        $position = $key_array_bills_orders_custom;
                    }
                }

                if($exist){
                    //Obj. mensual
                    foreach($bill_order['obj_men']['amounts'] as $obj_men){
                        $array_dep_custom[$position]['obj_men']['amounts'][count($array_bills_orders_custom[$position]['obj_men']['amounts']) - 1] += $obj_men;
                    }

                    //Fac. mensual
                    foreach($bill_order['fac_men']['amounts'] as $fac_men){
                        $array_dep_custom[$position]['fac_men']['amounts'][count($array_bills_orders_custom[$position]['fac_men']['amounts']) - 1] += $fac_men;
                    }

                    //Cum. mensual
                    foreach($bill_order['cum_men']['amounts'] as $cum_men){
                        $array_dep_custom[$position]['cum_men']['amounts'][count($array_bills_orders_custom[$position]['cum_men']['amounts']) - 1] += $cum_men;
                    }

                    //Obj. total
                    foreach($bill_order['obj_total']['amounts'] as $obj_total){
                        $array_dep_custom[$position]['obj_total']['amounts'][count($array_bills_orders_custom[$position]['obj_total']['amounts']) - 1] += $obj_total;
                    }

                    //Fac. total
                    foreach($bill_order['fac_total']['amounts'] as $fac_total){
                        $array_dep_custom[$position]['fac_total']['amounts'][count($array_bills_orders_custom[$position]['fac_total']['amounts']) - 1] += $fac_total;
                    }

                    //Cum. total
                    foreach($bill_order['cum_total']['amounts'] as $cum_total){
                        $array_dep_custom[$position]['cum_total']['amounts'][count($array_bills_orders_custom[$position]['cum_total']['amounts']) - 1] += $cum_total;
                    }
                }

                if(!$exist){
                    $custom_obj['dep_name'] = $bill_order['dep_name'];

                    $custom_obj_men['period'] = 'Obj. mensual';
                    $custom_fac_men['period'] = 'Fac. mensual';
                    $custom_cum_men['period'] = 'Cum. mensual%';

                    $custom_obj_total['period'] = 'Obj. acumulado';
                    $custom_fac_total['period'] = 'Fac. acumulado';
                    $custom_cum_total['period'] = 'Cum. acumulado%';
                    //error_log(print_r($bill_order, true));

                    //Obj. mensual
                    $custom_obj_men['amounts'] = array();
                    foreach($bill_order['obj_men']['amounts'] as $obj_men){
                        $custom_obj_men['amounts'][] = $obj_men;
                    }

                    //Fac. mensual
                    $custom_fac_men['amounts'] = array();
                    foreach($bill_order['fac_men']['amounts'] as $fac_men){
                        $custom_fac_men['amounts'][] = $fac_men;
                    }

                    //Cum. mensual
                    $custom_cum_men['amounts'] = array();
                    foreach($bill_order['cum_men']['amounts'] as $cum_men){
                        $custom_cum_men['amounts'][]['amount'] = 0;
                    }

                    //Obj. total
                    $custom_obj_total['amounts'] = array();
                    foreach($bill_order['obj_total']['amounts'] as $obj_total){
                        $custom_obj_total['amounts'][] = $obj_total;
                    }

                    //Fac. total
                    $custom_fac_total['amounts'] = array();
                    foreach($bill_order['fac_total']['amounts'] as $fac_total){
                        $custom_fac_total['amounts'][] = $fac_total;
                    }
                    
                    //Cum. total
                    $custom_cum_total['amounts'] = array();
                    foreach($bill_order['cum_total']['amounts'] as $cum_total){
                        $custom_cum_total['amounts'][]['amount'] = 0;
                    }

                    $custom_obj['obj_men'] = $custom_obj_men;
                    $custom_obj['fac_men'] = $custom_fac_men;
                    $custom_obj['cum_men'] = $custom_cum_men;
                    $custom_obj['obj_total'] = $custom_obj_total;
                    $custom_obj['fac_total'] = $custom_fac_total;
                    $custom_obj['cum_total'] = $custom_cum_total;
                    $custom_obj['type_obj'] = 2;
                    $array_dep_custom[] = $custom_obj;
                }
            }
            
        }

        foreach($array_dep_custom as $dep_custom){
            $array_bills_orders_custom[] = $dep_custom;
        }

        //Row total
        $position_total = 0;

        error_log(count($array_bills_orders_custom));
        foreach($array_bills_orders_custom as $key => $bill_order){
            if($bill_order['type_obj'] == 2){
                if($key == 0){
                    $custom_obj_men['period'] = 'Obj. mensual';
                    $custom_fac_men['period'] = 'Fac. mensual';
                    $custom_cum_men['period'] = 'Cum. mensual%';

                    $custom_obj_total['period'] = 'Obj. acumulado';
                    $custom_fac_total['period'] = 'Fac. acumulado';
                    $custom_cum_total['period'] = 'Cum. acumulado%';

                    //Obj. mensual
                    $custom_obj_men['amounts'] = array();
                    foreach($bill_order['obj_men']['amounts'] as $obj_men){
                        $custom_obj_men['amounts'][] = $obj_men;
                    }

                    //Fac. mensual
                    $custom_fac_men['amounts'] = array();
                    foreach($bill_order['fac_men']['amounts'] as $fac_men){
                        $custom_fac_men['amounts'][] = $fac_men;
                    }

                    //Cum. mensual
                    $custom_cum_men['amounts'] = array();
                    foreach($bill_order['cum_men']['amounts'] as $cum_men){
                        $custom_cum_men['amounts'][]['amount'] = 0;
                    }

                    //Obj. total
                    $custom_obj_total['amounts'] = array();
                    foreach($bill_order['obj_total']['amounts'] as $obj_total){
                        $custom_obj_total['amounts'][] = $obj_total;
                    }

                    //Fac. total
                    $custom_fac_total['amounts'] = array();
                    foreach($bill_order['fac_total']['amounts'] as $fac_total){
                        $custom_fac_total['amounts'][] = $fac_total;
                    }

                    //Cum. total
                    $custom_cum_total['amounts'] = array();
                    foreach($bill_order['cum_total']['amounts'] as $cum_total){
                        $custom_cum_total['amounts'][]['amount'] = 0;
                    }

                    $custom_obj['obj_men'] = $custom_obj_men;
                    $custom_obj['fac_men'] = $custom_fac_men;
                    $custom_obj['cum_men'] = $custom_cum_men;
                    $custom_obj['obj_total'] = $custom_obj_total;
                    $custom_obj['fac_total'] = $custom_fac_total;
                    $custom_obj['cum_total'] = $custom_cum_total;
                    $custom_obj['type_obj'] = 3;
                    $array_bills_orders_custom[] = $custom_obj;
                    $position_total = (count($array_bills_orders_custom) - 1);

                }else{
                    error_log('position: '. $position_total);
                    //Obj. mensual
                    foreach($bill_order['obj_men']['amounts'] as $key => $obj_men){
                        $array_bills_orders_custom[$position_total]['obj_men']['amounts'][$key] += $obj_men;
                    }

                    //Fac. mensual
                    foreach($bill_order['fac_men']['amounts'] as $key => $fac_men){
                        $array_bills_orders_custom[$position_total]['fac_men']['amounts'][$key] += $fac_men;
                    }

                    //Cum. mensual
                    foreach($bill_order['cum_men']['amounts'] as $key => $cum_men){
                        $array_bills_orders_custom[$position_total]['cum_men']['amounts'][$key] += $cum_men;
                    }

                    //Obj. total
                    foreach($bill_order['obj_total']['amounts'] as $key => $obj_total){
                        $array_bills_orders_custom[$position_total]['obj_total']['amounts'][$key] += $obj_total;
                    }

                    //Fac. total
                    foreach($bill_order['fac_total']['amounts'] as $key => $fac_total){
                        $array_bills_orders_custom[$position_total]['fac_total']['amounts'][$key] += $fac_total;
                    }

                    //Cum. total
                    foreach($bill_order['cum_total']['amounts'] as $key => $cum_total){
                        $array_bills_orders_custom[$position_total]['cum_total']['amounts'][$key] += $cum_total;
                    }
                }
            }
        }

        //Array de fechas
        $array_dates_finish = $this->generateDateArray($num_months, $date_from_custom, 1, true);

        $response['code'] = 1000;
        $response['array_dates'] = $array_dates_finish;
        $response['array_bills_orders_custom'] = $array_bills_orders_custom;

        return response()->json($response);

    }


    //Generar array de fechas
    function generateDateArray($num_months, $date_from_custom, $type, $trim){
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

            if($trim){
                if($i == 2){
                    $array_dates[]['date'] = '1/TRIM.';
                }
                if($i == 5){
                    $array_dates[]['date'] = '2/TRIM.';
                }
                if($i == 8){
                    $array_dates[]['date'] = '3/TRIM.';
                }
                if($i == 11){
                    $array_dates[]['date'] = '4/TRIM.';
                }
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
