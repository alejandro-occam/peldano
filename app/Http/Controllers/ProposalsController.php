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
use App\Models\Department;
use App\Models\Chapter;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Order;
use App\Models\BillOrder;
use App\Models\Batch;
use App\Models\ServiceBillOrder;
use App\Models\ConsultantProposal;
use App\Models\ConsultanOrder;
use App\Http\Controllers\CurlController;

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

        $array_proposals = Proposal::select('proposals.*', 'departments.name as department_name')
                        ->leftJoin('departments', 'departments.id', 'proposals.id_department')
                        ->leftJoin('proposals_bills', 'proposals.id', 'proposals_bills.id_proposal')
                        ->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill');

        if($request->get('type') == 1){
            if($request->get('status_order') != '' && $request->get('status_order') != 3){
                $array_proposals = Proposal::select('proposals.*', 'departments.name as department_name')
                                            ->leftJoin('departments', 'departments.id', 'proposals.id_department')
                                            ->leftJoin('proposals_bills', 'proposals.id', 'proposals_bills.id_proposal')
                                            ->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill')
                                            ->leftJoin('orders', 'proposals.id', 'orders.id_proposal');
                                     
                if($request->get('status_order') == 1){
                    $array_proposals = $array_proposals->where('orders.id_proposal', '=', null);
                }
               
                if($request->get('status_order') == 2){
                    $array_proposals = $array_proposals->where('orders.id_proposal', '<>', null);
                }
            }

            if($request->get('num_proposal') != ''){
                $array_proposals = $array_proposals->where('proposals.id_proposal_custom', $request->get('num_proposal'));
            }

            if($request->get('select_consultant') != ''){
                $array_proposals = $array_proposals->where('proposals.id_user', $request->get('select_consultant'));
            }

            if($request->get('select_department') != ''){
                $array_proposals = $array_proposals->where('proposals.id_department', $request->get('select_department'));
            }

            if($request->get('date_from') != '' && $request->get('date_from') != 'Invalid Date-undefined-undefined'){
                $array_proposals = $array_proposals->where('proposals.date_proyect', '>=', $request->get('date_from'));
            }

            if($request->get('date_to') != '' && $request->get('date_to') != 'Invalid Date-undefined-undefined'){
                $array_proposals = $array_proposals->where('proposals.date_proyect', '<=', $request->get('date_to'));
            }
        }

        $total_proposals = $array_proposals->groupBy('proposals.id')
                                            ->orderBy('proposals.created_at', 'desc')
                                            ->get();

        $array_proposals = $array_proposals->groupBy('proposals.id')
                                            ->orderBy('proposals.created_at', 'desc')
                                            ->skip($start)
                                            ->take($skip)
                                            ->get();

                                            
        foreach($array_proposals as $proposal){
            //Consultamos el nombre del contacto
            $contact = Contact::find($proposal->id_contact);
            $proposal['name_contact'] = $contact->name.' '.$contact->surnames;
            $proposal['id_sage_contact'] = $contact->id_sage;

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
           
            $proposal['total_amount'] = number_format($total, 2);
        }

        //Devolución de la llamada con la paginación
        $meta['page'] = $pagination['page'];

        if (count($total_proposals) < 1) {
            $meta['page'] = 1;
        }

        $meta['pages'] = 1;
        if (isset($pagination['pages'])) {
            $meta['pages'] = $pagination['pages'];
        }
        $meta['perpage'] = $pagination['perpage'];
        $meta['total'] = count($total_proposals);
        $meta['sort'] = 'asc';
        $meta['field'] = 'id';
        $response['meta'] = $meta;
        $response['data'] = $array_proposals;
        return response()->json($response);
    }

    //Consultar usuarios
    function getUsers(){
        $array_users = User::select('users.*', 'roles.name as role_name') 
                            ->leftJoin('role_user', 'role_user.user_id', 'users.id')
                            ->leftJoin('roles', 'roles.id', 'role_user.role_id')
                            ->get();
        $response['array_users'] = $array_users;
        return response()->json($response);
    }

    //Consultar empresas
    function getCompanies(){
        //$array_companies = Company::select('companies.*', DB::raw('CONCAT(contacts.name, " ", contacts.surnames) as fullname', 'contacts.id as id_contact'), 'contacts.email')->leftJoin('contacts', 'contacts.id_company', 'companies.id')->get();
        $array_contacts = Contact::select('contacts.*', 'companies.name', 'companies.nif', 'companies.address', DB::raw('CONCAT(contacts.name, " ", contacts.surnames) as fullname', 'contacts.id as id_contact'), 'contacts.email')->leftJoin('companies', 'contacts.id_company', 'companies.id')->get();
        $response['array_companies'] = $array_contacts;

        //Consultamos el usuario
        $user = User::find(Auth::user()->id);

        $response['user'] = $user;
        return response()->json($response);
    }

     //Consultar empresas
     function getCompaniesSearch(Request $request){
        $search = $request->get('term');
        $type_search = $request->get('type_search');

        error_log('search: '.$search);
        error_log('type_search: '.$type_search);

        $array_companies = array();
        $array_companies_custom = array();
        if(isset($search)){
            $array_companies = Contact::select('contacts.*', 'companies.name', 'companies.nif', 'companies.address', DB::raw('CONCAT(contacts.name, " ", contacts.surnames) as fullname', 'contacts.id as id_contact'), 'contacts.email')
                                    ->leftJoin('companies', 'contacts.id_company', 'companies.id');
            if($type_search == 1){
                $array_companies = $array_companies->where('contacts.name', 'like', '%'.$search.'%')
                                                    ->orWhere('contacts.surnames', 'like', '%'.$search.'%')
                                                    ->orWhere('companies.name', 'like', '%'.$search.'%')
                                                    ->get();
            }
            if($type_search == 2){
                $array_companies = $array_companies->where('contacts.email', 'like', '%'.$search.'%')
                                                    ->orWhere('companies.nif', 'like', '%'.$search.'%')
                                                    ->orWhere('companies.address', 'like', '%'.$search.'%')
                                                    ->get();
            }

            foreach($array_companies as $company){
                $company_custom['id'] = $company['id'];
                if($type_search == 1){
                    $company_custom['text'] = $company['name'].' - '.$company['fullname'];
                }
                if($type_search == 2){
                    $company_custom['text'] = $company['email'].' - '.$company['nif'];
                }
                $company_custom['name'] = $company['name'].' - '.$company['fullname'];
                $company_custom['nif'] = $company['nif'];
                $company_custom['address'] = $company['address'];
                $array_companies_custom[] = $company_custom;
            }
        }
        $response['array_companies'] = $array_companies_custom;
        $response['search'] = $search;
        return response()->json($array_companies_custom);
    }

    //Guardar y generar la propuesta
    function saveAndGenerateProposal(Request $request){
        $id_company = $request->get('id_company');

        //COMENTARIO PARA EL OBJETO
        error_log('id_company: '.$id_company);
        //END COMENTARIO PARA EL OBJETO

        //Consultamos si existe la empresa
        $company = Company::find($id_company);
        if(!$company){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //Guardamos el objeto
        $bill_obj = $request->get('bill_obj');

        //COMENTARIO PARA EL OBJETO
        error_log('bill_obj: '.print_r($bill_obj, true));
        //END COMENTARIO PARA EL OBJETO

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
        $custom_bill = false;
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

            $nun_custom_invoices = $request->get('nun_custom_invoices');
            $custom_bill = false;
            if(isset($nun_custom_invoices)){
                if($nun_custom_invoices > 0){
                    $custom_bill = true;
                }
            }
            if(!$custom_bill){
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

                    if($service->date == $bill->date && $bill_obj->article->id_chapter == $batch->id_chapter){
                        ServiceBill::create([
                            'id_service' => $service->id,
                            'id_bill' => $bill->id,
                        ]);
                    }
                }
            }
            if($custom_bill){
                foreach($array_services_aux as $service){
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

        //COMENTARIO PARA EL OBJETO
        error_log('proposal_submission_settings: '.print_r($request->get('proposal_submission_settings'), true));
        //END COMENTARIO PARA EL OBJETO

        //$id_proposal_custom = sprintf('%08d', ($count_proposal + 1));
        $id_proposal_custom = ($count_proposal + 1);
        $id_department = $request->get('id_department');

        //COMENTARIO PARA EL OBJETO
        error_log('id_department: '.$request->get('id_department'));
        //END COMENTARIO PARA EL OBJETO

        $status = 1;
        if($proposal_submission_settings->discount >= 20){
            $status = 2;
        }

        $advertiser = $request->get('advertiser');

        $proposal = Proposal::create([
            'id_proposal_custom' => $id_proposal_custom,
            'id_user' => Auth::user()->id,
            'id_contact' => $id_company,
            'discount' => $proposal_submission_settings->discount,
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
            'id_department' => $id_department,
            'is_custom' => $custom_bill,
            'status' => $status,
            'advertiser' => $advertiser
        ]);

        $fullname = Auth::user()->name.' '.Auth::user()->surnames;

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
        $bill_obj2 = json_decode($request->get('bill_obj'));

        //COMENTARIO PARA EL OBJETO
        error_log('bill_obj2: '.$request->get('bill_obj'));
        //END COMENTARIO PARA EL OBJETO

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

        //Rellenamos la tabla de consultores
        $array_consultants = $request->get('array_consultants');
        if(count($array_consultants) > 1){
            foreach($array_consultants as $key => $consultant){
                if($key != 0){
                    ConsultantProposal::create([
                        'id_consultant' => json_decode($consultant, true)['id_consultant'],
                        'id_proposal' => $proposal->id,
                        'percentage' => json_decode($consultant, true)['percentage'],
                    ]);
                }
            }
        }
        

        if($status == 1){
            $path = 'media/custom-imgs/logo_azul.png';
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data_base64 = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data_base64);
            
            //Preparamos los datos a pasar al pdf
            $data['proposal'] = $proposal;
            $data['fullname'] = $fullname;
            $data['department_name'] = $department->name;
            $data['proposal_obj'] = json_decode($request->get('proposal_obj'));
            $data['bill_obj'] = $bill_obj2;
            $data['select_way_to_pay_options'] = $request->get('select_way_to_pay_options');
            $data['select_expiration_options'] = $request->get('select_expiration_options');
            $data['advertiser'] = $request->get('advertiser');
            $data['base64'] = $base64;
                    
            //Generamos el pdf
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.invoice', $data);
            $content = $pdf->download()->getOriginalContent();
            Storage::put('pdfs_bills/propuesta-'.$proposal->id_proposal_custom.'.pdf',$content);

            //Guardamos el fichero
            $proposal->pdf_file = 'pdfs_bills/propuesta-'.$proposal->id_proposal_custom.'.pdf';
            $proposal->save();
            $response['pdf_file'] = $proposal->pdf_file;
        }else{
            $response['pdf_file'] = '';
        }
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Generar pdf de la propuesta
    function generatePdfProposalTest(){
        $data = array();
        //$pdf = Pdf::loadView('pdf.test', $data)->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $data = array();
        
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.test', $data);
        return $pdf->download('invoice.pdf');
        //return $pdf->stream('Idea.pdf');
    }

    //Mostrar información de una propuesta
    function getInfoProposal($id){
        //Consultamos si existe la propuesta
        $custom_proposal = Proposal::find($id);
        error_log('custom_proposal: '.$custom_proposal);
        $proposal = Proposal::select('proposals.*', 'contacts.name as contact_name', 'contacts.surnames as contact_surnames', 'contacts.email as contact_email', 'contacts.phone as contact_phone', 'contacts.id_company')
                                ->leftJoin('contacts', 'contacts.id', 'proposals.id_contact')
                                ->where('proposals.id', $id)
                                ->with('department')
                                ->first();

        $proposal['id_proposal_custom_aux'] = sprintf('%08d', $proposal->id_proposal_custom);
        $proposal['department_obj'] = Department::find($proposal->id_department);

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
        $is_read = 0;
        foreach($proposal_bills as $bill){
            if($proposal->is_custom){
                if(!$is_read){
                    $array_articles = [];
                    $array_services_obj = Service::select('services.*')
                                            ->leftJoin('services_bills', 'services.id', 'services_bills.id_service')
                                            ->where('services_bills.id_bill', $bill->id)
                                            ->with('article')
                                            ->get();
                    foreach($array_services_obj as $service){
                        //Consultamos el capitulo
                        $batch = Batch::find($service->article->id_batch);
                        if(!$batch){
                            $response['code'] = 1003;
                            return response()->json($response);
                        }
                        
                        $chapter = Chapter::find($batch->id_chapter);
                        if(!$chapter){
                            $response['code'] = 1004;
                            return response()->json($response);
                        }

                        $service['chapter'] = $chapter;
                        $array_services[] = $service;
                        $article = Article::find($service->id_article);
                        $array_articles[] = $article;
                    }
                    $bill['array_articles'] = $array_articles;
                    $is_read = 1;
                }
            }else{
                $array_articles = [];
                $array_services_obj = Service::select('services.*')
                                        ->leftJoin('services_bills', 'services.id', 'services_bills.id_service')
                                        ->where('services_bills.id_bill', $bill->id)
                                        ->with('article')
                                        ->get();
                foreach($array_services_obj as $service){
                    //Consultamos el capitulo
                    $batch = Batch::find($service->article->id_batch);
                    if(!$batch){
                        $response['code'] = 1003;
                        return response()->json($response);
                    }
                    
                    $chapter = Chapter::find($batch->id_chapter);
                    if(!$chapter){
                        $response['code'] = 1004;
                        return response()->json($response);
                    }

                    $service['chapter'] = $chapter;
                    $array_services[] = $service;
                    $article = Article::find($service->id_article);
                    $array_articles[] = $article;
                }
                $bill['array_articles'] = $array_articles;
            }
        }

        //Consultamos el usuario que ha creado la propuesta
        $user = User::find($proposal->id_user);

        //Consultamos la empresa
        $array_companies = Contact::select('contacts.*', 'companies.name', 'companies.nif', 'companies.address', DB::raw('CONCAT(contacts.name, " ", contacts.surnames) as fullname', 'contacts.id as id_contact'), 'contacts.email')
                             ->leftJoin('companies', 'contacts.id_company', 'companies.id')
                             ->where('contacts.id', $proposal->id_contact)
                             ->get();

        //Consultamos los consultores
        $array_custom_consultant = array();
        $custom_consultant['id_consultant'] = $user->id;
        $custom_consultant['percentage'] = 100;
        $custom_consultant['name'] = $user->name.' '.$user->surname;
        $array_custom_consultant[] = $custom_consultant;

        $array_consultants = ConsultantProposal::where('id_proposal', $proposal->id)->get();
        foreach($array_consultants as $consultant){
            $user_consultant = User::find($consultant->id_consultant);
            $custom_consultant['id_consultant'] = $user_consultant->id;
            $custom_consultant['percentage'] = $consultant->percentage;
            $custom_consultant['name'] = $user_consultant->name.' '.$user_consultant->surname;
            $array_custom_consultant[] = $custom_consultant;
            $array_custom_consultant[0]['percentage'] -= $consultant->percentage;
        }
        
        $response['company_aux'] = $array_companies;
        $response['consultant'] = $user;
        $response['array_services'] = $array_services;
        $response['proposal'] = $proposal;
        $response['proposal_bills'] = $proposal_bills;
        $response['array_consultants'] = $array_custom_consultant;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Actualizar información de una propuesta
    function updateProposal(Request $request){
        //Comprobamos si existe la propuesta
        if (!$request->has('id_proposal')){
            $response['code'] = 1001;
            return response()->json($response);
        }

        $id_proposal = $request->get('id_proposal');

        if(empty($id_proposal)){
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
        $is_save = 0;
        foreach($array_bills as $bill){
            $array_services_bills = ServiceBill::where('id_bill', $bill)->get();
            foreach($array_services_bills as $service_bill){
                if(($proposal->is_custom && !$is_save) || (!$proposal->is_custom)){
                    $array_services[] = $service_bill->id_service;
                }
                $service_bill->delete();
            }
            $is_save = 1;
            Bill::find($bill)->delete();
        }
        foreach($array_services as $service){
            Service::find($service)->delete();
        }

        //Guardamos el objeto
        $bill_obj = $request->get('bill_obj');

        error_log('bill_obj: '.print_r($bill_obj, true));

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
            $nun_custom_invoices = $request->get('nun_custom_invoices');
            $custom_bill = false;
            if(isset($nun_custom_invoices)){
                if($nun_custom_invoices > 0){
                    $custom_bill = true;
                }
            }
            if(!$custom_bill){
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

                    if($service->date == $bill->date && $bill_obj->article->id_chapter == $batch->id_chapter){
                        ServiceBill::create([
                            'id_service' => $service->id,
                            'id_bill' => $bill->id,
                        ]);
                    }
                }
            }

            if($custom_bill){
                foreach($array_services_aux as $service){
                    ServiceBill::create([
                        'id_service' => $service->id,
                        'id_bill' => $bill->id,
                    ]);
                }
            }
        }

        //Actualizamos la propuesta 
        $proposal_submission_settings = json_decode($request->get('proposal_submission_settings'));
        $advertiser = $request->get('advertiser');

        $proposal->discount = $proposal_submission_settings->discount;
        $proposal->language = $proposal_submission_settings->language;
        $proposal->type_proyect = $proposal_submission_settings->type_proyect;
        $proposal->name_proyect = $proposal_submission_settings->name_proyect;
        $proposal->date_proyect = $proposal_submission_settings->date_proyect;
        $proposal->objetives = $proposal_submission_settings->objetives;
        $proposal->proposal = $proposal_submission_settings->proposal;
        $proposal->actions = $proposal_submission_settings->actions;
        $proposal->observations = $proposal_submission_settings->observations;
        $proposal->show_discounts = $proposal_submission_settings->show_discounts;
        $proposal->show_inserts = $proposal_submission_settings->show_inserts;
        $proposal->show_invoices = $proposal_submission_settings->show_invoices;
        $proposal->show_pvp = $proposal_submission_settings->show_pvp;
        $proposal->sales_possibilities = $proposal_submission_settings->sales_possibilities;
        $proposal->show_invoices = $proposal_submission_settings->show_invoices;
        $proposal->is_custom = $custom_bill;
        $proposal->advertiser = $advertiser;

        $status = 1;
        if($proposal_submission_settings->discount >= 20){
            $status = 2;
        }
        $proposal->status = $status;
        $proposal->save();

        $fullname = Auth::user()->name.' '.Auth::user()->surnames;

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

        //Actualizamos los consultores
        ConsultantProposal::where('id_proposal', $proposal->id)->delete();
        //Rellenamos la tabla de consultores
        $array_consultants = $request->get('array_consultants');
        if(count($array_consultants) > 1){
            foreach($array_consultants as $key => $consultant){
                if($key != 0){
                    ConsultantProposal::create([
                        'id_consultant' => json_decode($consultant, true)['id_consultant'],
                        'id_proposal' => $proposal->id,
                        'percentage' => json_decode($consultant, true)['percentage'],
                    ]);
                }
            }
        }

        if($status == 1){
            $path = 'media/custom-imgs/logo_azul.png';
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data_base64 = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data_base64);
            
            //Preparamos los datos a pasar al pdf
            $data['proposal'] = $proposal;
            $data['fullname'] = $fullname;
            $data['department_name'] = $department->name;
            $data['proposal_obj'] = json_decode($request->get('proposal_obj'));
            $data['bill_obj'] = $bill_obj2;
            $data['select_way_to_pay_options'] = $request->get('select_way_to_pay_options');
            $data['select_expiration_options'] = $request->get('select_expiration_options');
            $data['base64'] = $base64;
            $data['advertiser'] = $advertiser;

            //Guardamos el fichero
            $proposal->pdf_file = 'pdfs_bills/propuesta-'.$proposal->id_proposal_custom.'.pdf';
            $proposal->save();
                    
            //Generamos el pdf
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.invoice', $data);
            $content = $pdf->download()->getOriginalContent();
            Storage::put('pdfs_bills/propuesta-'.$proposal->id_proposal_custom.'.pdf',$content);
            $response['pdf_file'] = $proposal->pdf_file;

        }else{
            $response['pdf_file'] = '';
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
        $is_save = 0;
        foreach($array_bills as $bill){
            $array_services_bills = ServiceBill::where('id_bill', $bill)->get();
            foreach($array_services_bills as $service_bill){
                if(($proposal->is_custom && !$is_save) || (!$proposal->is_custom)){
                    $array_services[] = $service_bill->id_service;
                }
                $service_bill->delete();
            }
            $is_save = 1;
            Bill::find($bill)->delete();
        }

        foreach($array_services as $service){
            Service::find($service)->delete();
        }

        $proposal->delete();

        $response['code'] = 1000;
        return response()->json($response);

    }

    //Validar propuesta por el admin
    function validateProposal($id){
        //Consultamos si existe la propuesta
        $proposal = Proposal::find($id);
        if(!$proposal){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //Modificamos el estado de la propuesta
        $proposal->status = 1;
        $proposal->save();
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Listar tabla de propuestas para exportar
    function listProposalsToExport(Request $request){
        $array_proposals = Proposal::select('proposals.*', 'departments.name as department_name')
                        ->leftJoin('departments', 'departments.id', 'proposals.id_department')
                        ->leftJoin('proposals_bills', 'proposals.id', 'proposals_bills.id_proposal')
                        ->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill');

        if($request->get('type') == 1){
            if($request->get('num_proposal') != ''){
                $array_proposals = $array_proposals->where('proposals.id_proposal_custom', $request->get('num_proposal'));
            }

            if($request->get('select_consultant') != ''){
                $array_proposals = $array_proposals->where('proposals.id_user', $request->get('select_consultant'));
            }

            if($request->get('select_department') != ''){
                $array_proposals = $array_proposals->where('proposals.id_department', $request->get('select_department'));
            }

            if($request->get('date_from') != ''){
                $array_proposals = $array_proposals->where('proposals.date_proyect', '>=', $request->get('date_from'));
            }

            if($request->get('date_to') != ''){
                $array_proposals = $array_proposals->where('proposals.date_proyect', '<=', $request->get('date_to'));
            }
        }

        $array_proposals = $array_proposals->groupBy('proposals.id')
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
        
        $html = '';
        foreach($array_proposals as $proposal){
            $status_text = 'CERRADA';
            if($proposal->status == 2){
                $status_text = 'PENDIENTE DE VALIDAR';
            }
            $html .= '<tr data-row="0" class="datatable-row" style="left: 0px;">
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#id_user" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-dark">'.$proposal->id_user.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#proposal_custom" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-dark">'.$proposal['proposal_custom'].'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#status" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-dark">'.$status_text.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#code" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">--</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#name_contact" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.$proposal['name_contact'].'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#date_proyect" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.$proposal->date_proyect.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#total_amount" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.$proposal['total_amount'].'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#department_name" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.strtoupper($proposal->department_name).'</span>
                            </span>
                        </td>
                    </tr>';
        }

        $response['code'] = 1000;
        $response['array_proposals'] = $html;
        return response()->json($response);
    }

    //Descargar tabla propuestas csv
    function downloadListProposalsCsv(Request $request){    
        //Creamos las columnas del fichero
        $array_custom_calendars = array (
            array('Consultor', 'Propuesta', 'Estado', 'Código', 'Nombre del cliente', 'Fecha', 'Total', 'Portada')
        );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //Creamos las cabeceras
        $sheet->setCellValue('A1', 'Consultor');
        $sheet->setCellValue('B1', 'Propuesta');
        $sheet->setCellValue('C1', 'Estado');
        $sheet->setCellValue('D1', 'Código');
        $sheet->setCellValue('E1', 'Nombre del cliente');
        $sheet->setCellValue('F1', 'Fecha');
        $sheet->setCellValue('G1', 'Total');
        $sheet->setCellValue('H1', 'Departamento');

        //Consultamos los usuarios
        $array_proposals = Proposal::select('proposals.*', 'departments.name as department_name')
                        ->leftJoin('departments', 'departments.id', 'proposals.id_department')
                        ->leftJoin('proposals_bills', 'proposals.id', 'proposals_bills.id_proposal')
                        ->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill');

        if($request->get('type') == 1){
            if($request->get('num_proposal') != ''){
                $array_proposals = $array_proposals->where('proposals.id_proposal_custom', $request->get('num_proposal'));
            }

            if($request->get('select_consultant') != ''){
                $array_proposals = $array_proposals->where('proposals.id_user', $request->get('select_consultant'));
            }

            if($request->get('select_department') != ''){
                $array_proposals = $array_proposals->where('proposals.id_department', $request->get('select_department'));
            }

            if($request->get('date_from') != ''){
                $array_proposals = $array_proposals->where('proposals.date_proyect', '>=', $request->get('date_from'));
            }

            if($request->get('date_to') != ''){
                $array_proposals = $array_proposals->where('proposals.date_proyect', '<=', $request->get('date_to'));
            }
        }

        $array_proposals = $array_proposals->groupBy('proposals.id')
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

        foreach($array_proposals as $key => $proposal){
            $sheet->setCellValue('A'.($key+2), $proposal->id_user);
            $sheet->setCellValue('B'.($key+2), $proposal['proposal_custom']);
            if($proposal->status == 2){
                $sheet->setCellValue('C'.($key+2), 'PENDIENTE DE VALIDAR');
            }else{
                $sheet->setCellValue('C'.($key+2), 'CERRADA');
            }
            $sheet->setCellValue('D'.($key+2), '--');
            $sheet->setCellValue('E'.($key+2), $proposal['name_contact']);
            $sheet->setCellValue('F'.($key+2), $proposal->date_proyect);
            $sheet->setCellValue('G'.($key+2), $proposal['total_amount']);
            $sheet->setCellValue('H'.($key+2), $proposal['department_name']);
        }

        $writer = new Xlsx($spreadsheet);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.'propuestas.xlsx');
        $writer->save('php://output');
    }

    //Crear orden
    function createOrder(Request $request){
        //Comprobamos si existe la propuesta
        if (!$request->has('id_proposal')){
            $response['code'] = 1001;
            return response()->json($response);
        }

        $id_proposal = $request->get('id_proposal');

        if(empty($id_proposal)){
            $response['code'] = 1002;
            return response()->json($response);
        }

        //Consultamos la propuesta
        $proposal = Proposal::find($id_proposal);
        if(!$proposal){
            $response['code'] = 1003;
            return response()->json($response);
        }

        //Consultamos la empresa a la que pertenece la propuesta
        $company = Company::select('companies.*')->leftJoin('contacts', 'contacts.id_company', 'companies.id')->where('contacts.id', $proposal->id_contact)->first();

        if(empty($company->nif) || empty($company->address) || empty($company->id_hubspot) || $company->id_sage == null){
            $response['code'] = 1004;
            return response()->json($response);
        }

        //Creamos las orden
        $order = Order::create([
            'id_company' => $company->id,
            'id_proposal' => $proposal->id,
            'is_custom' => $proposal->is_custom,
            'discount' => $proposal->discount,
            'status' => 0,
            'advertiser' => $proposal->advertiser
        ]);

        //Consultamos las facturas de la propuesta
        $array_bills = ProposalBill::select('bills.*')->leftJoin('bills', 'bills.id', 'proposals_bills.id_bill')->where('proposals_bills.id_proposal', $proposal->id)->get();

        //Creamos un array de factuas de ordenes para más tarde crearlas en sage
        $array_bills_orders = array();

        $exist = false;
        $array_custom_services_order = array();

        //Recorremos las facturas y creamos las facturas de la orden
        foreach($array_bills as $bill){
            //Consultamos los articulos de la facturar para ver si tienen IVA o no
            $iva = 0;
            $array_service_bill = Service::select('services.*')->leftJoin('services_bills', 'services.id', 'services_bills.id_service')->where('services_bills.id_bill', $bill->id)->with('article')->get();
            foreach($array_service_bill as $service){
                $article = $service['article'];
                if(!$article->is_exempt){
                    $iva += $service->pvp * 0.21;
                }
            }

            $bill_order = BillOrder::create([
                'number' => $bill->id_bill_internal,
                'date' => $bill->date,
                'way_to_pay' => $bill->way_to_pay,
                'expiration' => $bill->expiration,
                'amount' => $bill->amount,
                'iva' => round($iva, 2),
                'id_order' => $order->id
            ]);

            $array_bills_orders[] = $bill_order;

            $custom_bill = $proposal->is_custom;

            if(!$custom_bill){
                //Consultamos el servicio de la factura
                $array_services = ServiceBill::select('services.*')
                                        ->leftJoin('services', 'services.id', 'services_bills.id_service')
                                        ->where('services_bills.id_bill', $bill->id)
                                        ->get();
                foreach($array_services as $service){
                    $new_service = Service::create([
                        'pvp' => $service->pvp,
                        'date' => $service->date,
                        'id_article' => $service->id_article
                    ]);

                    //Asociamos el servicio a la factura de la orden
                    $service_order = ServiceBillOrder::create([
                        'id_service' => $new_service->id,
                        'id_bill_order' => $bill_order->id
                    ]);
                }
            }

            if($custom_bill){
                if(!$exist){
                    //Consultamos los servicios de la factura
                    $array_services = ServiceBill::select('services.*')
                                            ->leftJoin('services', 'services.id', 'services_bills.id_service')
                                            ->where('services_bills.id_bill', $bill->id)
                                            ->get();

                    foreach($array_services as $service){
                        $new_service = Service::create([
                            'pvp' => $service->pvp,
                            'date' => $service->date,
                            'id_article' => $service->id_article
                        ]);

                        //Guardamos los nuevos servicios para utilizarlos en las siguientes facturas
                        $array_custom_services_order[] = $new_service;
                        
                        //Asociamos el servicio a la factura de la orden
                        $service_order = ServiceBillOrder::create([
                            'id_service' => $new_service->id,
                            'id_bill_order' => $bill_order->id
                        ]);
                    }

                }else{
                    foreach($array_custom_services_order as $service){
                        //Asociamos el servicio a la factura de la orden
                        $service_order = ServiceBillOrder::create([
                            'id_service' => $service->id,
                            'id_bill_order' => $bill_order->id
                        ]);
                    }
                }

                $exist = true;
            }  
        }

        //Añadimos los consultores a la orden
        $array_consultants_proposals = ConsultantProposal::where('id_proposal', $proposal->id)->get();
        foreach ($array_consultants_proposals as $consultant_proposal) {
            ConsultanOrder::create([
                'id_consultant' => $consultant_proposal->id_consultant,
                'id_order' => $order->id,
                'percentage' => $consultant_proposal->percentage
            ]);
        }

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Enviar orden a Sage
    function sendOrderToSage($order){
        $requ_curls = new CurlController();

        //Consultamos las empresas registradas
        $url = 'https://sage200.sage.es/api/sales/SalesInvoices';
        $params['CompanyId'] = config('constants.id_company_sage');
        $params['CustomerId'] = '6972a7f8-a876-49fd-856b-19b86d5425f5'; // Esto sería la empresa o contacto a quien se lo asociamos
        $params['InvoiceType'] = 0; //Lo ponemos de momento Undefined
        $params['TaxNumber'] = 'C28325769'; // Es el nuestro inventado de momento 
        $params['TaxNumberType'] = 1;
        $custom_date = date('Y-m-dTH:i:s:').substr((string)microtime(), 2, 3).'Z';
        $params['Timestamp'] = $custom_date;
        $line['ProductId'] = 'fa4b8984-e114-413d-8d7a-b77a1e1947cc';
        $line['ProductId'] = $custom_date;
        $params['Lines'][] = $line;
        $response = json_decode($requ_curls->postSageCurl($url, $params, 3)['response'], true);
        error_log(print_r($response, true));
    }
}
