<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarMagazine;
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

        $array_suscriptions = Suscription::select('suscriptions.*', 'articles.name as article_name', 'calendars_magazines.title as calendars_magazines_name', 'contacts.name as contacts_name', 'contacts.surnames as contacts_surname')
                                            ->leftJoin('articles', 'articles.id', 'suscriptions.id_article')
                                            ->leftJoin('calendars_magazines', 'calendars_magazines.id', 'suscriptions.id_calendar')
                                            ->leftJoin('contacts', 'contacts.id', 'suscriptions.id_contact')
                                            ->skip($start)
                                            ->take($skip)
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

    //Listar calendarios de revistas
    function listCalendarsMagazines(){
        $array_calendars_magazines = CalendarMagazine::select('calendars_magazines.*' ,'calendars.name as name_calendar')
                                                        ->leftJoin('calendars', 'calendars.id', 'calendars_magazines.id_calendar')
                                                        ->get();

        $response['array_calendars_magazines'] = $array_calendars_magazines;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Listar articulos según el id_project
    function listArticles($id){
        //Consultamos el calendar_magazine
        $calendar_magazine = CalendarMagazine::select('calendars.id_project')
                                            ->leftJoin('calendars', 'calendars.id', 'calendars_magazines.id_calendar')
                                            ->where('calendars_magazines.id', $id)->first();

        $array_articles = Article::select('articles.*')
                                    ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                                    ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                                    ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                                    ->where('projects.id', $calendar_magazine['id_project'])
                                    ->get();
        
        $response['array_articles'] = $array_articles;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Añadir suscripción
    function addSuscription(Request $request){
        if (!$request->has('id_client') || !$request->has('id_calendar_magazine') || !$request->has('id_article') || !$request->has('num')) {
            $response['code'] = 1001;
            return response()->json($response);
        }

        $id_client = $request->get('id_client');
        $id_calendar_magazine = $request->get('id_calendar_magazine');
        $id_article = $request->get('id_article');
        $num = $request->get('num');

        if(!isset($id_client) || empty($id_client) || !isset($id_calendar_magazine) || empty($id_calendar_magazine) || !isset($id_article) || empty($id_article) || !isset($num) || empty($num)){
            $response['code'] = 1001;
            return response()->json($response);
        }

        $suscription = Suscription::create([
            'id_contact' => $id_client,
            'id_calendar' => $id_calendar_magazine,
            'id_article' => $id_article,
            'num' => $num
        ]);

        //$this->generateDeliveryAndInvoice($suscription);

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Actualizar suscripción
    function updateSuscription(Request $request){
        if (!$request->has('array_suscriptions') || !$request->has('num')){
            $response['code'] = 1001;
            return response()->json($response);
        }

        $num = $request->get('num');
        $array_suscriptions_custom = $request->get('array_suscriptions');
        $array_suscriptions = explode(",", $array_suscriptions_custom);

        if(!isset($num) || empty($num) || !isset($array_suscriptions) || empty($array_suscriptions)){
            $response['code'] = 1001;
            return response()->json($response);
        }

        foreach($array_suscriptions as $suscription){
            //Consultamos la suscripción
            $suscription = Suscription::find($suscription);
            if($suscription){
                $suscription->num = $num;
                $suscription->save();
            }
        }

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

        $number = Date('Y').$company->id.$suscription->id;

        error_log(print_r($array_sage_products, true));
        //Generamos el albarán en Sage
        $request->replace(['array_sage_products' => $array_sage_products, 'customer_id' => $company->id_sage, 'amount' => $article->pvp, 'number' => $number]);

        $invoice_custom = $requ_external_request->generateDeliveryNoteSageSuscription($request);
    }
}
