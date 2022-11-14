<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Contact;
use App\Models\ProposalBill;

class OrdersController extends Controller
{
    //Listar propuestas
    function listOrders(Request $request){
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
}
