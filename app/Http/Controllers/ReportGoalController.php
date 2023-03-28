<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillOrder;

class ReportGoalController extends Controller
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

        //Canales DIG Y PRINT
        $array_bills_orders_dig = BillOrder::select('users_objetives.id', 'users_objetives.obj_print', 'proposals.id_user', 'bills_orders.*', 'departments.nomenclature as department_nomenclature', 'departments.name as department_name', 'departments.id as id_department', 'sections.nomenclature as section_nomenclature', 'channels.nomenclature as channel_nomenclature', 'projects.nomenclature as project_nomenclature')
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
                                        ->where('users_objetives.id', '<>', null)->get();

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
