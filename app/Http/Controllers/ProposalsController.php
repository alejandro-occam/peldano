<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\Service;
use App\Models\Bill;
use App\Models\ServiceBill;
use App\Models\Proposal;
use App\Models\ProposalBill;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ProposalsController extends Controller
{
    //Consultar usuarios
    function getUsers(){
        $array_users = User::get();
        $response['array_users'] = $array_users;
        return response()->json($response);
    }

    //Consultar empresas
    function getCompanies(){
        $array_companies = Company::select('companies.*', DB::raw('CONCAT(contacts.name, " ", contacts.surnames) as fullname'), 'contacts.email')->leftJoin('contacts', 'contacts.id_company', 'companies.id')->get();
        $response['array_companies'] = $array_companies;

        //Consultamos el usuario
        $user = User::find(Auth::user()->id);

        $response['user'] = $user;
        return response()->json($response);
    }

    //Guardar y generar la propuesta
    function saveAndGenerateProposal(Request $request){
        $id_company = $request->get('id_company');
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
        foreach(json_decode($bill_obj)->articles as $article){
            $service = Service::create([
                'pvp' => $article->amount,
                'date' => $article->date,
                'id_article' => $article->article->article_obj->id
            ]);
            $array_services_aux[] = $service;
        }

        $array_bills_aux = array();
        //Consultamos las facturas
        foreach(json_decode($bill_obj)->array_bills as $key => $bill_obj){
            $bill = Bill::create([
                'id_bill_internal' => $key + 1,
                'amount' => $bill_obj->amount,
                'date' => $bill_obj->date,
                'observations' => $bill_obj->observations,
                'num_order' => $bill_obj->order_number,
                'internal_observations' => $bill_obj->internal_observations,
                'way_to_pay' => $bill_obj->select_way_to_pay,
                'expiration' => $bill_obj->select_expiration,
            ]);

            $array_bills_aux[] = $bill;

            //Creamos la relación entre las facturas y los artículos
            foreach($array_services_aux as $service){
                if($service->date == $bill->date && $bill_obj->article->article->article_obj->id == $service->id_article){
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
        //$id_proposal_custom = sprintf('%08d', ($count_proposal + 1));
        $id_proposal_custom = ($count_proposal + 1);
        $proposal = Proposal::create([
            'id_proposal_custom' => $id_proposal_custom,
            'id_user' => Auth::user()->id,
            'id_contact' => $id_company,
            'discount' => 0,
        ]);

        //Creamos las relacion de la propuesta con la factura
        foreach($array_bills_aux as $bill){
            error_log('$proposal->id: '.$proposal->id);
            ProposalBill::create([
                'id_proposal' => $proposal->id,
                'id_bill' => $bill->id
            ]);
        }
    }

    //Generar pdf de la propuesta
    function generatePdfProposalTest(){
        $data = array();
        $pdf = Pdf::loadView('pdf.invoice', $data)->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);
        return $pdf->download('invoice.pdf');
       
    }
}
