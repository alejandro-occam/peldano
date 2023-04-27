<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Service;
use App\Models\SerBillvice;
use App\Models\Article;
use App\Models\Batch;
use App\Models\Chapter;
use App\Models\Project;
use App\Models\Channel;
use App\Models\Section;
use App\Models\ServiceBill;
use App\Models\Proposal;
use App\Models\Bill;
use App\Models\Department;
use App\Models\ProposalBill;
use App\Http\Controllers\CurlController;
use Illuminate\Support\Facades\Log;


class ProposalsInfoges extends Controller
{

    //Crear propuesta
    function createProposal(Request $request){
        //Comprobamos se manda el email del ordenante
        if (!$request->has('email')){
            $response['code'] = 1004;
            return response()->json($response);
        }

        //Comprobamos se manda el email del contacto
        if (!$request->has('email_contact')){
            $response['code'] = 1005;
            return response()->json($response);
        }

        //$id_company = $request->get('id_company');
        $email_contact = $request->get('email_contact');
        $email = $request->get('email');

        if(empty($email)){
            $response['code'] = 1004;
            return response()->json($response);
        }

        if(empty($email_contact)){
            $response['code'] = 1005;
            return response()->json($response);
        }
    
        //Consultamos el usuario
        $user = User::where('email', $email)->first();

        //Consultamos el contact
        $contact = Contact::where('email', $email_contact)->first();
        if(!$contact){
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

            if(!$article_sage){
                $response['code'] = 1002;
                $response['msg'] = 'No se encuentra un artículo en la bd con este id_sage';
                return response()->json($response);
            }
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

            $num_custom_invoices = $request->get('num_custom_invoices');
            $custom_bill = false;
            if(isset($num_custom_invoices)){
                if($num_custom_invoices > 0){
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

                    $exist = false;
                    if($service->date == $bill->date){
                        foreach($bill_obj['array_id_sage_articles'] as $id_sage_article){
                            if($id_sage_article == $article->id_sage){
                                $service_bill = ServiceBill::where('id_service', $service->id)->where('id_bill', $bill->id)->first();
                                if(!$service_bill){
                                    ServiceBill::create([
                                        'id_service' => $service->id,
                                        'id_bill' => $bill->id,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
            if($custom_bill){
                error_log('entro aquí');
                error_log('array_services_aux: '.print_r($array_services_aux, true));
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
        $proposal_submission_settings = $request->get('proposal_submission_settings');
        //$id_proposal_custom = sprintf('%08d', ($count_proposal + 1));
        $id_proposal_custom = ($count_proposal + 1);
        $id_department = $request->get('id_department');

        $proposal = Proposal::create([
            'id_proposal_custom' => $id_proposal_custom,
            'id_user' => $user->id,
            'id_contact' => $contact->id,
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
            'id_department' => $id_department,
            'is_custom' => $custom_bill
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
        if (!$request->has('id_proposal') || !$request->has('email')){
            $response['code'] = 1001;
            return response()->json($response);
        }

        $id_proposal = $request->get('id_proposal');
        $email = $request->get('email');

        if(empty($id_proposal) || empty($email)){
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

        $array_services_aux = array();
        //Consultamos los artículos
        foreach($bill_obj['articles'] as $article){
            //Consultamos el artículo por el id_sage
            $article_sage = Article::where('id_sage', $article['id_sage_article'])->first();

            if(!$article_sage){
                $response['code'] = 1002;
                $response['msg'] = 'No se encuentra un artículo en la bd con este id_sage';
                return response()->json($response);
            }
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

            if(!$proposal->is_custom){
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

                    /*if($service->date == $bill->date && $bill_obj['id_chapter'] == $batch->id_chapter){
                        ServiceBill::create([
                            'id_service' => $service->id,
                            'id_bill' => $bill->id,
                        ]);
                    }*/

                    if($service->date == $bill->date){
                        foreach($bill_obj['array_id_sage_articles'] as $id_sage_article){
                            if($id_sage_article == $article->id_sage){
                                $service_bill = ServiceBill::where('id_service', $service->id)->where('id_bill', $bill->id)->first();
                                if(!$service_bill){
                                    ServiceBill::create([
                                        'id_service' => $service->id,
                                        'id_bill' => $bill->id,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            if($proposal->is_custom){
                foreach($array_services_aux as $service){
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
        $user = User::where('email', $email)->first();
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

    //Crear artículo
    function createArticle(Request $request){
        if (!$request->has('id_batch') || !$request->has('name') || !$request->has('name_eng') || !$request->has('price')) {
            $response['code'] = 1001;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        $id_batch = $request->get('id_batch');
        $name = $request->get('name');
        $name_eng = $request->get('name_eng');
        $price = $request->get('price');

        if (!isset($id_batch) || empty($id_batch) || !isset($name) || empty($name) || !isset($price) || empty($price)) {
            $response['code'] = 1002;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        if(!isset($name_eng) || empty($name_eng)){
            $name_eng = $name.'_eng';
        }

        //Consultamos si existe el producto
        $batch = Batch::find($id_batch);
        if(!$batch){
            $response['code'] = 1003;
            return response()->json($response);
        }

        //Consultamos el Capítulo, Proyecto, Canal, Sección y Departamento del artículo
        $chapter = Chapter::find($batch->id_chapter);
        if(!$chapter){
            $response['code'] = 1003;
            return response()->json($response);
        }

        //Consultamos el Departamento, Sección, Canal y Proyecto del lote
        $project = Project::find($chapter->id_project);
        if(!$project){
            $response['code'] = 1003;
            return response()->json($response);
        }

        $channel = Channel::find($project->id_channel);
        if(!$channel){
            $response['code'] = 1003;
            return response()->json($response);
        }

        $section = Section::find($channel->id_section);
        if(!$section){
            $response['code'] = 1003;
            return response()->json($response);
        }

        $department = Department::find($section->id_department);
        if(!$department){
            $response['code'] = 1003;
            return response()->json($response);
        }

        //Creamos un objeto para el controller curl
        $requ_curls = new CurlController();

        //Consultamos el product family del artículo
        $company = config('constants.id_company_sage');
        $url = 'https://sage200.sage.es/api/sales/ProductFamilies?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Name%20eq%20%27'.
                        $department->nomenclature."-".
                        $section->nomenclature."-".
                        $channel->nomenclature."-".
                        $project->nomenclature."-".
                        $chapter->nomenclature."-".
                        $batch->nomenclature.
                        '%27';

        $data = json_decode($requ_curls->getSageCurl($url)['response'], true);
        $product_family_id = '';
        
        if(count($data['value']) == 0){
            //Consultamos si existe el product family por code
            $url = 'https://sage200.sage.es/api/sales/ProductFamilies?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Code%20eq%20%27'.
            $channel->id.
            $project->id.
            $chapter->id.
            $batch->id.
            '%27';
            $data = json_decode($requ_curls->getSageCurl($url)['response'], true);

            if(count($data['value']) == 0){
                //Si no existe creamos un product family
                $param['CompanyId'] = $company;
                $param['Name'] = $department->nomenclature."-".$section->nomenclature."-".$channel->nomenclature."-".$project->nomenclature."-".$chapter->nomenclature."-".$batch->nomenclature;
                $param['Code'] = $channel->id.$project->id.$chapter->id.$batch->id;
                $url = 'https://sage200.sage.es/api/sales/ProductFamilies?api-version=1.0';
                $response = json_decode($requ_curls->postSageCurl($url, $param)['response'], true);
                Log::info('$param: ' . print_r($param, true));
                Log::info('$response: ' . print_r($response, true));
                
                $product_family_id = $response['Id'];
                $product_family_code = $response['Code'];

            }else{
                $array_product_family = $data['value'];
                foreach($array_product_family as $product_family){
                    $product_family_id = $product_family['Id'];
                    $product_family_code = $product_family['Code'];
                }
            }

        }else{
            $array_product_family = $data['value'];
            foreach($array_product_family as $product_family){
                $product_family_id = $product_family['Id'];
                $product_family_code = $product_family['Code'];
            }
        }

        //Consultamos si existe el artículo con este product family y nombre
        $custom_name = str_replace(' ', '%20', $name);
        $url = 'https://sage200.sage.es/api/sales/Products?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Name%20eq%20%27'.$custom_name.'%27%20and%20FamilyId%20eq%20%27'.$product_family_id.'%27';
        $data_product = json_decode($requ_curls->getSageCurl($url)['response'], true);
        if(count($data_product['value']) == 0){
            //Si no existe creamos un product family
            $param['CompanyId'] = $company;
            $param['Name'] = $name;
            $param['SalesPriceIncludingTaxes'] = false;
            $param['SalesPrice'] = $price;
            $param['FamilyId'] = $product_family_id;
            $url = 'https://sage200.sage.es/api/sales/Products?api-version=1.0';
            $response = json_decode($requ_curls->postSageCurl($url, $param)['response'], true);
            error_log(print_r($response, true));
            $product_code = $response['Code'];

        }else{
            $response['code'] = 1004;
            return response()->json($response);
        }

        $article = Article::create([
            'name' => $name,
            'english_name' => $name_eng,
            'pvp' => $price,
            'id_batch' => $id_batch,
            'id_sage' => $product_code,
            'id_family_sage' => $product_family_code
        ]);

        $response['id_article'] = $article->id;
        $response['code_sage'] = $product_code;
        $response['code'] = 1000;
        return response()->json($response);
    }
}