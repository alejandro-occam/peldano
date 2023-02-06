<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Company;
use App\Models\Contact;
use App\Models\DealHubspot;
use App\Models\Payment;

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

        //Consultamos el id_sage de la empresa
        $requ_curls = new CurlController();
        $company_sage = config('constants.id_company_sage');
        if($company->nif != null){
            $url = 'https://sage200.sage.es:443/api/sales/Customers?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company_sage.'%27%20and%20TaxNumber%20eq%20%27'.$company->nif.'%27';
            $data = json_decode($requ_curls->getSageCurl($url)['response'], true);
            if(isset($data['diagnosis'][0]['errorCode'])){
                sleep(30);
                $url = 'https://sage200.sage.es:443/api/sales/Customers?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20TaxNumber%20eq%20%27'.$company->nif.'%27';
                $data = json_decode($requ_curls->getSageCurl($url)['response'], true);
                if(isset($data['value'][0]['Id'])){
                    $company->id_sage = $data['value'][0]['Id'];
                    $company->save();
                }

            }else if(isset($data['value'][0]['Id'])){
                $company->id_sage = $data['value'][0]['Id'];
                $company->save();
            }
        }    

        //Consultamos si se ha añadido el id_sage y si no buscamos por nombre
        if($company->id_sage == null){
            $name_without_space = str_replace(" ", "%20", $company->name);
            $url = 'https://sage200.sage.es:443/api/sales/Customers?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company_sage.'%27%20and%20Name%20eq%20%27'.$name_without_space.'%27';
            $data = json_decode($requ_curls->getSageCurl($url)['response'], true);
            if(isset($data['diagnosis'][0]['errorCode'])){
                sleep(30);
                $url = 'https://sage200.sage.es:443/api/sales/Customers?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company_sage.'%27%20and%20Name%20eq%20%27'.$name_without_space.'%27';
                $data = json_decode($requ_curls->getSageCurl($url)['response'], true);
                if(isset($data['value'][0]['Id'])){
                    $company->id_sage = $data['value'][0]['Id'];
                    $company->save();
                }

            }else if(isset($data['value'][0]['Id'])){
                $company->id_sage = $data['value'][0]['Id'];
                $company->save();
            }
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

    //Generar albarán en Sage
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
        $delivery_note['TotalNet'] = floatval(round($request->get('amount'), 2));

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

        error_log(print_r($delivery_note, true));

        $url = 'https://sage200.sage.es/api/sales/SalesDeliveryNotes?api-version=1.0';
        $response = json_decode($requ_curls->postSageCurl($url, $delivery_note)['response'], true);
        if($response == null){
            //Paramos 4 segundos para que aparezca en el listado
            sleep(4);
            //Consultamos el albarán creado
            $response = json_decode($requ_curls->getSageCurl($url.'&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Number%20eq%20'.$request->get('number').'and%20Period%20eq%20'.$delivery_note['Period'].'&$expand=*')['response'], true);
            error_log($url.'&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Number%20eq%20'.$request->get('number').'and%20Period%20eq%20'.$delivery_note['Period']);
            error_log(print_r($response, true));
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
            $order['TotalNet'] = $delivery_note_obj['TotalNet'];//floatval($request->get('amount'));
            $order['TotalTaxes'] = $delivery_note_obj['TotalTaxes']; //floatval($request->get('amount')) * 0.21;
            $order['Total'] = $delivery_note_obj['Total'];//floatval($request->get('amount')) * 1.21;
            $order['Lines'] = $array_lines_to_order;
            $url = 'https://sage200.sage.es/api/sales/SalesInvoices?api-version=1.0';
            $response = json_decode($requ_curls->postSageCurl($url, $order)['response'], true);

            //Paramos 4 segundos para que aparezca en el listado
            sleep(4);

            //Consultamos la factura creada creado
            $response = json_decode($requ_curls->getSageCurl($url.'&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Number%20eq%20'.$request->get('number').'and%20Period%20eq%20'.$delivery_note['Period'].'&$expand=*')['response'], true);
            error_log($url.'&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Number%20eq%20'.$request->get('number').'and%20Period%20eq%20'.$delivery_note['Period'].'&$expand=*');
            error_log('Invoice '.print_r($response, true));
            $invoice_obj = $response['value'][0];
            $id_invoice = $invoice_obj['Id'];
            $invoice_custom['Id'] = $id_invoice;
            $receipt_order_sage = $invoice_obj['Receipts'][0]['Id'];
            $invoice_custom['receipt_order_sage'] = $receipt_order_sage;
            return $invoice_custom;
        }
    }

    //Consultamos el estado de un recibo
    function getReceiptSage(Request $request){
        error_log($request->get('receipt_order_sage'));
        //Creamos un objeto para el controller curl
        $requ_curls = new CurlController();
        $company = config('constants.id_company_sage');

        $url = 'https://sage200.sage.es/api/sales/SalesReceipts?api-version=1.0';
        //Consultamos el recibo de la factura
        error_log($url.'&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Id%20eq%20%27'.$request->get('receipt_order_sage').'%27');
        $response = json_decode($requ_curls->getSageCurl($url.'&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Id%20eq%20%27'.$request->get('receipt_order_sage').'%27')['response'], true);
        error_log(print_r($response, true));
        $receipt_obj = $response['value'][0];
        
        if($receipt_obj['Status'] == 'Paid'){
            //Consultamos la orden de Pago
            $url = 'https://sage200.sage.es/api/sales/SalesPayments?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20ReceiptId%20eq%20%27'.$receipt_obj['Id'].'%27';
            $response = json_decode($requ_curls->getSageCurl($url)['response'], true);
            error_log(print_r($response, true));
            $sale_payment = $response['value'][0];
            if(count($sale_payment) > 0){
                $id_sale_payment_sage = $sale_payment['Id'];
                $amount = $sale_payment['TotalAmount'];
                Payment::create([
                    'id_bill_order' => $request->get('id_bill_order'),
                    'amount' => $amount,
                    'id_sage' => $id_sale_payment_sage
                ]);
            }
        }
    }
}
