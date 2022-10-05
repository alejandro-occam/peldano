<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ConfigurationController extends Controller
{
    //

    function listUsers(Request $request){
        error_log('hola');
        //Elementos para la paginación 
        $pagination = $request->get('pagination');
        $query = $request->get('query');
        $start = 0;
        $skip = $pagination['perpage'];
        if ($pagination['page'] != 1) {
            $start = ($pagination['page'] - 1) * $pagination['perpage'];
            //Consultamos si hay tantos registros como para empezar en el numero de $start
            $num_users = User::count();
            if ($start >= $num_users) {
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

        $array_users = User::get();
        $total_users = count($array_users);

        //Devolución de la llamada con la paginación
        $meta['page'] = $pagination['page'];

        if ($total_users < 1) {
            $meta['page'] = 1;
        }

        $meta['pages'] = 1;
        if (isset($pagination['pages'])) {
            $meta['pages'] = $pagination['pages'];
        }
        $meta['perpage'] = $pagination['perpage'];
        $meta['total'] = $total_users;
        $meta['sort'] = 'asc';
        $meta['field'] = 'id';
        $response['meta'] = $meta;
        $response['data'] = $array_users;
        return response()->json($response);
    }

}
