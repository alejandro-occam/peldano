<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Company;
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
        error_log($request);
    }

    //Generar pdf de la propuesta
    function generatePdfProposalTest(){
        $data = array();
        $pdf = Pdf::loadView('pdf.invoice', $data)->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);
        return $pdf->download('invoice.pdf');
       
    }
}
