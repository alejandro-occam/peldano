<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Role;
use App\Models\Position;
use Illuminate\Support\Facades\Hash;

class ConfigurationController extends Controller
{
    //
    //Consultar información necesaria para el registro de usuarios
    function getInfoFormAddUser(){
        //Consultamos los cargos
        $array_positions = Position::get();

        //Consultamos los roles
        $array_roles = Role::get();

        $response['array_positions'] = $array_positions;
        $response['array_roles'] = $array_roles;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Listado de usuarios
    function listUsers(Request $request){
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

        $array_users = User::select('users.*', 'positions.name as position_nane', 'roles.name as role_name')->leftJoin('positions', 'positions.id', 'users.id_position')->leftJoin('roles_users', 'roles_users.id_user', 'users.id_position')->leftJoin('roles', 'roles.id', 'roles_users.id_role')->get();
        foreach($array_users as $user){
            $user['custom_date'] = $this->customDate($user->created_at);

        }
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

    //Añadir usuario
    function addUser(Request $request){
        if (!$request->has('name') || !$request->has('surname')  || !$request->has('email') || !$request->has('user') || !$request->has('password') || !$request->has('id_position') || !$request->has('mobile')
            || !$request->has('discharge_date') || !$request->has('id_rol') || !$request->has('commission') || !$request->has('status') || !$request->has('extension')) {
            $response['code'] = 1001;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        $name = $request->get('name');
        $surname = $request->get('surname');
        $email = $request->get('email');
        $user = $request->get('user');
        $password = $request->get('password');
        $id_position = $request->get('id_position');
        $mobile = $request->get('mobile');
        $discharge_date = $request->get('discharge_date');
        $id_rol = $request->get('id_rol');
        $commission = $request->get('commission');
        $status = $request->get('status');
        $extension = $request->get('extension');

        if (!isset($name) || empty($name) || !isset($surname) || empty($surname) || !isset($email) || empty($email) || !isset($user) || empty($user) || !isset($password) || empty($password) || !isset($id_position) || empty($id_position)
            || !isset($mobile) || empty($mobile) || !isset($discharge_date) || empty($discharge_date) || !isset($extension) || empty($extension) || !isset($id_rol) || empty($id_rol) || !isset($commission) || empty($commission) || !isset($status) || empty($status)) {
            $response['code'] = 1002;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        //Consultamos si el email o el usuario ya están en la bd
        $user = User::where('email', $email)->first();
        if($user){
            $response['code'] = 1003;
            return response()->json($response);
        }

        $user = User::where('user', $user)->first();
        if($user){
            $response['code'] = 1004;
            return response()->json($response);
        }
        
        $user = User::create([
            'email' => $email,
            'password' => Hash::make($password),
            'name' => $name,
            'surname' => $surname,
            'user' => $user,
            'id_position' => $id_position,
            'extension' => $extension,
            'mobile' => $mobile,
            'discharge_date' => $discharge_date,
            'commission' => $commission,
            'active' => $status,
        ]);

        User::create([
            'id_role' => $id_rol,
            'id_user' => $user->id
        ]);

        $response['code'] = 1000;
        return response()->json($response);
    }

    //UTILS
    //Cambiar de formato la fecha
    public function customDate($date)
    {
        $aux1 = explode(" ", $date);
        $aux2 = explode('-', $aux1[0]);
        return $order_date = $aux2[2] . '/' . $aux2[1] . '/' . $aux2[0];
    }
}
