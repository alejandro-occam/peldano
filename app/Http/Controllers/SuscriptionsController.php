<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarMagazine;
use App\Models\Calendar;
use App\Models\Article;
use App\Models\Suscription;
use App\Models\Company;

class SuscriptionsController extends Controller
{
    //Listar suscripciones
    function listSuscriptions(Request $request){
        //Elementos para la paginación 
        $pagination = $request->get('pagination');
        $query = $request->get('query');
        $start = 0;
        $skip = $pagination['perpage'];
        if ($pagination['page'] != 1) {
            $start = ($pagination['page'] - 1) * $pagination['perpage'];
            //Consultamos si hay tantos registros como para empezar en el numero de $start
            $num_suscriptions = Suscription::count();
            if ($start >= $num_suscriptions) {
                $skip = $skip - 1;
                $start = $start - 10;
                if ($start < 0) {
                    $start = 0;
                }
            }
        }

        //Recogemos los datos de los filtros
        $select_magazine = $request->get('select_magazine');
        $select_payment_method = $request->get('select_payment_method');
        $num_finish = $request->get('num_finish');

        $array_suscriptions = Suscription::select('suscriptions.*', 'articles.name as article_name', 'calendars.name as calendars_magazines_name', 'contacts.name as contacts_name', 'contacts.surnames as contacts_surname')
                                            ->leftJoin('articles', 'articles.id', 'suscriptions.id_article')
                                            ->leftJoin('calendars', 'calendars.id', 'suscriptions.id_calendar')
                                            ->leftJoin('calendars_magazines', 'calendars.id', 'calendars_magazines.id_calendar')
                                            ->leftJoin('contacts', 'contacts.id', 'suscriptions.id_contact');
        
        if($select_magazine != '0'){                    
            $array_suscriptions = $array_suscriptions->where('calendars_magazines.id', $select_magazine);
        }

        if($select_payment_method != '0'){                    
            $array_suscriptions = $array_suscriptions->where('suscriptions.payment_method', $select_payment_method);
        }

        if(!empty($num_finish)){                    
            $array_suscriptions = $array_suscriptions->where('num_finish', $num_finish);
        }

        $array_suscriptions = $array_suscriptions->skip($start)
                                                    ->take($skip)
                                                    ->groupBy('suscriptions.id')
                                                    ->get();

        $rowIds[] = array();
        foreach($array_suscriptions as $suscription){
            $rowIds[] = $suscription->id;
            $suscription['RecordID'] = $suscription->id;
        }

        $tota_suscriptions = Suscription::count();

        //Devolución de la llamada con la paginación
        $meta['rowIds'] = $rowIds;
        $meta['page'] = $pagination['page'];

        if ($tota_suscriptions < 1) {
            $meta['page'] = 1;
        }

        $meta['pages'] = 1;
        if (isset($pagination['pages'])) {
            $meta['pages'] = $pagination['pages'];
        }
        $meta['perpage'] = $pagination['perpage'];
        $meta['total'] = $tota_suscriptions;
        $meta['sort'] = 'asc';
        $meta['field'] = 'id';
        $response['meta'] = $meta;
        $response['data'] = $array_suscriptions;
        return response()->json($response);
    }

    //Listar calendarios
    function listCalendars(){
        $array_calendars = Calendar::orderBy('name')->get();

        $response['array_calendars'] = $array_calendars;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Listar calendarios de revistas
    function listCalendarsMagazines($id = null){
        $array_calendars_magazines = CalendarMagazine::select('calendars_magazines.*' ,'calendars.name as name_calendar')
                                                        ->leftJoin('calendars', 'calendars.id', 'calendars_magazines.id_calendar');
             
        error_log('que pasa');
        if(isset($id) && $id != null){
            error_log('hola');
            $array_calendars_magazines = $array_calendars_magazines->where('calendars_magazines.id_calendar', $id);
        }

        $array_calendars_magazines = $array_calendars_magazines->get();

        $response['array_calendars_magazines'] = $array_calendars_magazines;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Listar articulos según el id_project
    function listArticles($id){
        //Consultamos los artículos en base al calendario seleccionado
        $array_articles = Article::select('articles.*')
                                    ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                                    ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                                    ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                                    ->leftJoin('calendars', 'calendars.id_project', 'projects.id')
                                    ->where('calendars.id', $id)
                                    ->groupBy('articles.id')
                                    ->orderBy('articles.name')
                                    ->get();
                                    
        $response['array_articles'] = $array_articles;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Añadir suscripción
    function addSuscription(Request $request){
        if (!$request->has('id_client') || !$request->has('id_calendar') || !$request->has('id_article') || !$request->has('num') || !$request->has('num_finish') || !$request->hash('id_payment_method')) {
            $response['code'] = 1001;
            return response()->json($response);
        }

        $id_client = $request->get('id_client');
        $id_calendar = $request->get('id_calendar');
        $id_article = $request->get('id_article');
        $num = $request->get('num');
        $num_finish = $request->get('num_finish');
        $id_payment_method = $request->get('id_payment_method');

        if(!isset($id_client) || empty($id_client) || !isset($id_calendar) || empty($id_calendar) || !isset($id_article) || empty($id_article) || !isset($num) || empty($num) || !isset($num_finish) || empty($num_finish) || !isset($id_payment_method) || empty($id_payment_method)){
            $response['code'] = 1001;
            return response()->json($response);
        }

        $suscription = Suscription::create([
            'id_contact' => $id_client,
            'id_calendar' => $id_calendar,
            'id_article' => $id_article,
            'num' => $num,
            'num_finish' => $num_finish,
            'id_payment_method' => $id_payment_method
        ]);

        $this->generateDeliveryAndInvoice($suscription);

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Actualizar suscripción
    function updateSuscription(Request $request){
        if (!$request->has('array_suscriptions') || !$request->has('num') || !$request->has('num_finish') || !$request->hash('id_payment_method')){
            $response['code'] = 1001;
            return response()->json($response);
        }

        $num = $request->get('num');
        $num_finish = $request->get('num_finish');
        $array_suscriptions_custom = $request->get('array_suscriptions');
        $id_payment_method = $request->get('id_payment_method');
        $array_suscriptions = explode(",", $array_suscriptions_custom);

        if(!isset($num) || empty($num) || !isset($num_finish) || empty($num_finish) || !isset($array_suscriptions) || empty($array_suscriptions) || !isset($id_payment_method) || empty($id_payment_method)){
            $response['code'] = 1001;
            return response()->json($response);
        }

        foreach($array_suscriptions as $suscription){
            //Consultamos la suscripción
            $suscription = Suscription::find($suscription);
            if($suscription){
                $suscription->num = $num;
                $suscription->num_finish = $num_finish;
                $suscription->id_payment_method = $id_payment_method;
                $suscription->save();
                $this->generateDeliveryAndInvoice($suscription);
            }
            
        }

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Eliminar suscriptor
    function deleteSuscription($id){
        //Consultamos si existe el suscriptor
        $suscription = Suscription::find($id);
        if(!$suscription){
            $response['code'] = 1001;
            return response()->json($response);
        }

        $suscription->delete();

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Generar albarán y factura para la suscripción
    function generateDeliveryAndInvoice($suscription){
        //Creamos un objeto para el controller ExternalRequest
        $requ_external_request = new ExternalRequestController();

        //Creamos el objeto request
        $request = new \Illuminate\Http\Request();

        error_log('id_contact: '.$suscription->id_contact);

        //Consultamos la empresa a la que pertenece la suscripción
        $company = Company::select('companies.*')->leftJoin('contacts', 'contacts.id_company', 'companies.id')->where('contacts.id', $suscription->id_contact)->first();

        //Creamos un array para guardar los id_sage de cada artículo-producto
        $array_sage_products = array();

        $article = Article::find($suscription->id_article);

        //Creamos un array para guardar los id_sage de cada artículo-producto
        $array_sage_products = array();

        //Consultamos el id_sage del artículo
        $request->replace(['code_sage' => $article->id_sage]);
        $id_sage = $requ_external_request->getProductSage($request);
        $product['id'] = $id_sage;
        $product['pvp'] = $article->pvp;
        $array_sage_products[] = $product;

        $number = Date('Y').$company->id.$suscription->id.$suscription->num.$suscription->num_finish;

        error_log(print_r($array_sage_products, true));
        //Generamos el albarán en Sage
        $request->replace(['array_sage_products' => $array_sage_products, 'customer_id' => $company->id_sage, 'amount' => $article->pvp, 'number' => $number]);

        $invoice_custom = $requ_external_request->generateDeliveryNoteSageSuscription($request);
    }
}
