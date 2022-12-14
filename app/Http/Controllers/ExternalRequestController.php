<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Company;
use App\Models\Contact;
use App\Models\DealHubspot;

class ExternalRequestController extends Controller
{
    //Guardar empresas desde Hubspot
    function saveCompaniesFromHubspot(Request $request){
        //Comprobamos si existe la empresa
        $company = Company::where('id_hubspot', $request->get('hub_id'))->first();
        $address = $request->get('direccion').', '.$request->get('direccion_2').', '.$request->get('ciudad').', '.$request->get('provincia').', '.$request->get('cp');
        if($company){
            $company->name = $request->get('name');
            $company->nif = $request->get('cifnif');
            $company->address = $address;
            $company->save();

        }else{
            $company = Company::create([
                'name' => $request->get('name'),
                'nif' => $request->get('cifnif'),
                'address' => $address,
                'id_hubspot' => $request->get('hub_id')
            ]);
        }
    }

    //Guardar contactos desde Hubspot
    function saveContactsFromHubspot(Request $request){
        $requ_curls = new CurlController();
        $hub_id = $request->get('hub_id');
        //Comprobamos si existe la empresa
        $contacts = Contact::where('id_hubspot', $hub_id)->first();
        if($contacts){
            $contacts->name = $request->get('firstname');
            $contacts->surnames = $request->get('lastname');
            $contacts->email = $request->get('email');
            $contacts->phone = $request->get('phone');
            $contacts->save();
            
        }else{
            Log::info('$hub_id: ' . $hub_id);

            //Consultamos la empresa asociada a este contacto
            $url_contacts_association = 'https://api.hubapi.com/crm/v4/objects/contacts/'.$hub_id.'/associations/companies?limit=10&archived=false';
            $stop_companies_association_contacts = 0;
            while($stop_companies_association_contacts == 0){
                $array_companies_association_contacts_hubspot_obj = json_decode($requ_curls->getCurl($url_contacts_association, 1)['response'], true);
                foreach($array_companies_association_contacts_hubspot_obj["results"] as $company_association_contacts){
                    Log::info('$company_association_contacts: ' . $company_association_contacts['toObjectId']);

                    $id_company = $company_association_contacts['toObjectId'];
                    //Consultamos el contacto en la bd
                    $company = Company::where('id_hubspot', $id_company)->first();
                    if($company){
                        $stop_companies_association_contacts = 1;
                    }
                }

                if($stop_companies_association_contacts == 0){
                    if(!isset($company_association_contacts['paging'])){
                        $stop_companies_association_contacts = 1;
                        
                    }else{
                        $url = $company_association_contacts['paging']['next']['link'];
                    }
                }
            }

            $contact = Contact::create([
                'name' => $request->get('firstname'),
                'surnames' => $request->get('lastname'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'id_company' => $company->id,
                'id_hubspot' => $hub_id
            ]);
        }
    }

    //Guardar negocio de un contacto
    function saveDealFromHubspot(Request $request){
        $requ_curls = new CurlController();
        $hub_id = $request->get('hub_id');
        //Comprobamos si existe la empresa
        $deal = DealHubspot::where('id_hubspot', $hub_id)->first();

        if(!$deal){
            //Consultamos la empresa de hubspot asociada al negocio y miramos nuestra empresa
            $url_deals_association_company = 'https://api.hubapi.com/crm/v4/objects/deal/'.$hub_id.'/associations/company';
            $company_hubspot = json_decode($requ_curls->getCurl($url_deals_association_company, 1)['response'], true);
            $hub_id_company = $company_hubspot['results'][0]['toObjectId'];

            //Buscamos una empresa con este id de hub en nuestra bd
            $company = Company::where('id_hubspot', $hub_id_company)->first();
            if(!$company){
                //No existe
                return;
            }

            //Consultamos el contacto de hubspot asociada al negocio y miramos nuestro contacto
            $url_deals_association_contact = 'https://api.hubapi.com/crm/v4/objects/deal/'.$hub_id.'/associations/contact';
            $contact_hubspot = json_decode($requ_curls->getCurl($url_deals_association_contact, 1)['response'], true);
            $hub_id_contact = $contact_hubspot['results'][0]['toObjectId'];

            //Buscamos una empresa con este id de hub en nuestra bd
            $contact = Contact::where('id_hubspot', $hub_id_contact)->first();
            if(!$contact){
                //No existe
                return;
            }

            //Creamos el negocio
            DealHubspot::create([
                'name' => $request->get('name'),
                'id_hubspot' => $request->get('hub_id'),
                'id_contact' => $contact->id,
                'id_company' => $company->id,
            ]);
            
        }
    }

    //Consultar producto de Sage
    function getProductSage(Request $request){
        //Creamos un objeto para el controller curl
        $requ_curls = new CurlController();
        $company = config('constants.id_company_sage');

        $url = 'https://sage200.sage.es/api/sales/Products?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Code%20eq%20%27'.$request->get('code_sage').'%27';
        $response = json_decode($requ_curls->getSageCurl($url)['response'], true);
        if(count($response['value']) > 0){
            $array_products = $response['value'];
            foreach($array_products as $product){
                return $product['Id'];
            }
        }
    }

    //Generar albar??n en Sage
    function generateDeliveryNoteSage(Request $request){
        //Creamos un objeto para el controller curl
        $requ_curls = new CurlController();
        $company = config('constants.id_company_sage');

        $url = 'https://sage200.sage.es/api/sales/SalesDeliveryNotes?api-version=1.0';

        //Creamos el objeto que vamos a pasar a llamada
        $delivery_note['CompanyId'] = $company;
        $delivery_note['CustomerId'] = $request->get('customer_id');
        $delivery_note['Number'] = $request->get('number');//Date('y').$request->get('id_bill_order').$request->get('id_order');
        $delivery_note['Period'] = Date('Y');
        $delivery_note['Serie'] = 'A';
        $delivery_note['TotalNet'] = floatval($request->get('amount'));
        //$delivery_note['TotalTaxes'] = floatval($request->get('amount')) * 0.21;
        //$delivery_note['Total'] = floatval($request->get('amount')) * 1.21;

        //Creamos el objeto de Lines
        $array_sage_products = $request->get('array_sage_products');
        $array_lines = array();
        foreach($array_sage_products as $sage_product){
            $line['ProductId'] = $sage_product['id'];
            $line['Price'] = floatval($sage_product['pvp']);
            $line['Total'] = floatval($sage_product['pvp']);
            $line['TotalNet'] = floatval($sage_product['pvp']);
            $line['Quantity'] = 1;
            $line['QuantityMeasureUnit'] = 1;
            $array_lines[] = $line;
        }

        $delivery_note['Lines'] = $array_lines;

        //error_log(print_r($delivery_note, true));

        $url = 'https://sage200.sage.es/api/sales/SalesDeliveryNotes?api-version=1.0';
        $response = json_decode($requ_curls->postSageCurl($url, $delivery_note)['response'], true);
        if($response == null){
            //Paramos 4 segundos para que aparezca en el listado
            sleep(4);
            //Consultamos el albar??n creado
            $response = json_decode($requ_curls->getSageCurl($url.'&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Number%20eq%20'.$request->get('number').'&$expand=*')['response'], true);
            error_log($url.'&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Number%20eq%20'.$request->get('number'));
            $delivery_note_obj = $response['value'][0];
            //Comenzamos a crear la orden
            $lines = $delivery_note_obj['Lines'];
            $array_lines_to_order = array();
            foreach($lines as $line){
                $line_obj['Id'] = $line['Id'];
                $line_obj['ProductId'] = $line['ProductId'];
                $line_obj['Price'] = $line['Price'];
                $line_obj['Total'] = $line['Total'];
                $line_obj['TotalNet'] = $line['TotalNet'];
                $line_obj['Quantity'] = $line['Quantity'];
                $line_obj['QuantityMeasureUnit'] = $line['QuantityMeasureUnit'];
                $array_lines_to_order[] = $line_obj;
            }
            $order['CompanyId'] = $company;
            $order['CustomerId'] = $request->get('customer_id');
            $order['Number'] = $request->get('number');
            $order['Period'] = Date('Y');
            $order['Serie'] = 'A';
            $order['TaxNumberType'] = '1';
            $order['InvoiceType'] = '1';
            $order['TotalNet'] = floatval($request->get('amount'));
            $order['Lines'] = $array_lines_to_order;
            $url = 'https://sage200.sage.es/api/sales/SalesInvoices?api-version=1.0';
            $response = json_decode($requ_curls->postSageCurl($url, $order)['response'], true);
        }
    }
}
