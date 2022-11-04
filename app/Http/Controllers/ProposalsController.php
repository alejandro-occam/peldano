<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Service;
use App\Models\Bill;
use App\Models\ServiceBill;
use App\Models\Proposal;
use App\Models\ProposalBill;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ProposalsController extends Controller
{
    //Listar propuestas
    function listProposals(Request $request){
        //Elementos para la paginación 
        $pagination = $request->get('pagination');
        $query = $request->get('query');
        $start = 0;
        $skip = $pagination['perpage'];
        if ($pagination['page'] != 1) {
            $start = ($pagination['page'] - 1) * $pagination['perpage'];
            //Consultamos si hay tantos registros como para empezar en el numero de $start
            $num_proposals = Proposal::count();
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

        $array_proposals = Proposal::skip($start)
                        ->take($skip)
                        ->get();

        foreach($array_proposals as $proposal){
            $contact = Contact::find($proposal->id_contact);
            $proposal['name_contact'] = $contact->name.' '.$contact->surnames;

            $id_proposal_custom_aux = sprintf('%08d', $proposal->id_proposal_custom);
            $date_aux = explode("-", $proposal->date_proyect);
            $proposal['proposal_custom'] = 'EP'.$date_aux[2].$date_aux[1].'-'.$id_proposal_custom_aux;
        }

        $total_proposals = Proposal::count();

        //Devolución de la llamada con la paginación
        $meta['page'] = $pagination['page'];

        if ($total_proposals < 1) {
            $meta['page'] = 1;
        }

        $meta['pages'] = 1;
        if (isset($pagination['pages'])) {
            $meta['pages'] = $pagination['pages'];
        }
        $meta['perpage'] = $pagination['perpage'];
        $meta['total'] = $total_proposals;
        $meta['sort'] = 'asc';
        $meta['field'] = 'id';
        $response['meta'] = $meta;
        $response['data'] = $array_proposals;
        return response()->json($response);
    }

    //Consultar usuarios
    function getUsers(){
        $array_users = User::get();
        $response['array_users'] = $array_users;
        return response()->json($response);
    }

    //Consultar empresas
    function getCompanies(){
        //$array_companies = Company::select('companies.*', DB::raw('CONCAT(contacts.name, " ", contacts.surnames) as fullname', 'contacts.id as id_contact'), 'contacts.email')->leftJoin('contacts', 'contacts.id_company', 'companies.id')->get();
        $array_contacts = Contact::select('contacts.*', 'companies.name', 'companies.nif', DB::raw('CONCAT(contacts.name, " ", contacts.surnames) as fullname', 'contacts.id as id_contact'), 'contacts.email')->leftJoin('companies', 'contacts.id_company', 'companies.id')->get();
        $response['array_companies'] = $array_contacts;

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
        $proposal_submission_settings = json_decode($request->get('proposal_submission_settings'));
        //$id_proposal_custom = sprintf('%08d', ($count_proposal + 1));
        $id_proposal_custom = ($count_proposal + 1);
        $proposal = Proposal::create([
            'id_proposal_custom' => $id_proposal_custom,
            'id_user' => Auth::user()->id,
            'id_contact' => $id_company,
            'discount' => 0,
            'commercial_name' => $proposal_submission_settings->commercial_name,
            'language' => $proposal_submission_settings->language,
            'type_proyect' => $proposal_submission_settings->type_proyect,
            'name_proyect' => $proposal_submission_settings->name_proyect,
            'date_proyect' => $proposal_submission_settings->date_proyect,
            'objetives' => $proposal_submission_settings->objetives,
            'proposal' => $proposal_submission_settings->proposal,
            'actions' => $proposal_submission_settings->actions,
            'observations' => $proposal_submission_settings->observations,
            'show_discounts' => $proposal_submission_settings->show_discounts,
            'show_inserts' => $proposal_submission_settings->show_inserts,
            'show_invoices' => $proposal_submission_settings->show_invoices,
            'show_pvp' => $proposal_submission_settings->show_pvp,
            'sales_possibilities' => $proposal_submission_settings->sales_possibilities,
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
