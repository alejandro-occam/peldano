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
use App\Models\Article;
use App\Models\Sector;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Storage;
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

        $array_proposals = Proposal::select('proposals.*', 'sectors.name as sector_name')
                        ->leftJoin('sectors', 'sectors.id', 'proposals.id_sector')
                        ->leftJoin('proposals_bills', 'proposals.id', 'proposals_bills.id_proposal')
                        ->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill');

        if($request->get('type') == 1){
            if($request->get('num_proposal') != ''){
                $array_proposals = $array_proposals->where('proposals.id_proposal_custom', $request->get('num_proposal'));
            }

            if($request->get('select_consultant') != ''){
                $array_proposals = $array_proposals->where('proposals.id_user', $request->get('select_consultant'));
            }

            if($request->get('select_sector') != ''){
                $array_proposals = $array_proposals->where('proposals.id_sector', $request->get('select_sector'));
            }

            if($request->get('date_from') != ''){
                $array_proposals = $array_proposals->where('proposals.date_proyect', '>=', $request->get('date_from'));
            }

            if($request->get('date_to') != ''){
                $array_proposals = $array_proposals->where('proposals.date_proyect', '<=', $request->get('date_to'));
            }
        }

        $array_proposals = $array_proposals->groupBy('proposals.id')
                                            ->skip($start)
                                            ->take($skip)
                                            ->get();

        foreach($array_proposals as $proposal){
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
                //Consultamos el producto del servicio
                $article = Article::find($service->id_article);
                if($service->date == $bill->date && $bill_obj->article->id_product == $article->id_product){
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
        $id_sector = $request->get('id_sector');

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
            'id_sector' => $id_sector
        ]);

        $fullname = Auth::user()->name.' '.Auth::user()->surnames;

        //Consultamos el nombre del sector
        $sector = Sector::find($proposal->id_sector);

        //Creamos las relacion de la propuesta con la factura
        foreach($array_bills_aux as $bill){
            ProposalBill::create([
                'id_proposal' => $proposal->id,
                'id_bill' => $bill->id
            ]);
        }

        //Contabilizamos el colspan de plan de pago
        $bill_obj2 = json_decode($request->get('bill_obj'));
        foreach($bill_obj2->array_bills as $bill){
            $rows = 2;
            if($bill->observations != ''){
                $rows++;
            }
            if($bill->order_number != ''){
                $rows++;
            }
            if($bill->internal_observations != ''){
                $rows++;
            }
            $bill->rows = $rows;
        }
        
        //Preparamos los datos a pasar al pdf
        $data['proposal'] = $proposal;
        $data['fullname'] = $fullname;
        $data['sector_name'] = $sector->name;
        $data['proposal_obj'] = json_decode($request->get('proposal_obj'));
        $data['bill_obj'] = $bill_obj2;
        $data['array_bills'] = $bill_obj2->array_bills;
        $data['total_bill'] = $bill_obj2->total_bill;
        $data['value_form1'] = $request->get('value_form1');
        $data['proposal_submission_settings'] = $proposal_submission_settings;
        $data['select_way_to_pay_options'] = $request->get('select_way_to_pay_options');
        $data['select_expiration_options'] = $request->get('select_expiration_options');

        //Generamos el pdf
        /*$pdf = Pdf::loadView('pdf.invoice', $data)->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);
        $content = $pdf->download()->getOriginalContent();
        Storage::put('pdfs_bills/propuesta-'.$proposal->id_proposal_custom.'.pdf',$content);*/

        //Guardamos el fichero
        $proposal->pdf_file = 'pdfs_bills/propuesta-'.$proposal->id_proposal_custom.'.pdf';
        $proposal->save();

        $response['pdf_file'] = $proposal->pdf_file;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Generar pdf de la propuesta
    function generatePdfProposalTest(){
        $data = array();
        $pdf = Pdf::loadView('pdf.invoice', $data)->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);
        return $pdf->download('invoice.pdf');
    }

    //Mostrar información de una propuesta
    function getInfoProposal($id){
        //Consultamos si existe la propuesta
        $proposal = Proposal::select('proposals.*', 'contacts.name as contact_name', 'contacts.surnames as contact_surnames', 'contacts.email as contact_email', 'contacts.phone as contact_phone', 'contacts.id_company')
                                ->leftJoin('contacts', 'contacts.id', 'proposals.id_contact')
                                ->where('proposals.id', $id)
                                ->with('sector')
                                ->first();

        $proposal['id_proposal_custom_aux'] = sprintf('%08d', $proposal->id_proposal_custom);

        if(!$proposal){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //Consultamos el array de facturas
        $proposal_bills = Bill::select('bills.*')
                            ->leftJoin('proposals_bills', 'proposals_bills.id_bill', 'bills.id')
                            ->where('proposals_bills.id_proposal', '=', $proposal->id)
                            ->get();

        if(count($proposal_bills) <= 0){
            $response['code'] = 1002;
            return response()->json($response);
        }

        //Consultamos el array de servicios
        $array_services = array();
        foreach($proposal_bills as $bill){
            $array_services_obj = Service::select('services.*')
                                    ->leftJoin('services_bills', 'services.id', 'services_bills.id_service')
                                    ->where('services_bills.id_bill', $bill->id)
                                    ->with('article')
                                    ->get();
            
            foreach($array_services_obj as $service){
                $service['product'] = $service->article->product;
                $array_services[] = $service;
            }
        }

        error_log('array_services: '.count($array_services));
        
        $response['array_services'] = $array_services;
        $response['proposal'] = $proposal;
        $response['proposal_bills'] = $proposal_bills;
        $response['code'] = 1000;
        return response()->json($response);
    }
}
