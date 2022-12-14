<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Service;
use App\Models\SerBillvice;
use App\Models\Article;
use App\Models\Batch;
use App\Models\ServiceBill;
use App\Models\Proposal;
use App\Models\Bill;
use App\Models\Department;
use App\Models\ProposalBill;

class ProposalsInfoges extends Controller
{

    //Crear propuesta
    function createProposal(Request $request){
        $id_company = $request->get('id_company');
        $id_user = $request->get('id_user');
    
        //Consultamos el usuario
        $user = User::find($id_user);

        //Consultamos si existe la empresa
        $company = Company::find($id_company);
        if(!$company){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //Guardamos el objeto
        $bill_obj = $request->get('bill_obj');

        $array_services_aux = array();
        //Consultamos los artículos
        foreach($bill_obj['articles'] as $article){
            //Consultamos el artículo por el id_sage
            $article_sage = Article::where('id_sage', $article['id_sage_article'])->first();

            $service = Service::create([
                'pvp' => $article['amount'],
                'date' => $article['date'],
                'id_article' => $article_sage->id
            ]);
            $array_services_aux[] = $service;
        }

        $array_bills_aux = array();
        //Consultamos las facturas
        foreach($bill_obj['array_bills'] as $key => $bill_obj){
            $bill = Bill::create([
                'id_bill_internal' => $key + 1,
                'amount' => $bill_obj['amount'],
                'date' => $bill_obj['date'],
                'observations' => $bill_obj['observations'],
                'num_order' => $bill_obj['order_number'],
                'internal_observations' => $bill_obj['internal_observations'],
                'way_to_pay' => $bill_obj['select_way_to_pay'],
                'expiration' => $bill_obj['select_expiration'],
            ]);

            $array_bills_aux[] = $bill;

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

                if($service->date == $bill->date && $bill_obj['id_chapter'] == $batch->id_chapter){
                    ServiceBill::create([
                        'id_service' => $service->id,
                        'id_bill' => $bill->id,
                    ]);
                }
            }
        }

        //Consultamos el número de propuestas que hay
        $count_proposal = Proposal::count();

        //Creamos la propuesta 
        $proposal_submission_settings = $request->get('proposal_submission_settings');
        //$id_proposal_custom = sprintf('%08d', ($count_proposal + 1));
        $id_proposal_custom = ($count_proposal + 1);
        $id_department = $request->get('id_department');

        $proposal = Proposal::create([
            'id_proposal_custom' => $id_proposal_custom,
            'id_user' => $user->id,
            'id_contact' => $id_company,
            'discount' => $proposal_submission_settings['discount'],
            'commercial_name' => $proposal_submission_settings['commercial_name'],
            'language' => $proposal_submission_settings['language'],
            'type_proyect' => $proposal_submission_settings['type_proyect'],
            'name_proyect' => $proposal_submission_settings['name_proyect'],
            'date_proyect' => $proposal_submission_settings['date_proyect'],
            'objetives' => $proposal_submission_settings['objetives'],
            'proposal' => $proposal_submission_settings['proposal'],
            'actions' => $proposal_submission_settings['actions'],
            'observations' => $proposal_submission_settings['observations'],
            'show_discounts' => $proposal_submission_settings['show_discounts'],
            'show_inserts' => $proposal_submission_settings['show_inserts'],
            'show_invoices' => $proposal_submission_settings['show_invoices'],
            'show_pvp' => $proposal_submission_settings['show_pvp'],
            'sales_possibilities' => $proposal_submission_settings['sales_possibilities'],
            'id_department' => $id_department
        ]);

        $fullname = $user->name.' '.$user->surnames;

        //Consultamos el nombre del departamento
        $department = Department::find($proposal->id_department);

        //Creamos las relacion de la propuesta con la factura
        foreach($array_bills_aux as $bill){
            ProposalBill::create([
                'id_proposal' => $proposal->id,
                'id_bill' => $bill->id
            ]);
        }

        //Contabilizamos el colspan de plan de pago
        $bill_obj2 = $request->get('bill_obj');
        foreach($bill_obj2['array_bills'] as $bill){
            $rows = 2;
            if($bill['observations'] != ''){
                $rows++;
            }
            if($bill['order_number'] != ''){
                $rows++;
            }
            if($bill['internal_observations'] != ''){
                $rows++;
            }
            $bill['rows'] = $rows;
        }
        
        $response['id_proposal'] = $proposal->id;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Modificar propuesta
    function updateProposal(Request $request){
        //Comprobamos si existe la propuesta
        if (!$request->has('id_proposal') || !$request->has('id_user')){
            $response['code'] = 1001;
            return response()->json($response);
        }

        $id_proposal = $request->get('id_proposal');
        $id_user = $request->get('id_user');

        if(empty($id_proposal) || empty($id_user)){
            $response['code'] = 1002;
            return response()->json($response);
        }

        $proposal = Proposal::find($id_proposal);
        if(!$proposal){
            $response['code'] = 1003;
            return response()->json($response);
        }

        //Limpiamos la bd de los datos anteriores
        $array_bills = array();
        $array_proposals_bills = ProposalBill::where('id_proposal', $proposal->id)->get();
        foreach($array_proposals_bills as $proposal_bill){
            $array_bills[] = $proposal_bill->id_bill;
            $proposal_bill->delete();
        }
        $array_services = array();
        foreach($array_bills as $bill){
            $array_services_bills = ServiceBill::where('id_bill', $bill)->get();
            foreach($array_services_bills as $service_bill){
                $array_services[] = $service_bill->id_service;
                $service_bill->delete();
            }
            Bill::find($bill)->delete();
        }
        foreach($array_services as $service){
            Service::find($service)->delete();
        }

        //Guardamos el objeto
        $bill_obj = $request->get('bill_obj');

        $array_services_aux = array();
        //Consultamos los artículos
        foreach($bill_obj['articles'] as $article){
            //Consultamos el artículo por el id_sage
            $article_sage = Article::where('id_sage', $article['id_sage_article'])->first();

            $service = Service::create([
                'pvp' => $article['amount'],
                'date' => $article['date'],
                'id_article' => $article_sage->id
            ]);
            $array_services_aux[] = $service;
        }

        $array_bills_aux = array();
        //Consultamos las facturas
        foreach($bill_obj['array_bills'] as $key => $bill_obj){
            $bill = Bill::create([
                'id_bill_internal' => $key + 1,
                'amount' => $bill_obj['amount'],
                'date' => $bill_obj['date'],
                'observations' => $bill_obj['observations'],
                'num_order' => $bill_obj['order_number'],
                'internal_observations' => $bill_obj['internal_observations'],
                'way_to_pay' => $bill_obj['select_way_to_pay'],
                'expiration' => $bill_obj['select_expiration'],
            ]);

            $array_bills_aux[] = $bill;

            //Creamos la relación entre las facturas y los artículos
            foreach($array_services_aux as $service){
                //Consultamos el capitulo del servicio
                $article = Article::find($service->id_article);
                if(!$article){
                    $response['code'] = 1004;
                    return response()->json($response);
                }

                $batch = Batch::find($article->id_batch);
                if(!$batch){
                    $response['code'] = 1005;
                    return response()->json($response);
                }

                if($service->date == $bill->date && $bill_obj['id_chapter'] == $batch->id_chapter){
                    ServiceBill::create([
                        'id_service' => $service->id,
                        'id_bill' => $bill->id,
                    ]);
                }
            }
        }

        //Actualizamos la propuesta 
        $proposal_submission_settings = $request->get('proposal_submission_settings');

        $proposal->discount = $proposal_submission_settings['discount'];
        $proposal->language = $proposal_submission_settings['language'];
        $proposal->type_proyect = $proposal_submission_settings['type_proyect'];
        $proposal->name_proyect = $proposal_submission_settings['name_proyect'];
        $proposal->date_proyect = $proposal_submission_settings['date_proyect'];
        $proposal->objetives = $proposal_submission_settings['objetives'];
        $proposal->proposal = $proposal_submission_settings['proposal'];
        $proposal->actions = $proposal_submission_settings['actions'];
        $proposal->observations = $proposal_submission_settings['observations'];
        $proposal->show_discounts = $proposal_submission_settings['show_discounts'];
        $proposal->show_inserts = $proposal_submission_settings['show_inserts'];
        $proposal->show_invoices = $proposal_submission_settings['show_invoices'];
        $proposal->show_pvp = $proposal_submission_settings['show_pvp'];
        $proposal->sales_possibilities = $proposal_submission_settings['sales_possibilities'];
        $proposal->save();

        //Consultamos el usuario
        $user = User::find($id_user);
        if(!$user){
            $response['code'] = 1006;
            return response()->json($response);
        }

        $fullname = $user->name.' '.$user->surnames;

        //Consultamos el nombre del departamento
        $department = Department::find($proposal->id_department);

        //Creamos las relacion de la propuesta con la factura
        foreach($array_bills_aux as $bill){
            ProposalBill::create([
                'id_proposal' => $proposal->id,
                'id_bill' => $bill->id
            ]);
        }

        //Contabilizamos el colspan de plan de pago
        $bill_obj2 = $request->get('bill_obj');
        foreach($bill_obj2['array_bills'] as $bill){
            $rows = 2;
            if($bill['observations'] != ''){
                $rows++;
            }
            if($bill['order_number'] != ''){
                $rows++;
            }
            if($bill['internal_observations'] != ''){
                $rows++;
            }
            $bill['rows'] = $rows;
        }

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Eliminar propuesta
    function deleteProposal($id){
        $proposal = Proposal::find($id);
        if(!$proposal){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //Eliminamos la propuesta
        $array_bills = array();
        $array_proposals_bills = ProposalBill::where('id_proposal', $proposal->id)->get();
        foreach($array_proposals_bills as $proposal_bill){
            $array_bills[] = $proposal_bill->id_bill;
            $proposal_bill->delete();
        }
        $array_services = array();
        foreach($array_bills as $bill){
            $array_services_bills = ServiceBill::where('id_bill', $bill)->get();
            foreach($array_services_bills as $service_bill){
                $array_services[] = $service_bill->id_service;
                $service_bill->delete();
            }
            Bill::find($bill)->delete();
        }
        foreach($array_services as $service){
            Service::find($service)->delete();
        }

        $proposal->delete();

        $response['code'] = 1000;
        return response()->json($response);

    }
}