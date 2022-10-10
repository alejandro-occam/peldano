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
            || !$request->has('id_rol') || !$request->has('commission') || !$request->has('status') || !$request->has('extension')) {
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
        $id_rol = $request->get('id_rol');
        $commission = $request->get('commission');
        $status = $request->get('status');
        $extension = $request->get('extension');

        if (!isset($name) || empty($name) || !isset($surname) || empty($surname) || !isset($email) || empty($email) || !isset($user) || empty($user) || !isset($password) || empty($password) || !isset($id_position) || empty($id_position)
            || !isset($mobile) || empty($mobile) || !isset($extension) || empty($extension) || !isset($id_rol) || empty($id_rol) || !isset($commission) || empty($commission) || !isset($status) || empty($status)) {
            $response['code'] = 1002;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        //Consultamos si el email o el usuario ya están en la bd
        $user_obj = User::where('email', $email)->first();
        if($user_obj){
            $response['code'] = 1003;
            return response()->json($response);
        }

        $user_obj = User::where('user', $user)->first();
        if($user_obj){
            $response['code'] = 1004;
            return response()->json($response);
        }
        
        $user_obj = User::create([
            'email' => $email,
            'password' => Hash::make($password),
            'name' => $name,
            'surname' => $surname,
            'user' => $user,
            'id_position' => $id_position,
            'extension' => $extension,
            'mobile' => $mobile,
            'commission' => $commission,
            'active' => $status,
        ]);

        RoleUser::create([
            'id_role' => $id_rol,
            'id_user' => $user_obj->id
        ]);

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Consultar información de un usuario
    function getInfoUser($id){
        $user = User::select('users.*', 'positions.name as name_position')->leftJoin('positions', 'positions.id', 'users.id_position')->where('users.id',$id)->first();
        $user['discharge_date'] = $this->customDateBis($user->created_at);

        //Consultamos el id rol
        $role_user = RoleUser::select('roles_users.*', 'roles.name as role_name')->leftJoin('roles', 'roles.id', 'roles_users.id_role')->where('roles_users.id_user', $id)->first();
        $user['id_rol'] = $role_user->id_role;
        $user['role_name'] = $role_user['role_name'];
        $user['custom_date'] = $this->customDate($user->created_at);
        $response['user'] = $user;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Actualizar usuario
    function updateUser(Request $request){
        if (!$request->has('id_user') || !$request->has('name') || !$request->has('surname')  || !$request->has('user') || !$request->has('id_position') || !$request->has('mobile')
            || !$request->has('id_rol') || !$request->has('commission') || !$request->has('status') || !$request->has('extension')) {
            $response['code'] = 1001;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        $id_user = $request->get('id_user');
        $name = $request->get('name');
        $surname = $request->get('surname');
        $user = $request->get('user');
        $id_position = $request->get('id_position');
        $mobile = $request->get('mobile');
        $id_rol = $request->get('id_rol');
        $commission = $request->get('commission');
        $status = $request->get('status');
        $extension = $request->get('extension');

        if (!isset($id_user) || empty($id_user) || !isset($name) || empty($name) || !isset($surname) || empty($surname) || !isset($user) || empty($user) || !isset($id_position) || empty($id_position)
            || !isset($mobile) || empty($mobile) || !isset($extension) || empty($extension) || !isset($id_rol) || empty($id_rol) || !isset($commission) || empty($commission) || !isset($status)) {
            $response['code'] = 1002;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        //Consultamos si existe el usuario a modificar
        $user_obj = User::find($id_user);
        if(!$user_obj){
            $response['code'] = 1003;
            return response()->json($response);
        }

        //Consultamos si el email o el usuario ya están en la bd
        /*$user_obj_aux = User::where('email', $email)->first();
        if($user_obj_aux){
            $response['code'] = 1004;
            return response()->json($response);
        }*/

        $user_obj_aux = User::where('user', $user)->first();
        if($user_obj_aux && $user_obj->user != $user){
            $response['code'] = 1005;
            return response()->json($response);
        }

        $user_obj->name = $name;
        $user_obj->surname = $surname;
        $user_obj->user = $user;
        $user_obj->id_position = $id_position;
        $user_obj->extension = $extension;
        $user_obj->mobile = $mobile;
        $user_obj->commission = $commission;
        $user_obj->active = $status;
        $user_obj->save();
        
        $role_user = RoleUser::where('id_user', $id_user)->first();
        if($role_user){
            $role_user->id_role = $id_rol;
        }

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Eliminar usuario
    function deleteUser($id){
        //Consultamos si existe el usuario
        $user = User::find($id);
        if(!$user){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //"Eliminamos al usuarios"
        $user->soft_delete = 1;
        $user->save();

        $response['code'] = 1000;
        return response()->json($response);
    }

    //UTILS
    //Cambiar de formato la fecha
    public function customDate($date){
        $aux1 = explode(" ", $date);
        $aux2 = explode('-', $aux1[0]);
        return $order_date = $aux2[2] . '/' . $aux2[1] . '/' . $aux2[0];
    }

    public function customDateBis($date){
        $aux1 = explode(" ", $date);
        $aux2 = explode('-', $aux1[0]);
        return $order_date = $aux2[2] . '-' . $aux2[1] . '-' . $aux2[0];
    }
}
