<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Role;
use App\Models\Position;
use App\Models\Calendar;
use App\Models\CalendarMagazine;
use Illuminate\Support\Facades\Hash;
use DateTime;
use DateTimeZone;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Area;
use App\Models\Sector;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Article;

class ConfigurationController extends Controller
{
    //USUARIOS
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

        $array_users = User::select('users.*', 'positions.name as position_nane', 'roles.name as role_name')
                        ->leftJoin('positions', 'positions.id', 'users.id_position')
                        ->leftJoin('roles_users', 'roles_users.id_user', 'users.id_position')
                        ->leftJoin('roles', 'roles.id', 'roles_users.id_role')
                        ->where('users.name', 'like', '%'.$search.'%')
                        ->orWhere('users.surname', 'like', '%'.$search.'%')
                        ->orWhere('users.email', 'like', '%'.$search.'%')
                        ->orWhere('users.user', 'like', '%'.$search.'%')
                        ->skip($start)
                        ->take($skip)
                        ->get();
        foreach($array_users as $user){
            $user['custom_date'] = $this->customDate($user->created_at);

        }
        $total_users = User::where('users.name', 'like', '%'.$search.'%')
        ->orWhere('users.surname', 'like', '%'.$search.'%')
        ->orWhere('users.email', 'like', '%'.$search.'%')
        ->orWhere('users.user', 'like', '%'.$search.'%')
        ->count();

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

    //END USUARIOS

    //CALENDARIOS
    //Consultar información necesaria para el registro de calendarios
    function getInfoFormCalendars(){
        $array_calendars = Calendar::get();
        $response['array_calendars'] = $array_calendars;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Añadir usuario
    function addCalendar(Request $request){
        date_default_timezone_set('Europe/Madrid');
        if (!$request->has('id_calendar') || !$request->has('number')  || !$request->has('title') || !$request->has('topics_date') || !$request->has('drafting_date') || !$request->has('commercial_date') || !$request->has('output_date')
            || !$request->has('billing_date') || !$request->has('front_page_date')) {
            $response['code'] = 1001;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        $id_calendar = $request->get('id_calendar');
        $number = $request->get('number');
        $title = $request->get('title');
        $topics_date = $request->get('topics_date');
        $drafting_date = $request->get('drafting_date');
        $commercial_date = $request->get('commercial_date');
        $output_date = $request->get('output_date');
        $billing_date = $request->get('billing_date');
        $front_page_date = $request->get('front_page_date');

        if (!isset($id_calendar) || empty($id_calendar) || !isset($number) || empty($number) || !isset($title) || empty($title) || !isset($topics_date) || empty($topics_date) || !isset($drafting_date) || empty($drafting_date) || !isset($commercial_date) || empty($commercial_date)
            || !isset($output_date) || empty($output_date) || !isset($billing_date) || empty($billing_date) || !isset($front_page_date) || empty($front_page_date)) {
            $response['code'] = 1002;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }
        
        CalendarMagazine::create([
            'number' => $number,
            'title' => $title,
            'topics' => date('d-m-Y',strtotime($topics_date)),
            'drafting' => date('d-m-Y',strtotime($drafting_date)),
            'commercial' => date('d-m-Y',strtotime($commercial_date)),
            'output' => date('d-m-Y',strtotime($output_date)),
            'billing' => date('d-m-Y',strtotime($billing_date)),
            'front_page' => date('d-m-Y',strtotime($front_page_date)),
            'id_calendar' => $id_calendar,
        ]);

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Listado de calendarios
    function listCalendars(Request $request){
        //Elementos para la paginación 
        $pagination = $request->get('pagination');
        $query = $request->get('query');
        $start = 0;
        $skip = $pagination['perpage'];
        if ($pagination['page'] != 1) {
            $start = ($pagination['page'] - 1) * $pagination['perpage'];
            //Consultamos si hay tantos registros como para empezar en el numero de $start
            $num_calendars = CalendarMagazine::count();
            if ($start >= $num_calendars) {
                $skip = $skip - 1;
                $start = $start - 10;
                if ($start < 0) {
                    $start = 0;
                }
            }
        }

        $select_calendar_filter = $request->get('select_calendar_filter');

        if(empty($select_calendar_filter)){
            $array_calendars = CalendarMagazine::select('calendars_magazines.*', 'calendars.name as calendar_name')->leftJoin('calendars', 'calendars.id', '=', 'calendars_magazines.id_calendar')->skip($start)->take($skip)->get();
            $total_calendars = CalendarMagazine::count();
        }else{
            $array_calendars = CalendarMagazine::select('calendars_magazines.*', 'calendars.name as calendar_name')->leftJoin('calendars', 'calendars.id', '=', 'calendars_magazines.id_calendar')->where('calendars_magazines.id_calendar', $select_calendar_filter)->skip($start)->take($skip)->get();
            $total_calendars = CalendarMagazine::where('id_calendar', $select_calendar_filter)->count();
        }

        //Devolución de la llamada con la paginación
        $meta['page'] = $pagination['page'];

        if ($total_calendars < 1) {
            $meta['page'] = 1;
        }

        $meta['pages'] = 1;
        if (isset($pagination['pages'])) {
            $meta['pages'] = $pagination['pages'];
        }
        $meta['perpage'] = $pagination['perpage'];
        $meta['total'] = $total_calendars;
        $meta['sort'] = 'asc';
        $meta['field'] = 'id';
        $response['meta'] = $meta;
        $response['data'] = $array_calendars;
        return response()->json($response);
    }

    //Eliminar usuario
    function deleteCalendar($id){
        //Consultamos si existe el usuario
        $calendar = CalendarMagazine::find($id);
        if(!$calendar){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //Eliminamos el calendario
        $calendar->delete();

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Consultar información del calendario
    function getInfoCalendar($id){
        $calendar = CalendarMagazine::find($id);

        $response['calendar'] = $calendar;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Actualizar calendario
    function updateCalendar(Request $request){
        date_default_timezone_set('Europe/Madrid');
        if (!$request->has('id') || !$request->has('id_calendar') || !$request->has('number')  || !$request->has('title') || !$request->has('topics_date') || !$request->has('drafting_date') || !$request->has('commercial_date') || !$request->has('output_date')
            || !$request->has('billing_date') || !$request->has('front_page_date')) {
            $response['code'] = 1001;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        $id = $request->get('id');
        $id_calendar = $request->get('id_calendar');
        $number = $request->get('number');
        $title = $request->get('title');
        $topics = $request->get('topics_date');
        $drafting = $request->get('drafting_date');
        $commercial = $request->get('commercial_date');
        $output = $request->get('output_date');
        $billing = $request->get('billing_date');
        $front_page = $request->get('front_page_date');

        $madrid = new DateTimeZone('Europe/Madrid');

        if (!isset($id) || empty($id) || !isset($id_calendar) || empty($id_calendar) || !isset($number) || empty($number) || !isset($title) || empty($title) || !isset($topics) || empty($topics) || !isset($drafting) || empty($drafting) || !isset($commercial) || empty($commercial)
            || !isset($output) || empty($output) || !isset($billing) || empty($billing) || !isset($front_page) || empty($front_page)) {
            $response['code'] = 1002;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        //Consultamos si existe el calendario
        $calendar = CalendarMagazine::find($id);
        if(!$calendar){
            $response['code'] = 1003;
            return response()->json($response);
        }

        if(str_contains($topics, 'T') && str_contains($topics, 'Z')){            
            $topics = date('d-m-Y',strtotime($topics));
        }

        if(str_contains($drafting, 'T') && str_contains($drafting, 'Z')){
            $drafting = date('d-m-Y',strtotime($drafting));
        }

        if(str_contains($commercial, 'T') && str_contains($commercial, 'Z')){
            $commercial = date('d-m-Y',strtotime($commercial));
        }

        if(str_contains($output, 'T') && str_contains($output, 'Z')){
            $output = date('d-m-Y',strtotime($output));
        }

        
        if(str_contains($billing, 'T') && str_contains($billing, 'Z')){
            $billing = date('d-m-Y',strtotime($billing));
        }

        if(str_contains($front_page, 'T') && str_contains($front_page, 'Z')){
            $front_page = date('d-m-Y',strtotime($front_page));
        }
        
        $calendar->number = $number;
        $calendar->title = $title;
        $calendar->topics = $topics;
        $calendar->drafting = $drafting;
        $calendar->commercial = $commercial;
        $calendar->output = $output;
        $calendar->billing = $billing;
        $calendar->front_page = $front_page;
        $calendar->id_calendar = $id_calendar;
        $calendar->save();

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Listar tabla de calendarios para exportar
    function listCalendarsToExport(Request $request){
        $select_calendar_filter = $request->get('select_calendar_filter');

        if(empty($select_calendar_filter)){
            $array_calendars = CalendarMagazine::select('calendars_magazines.*', 'calendars.name as calendar_name')->leftJoin('calendars', 'calendars.id', '=', 'calendars_magazines.id_calendar')->get();
        }else{
            $array_calendars = CalendarMagazine::select('calendars_magazines.*', 'calendars.name as calendar_name')->leftJoin('calendars', 'calendars.id', '=', 'calendars_magazines.id_calendar')->where('calendars_magazines.id_calendar', $select_calendar_filter)->get();
        }
        
        $html = '';
        foreach($array_calendars as $calendar){
            $html .= '<tr data-row="0" class="datatable-row" style="left: 0px;">
                        <td class="datatable-cell-center datatable-cell" data-field="#calendar" aria-label="null">
                            <span style="width: 175px;">
                                <span class="text-dark">'.$calendar['calendar_name'].'</span>
                            </span>
                        </td>
                        <td class="datatable-cell-center datatable-cell" data-field="#number" aria-label="null">
                            <span style="width: 100px;">
                                <span class="text-dark">'.$calendar->number.'</span>
                            </span>
                        </td>
                        <td class="datatable-cell-center datatable-cell" data-field="#title" aria-label="null">
                            <span style="width: 175px;">
                                <span class="text-dark">'.$calendar->title.'</span>
                            </span>
                        </td>
                        <td class="datatable-cell-center datatable-cell" data-field="#drafting" aria-label="null">
                            <span style="width: 175px;">
                                <span class="text-gray font-weight-bold">'.$calendar->drafting.'</span>
                            </span>
                        </td>
                        <td class="datatable-cell-center datatable-cell" data-field="#commercial" aria-label="null">
                            <span style="width: 175px;">
                                <span class="text-gray font-weight-bold">'.$calendar->commercial.'</span>
                            </span>
                        </td>
                        <td class="datatable-cell-center datatable-cell" data-field="#output" aria-label="null">
                            <span style="width: 175px;">
                                <span class="text-gray font-weight-bold">'.$calendar->output.'</span>
                            </span>
                        </td>
                        <td class="datatable-cell-center datatable-cell" data-field="#billing" aria-label="null">
                            <span style="width: 175px;">
                                <span class="text-gray font-weight-bold">'.$calendar->billing.'</span>
                            </span>
                        </td>
                        <td class="datatable-cell-center datatable-cell" data-field="#front_page" aria-label="null">
                            <span style="width: 175px;">
                                <span class="text-gray font-weight-bold">'.$calendar->front_page.'</span>
                            </span>
                        </td>
                    </tr>';
        }

        $response['code'] = 1000;
        $response['array_calendars'] = $html;
        return response()->json($response);
    }

    //Descargar tabla calendarios csv
    function downloadListCalendarsCsv($select_calendar_filter){    
        //Creamos las columnas del fichero
        $array_custom_calendars = array (
            array('Calendario', 'Num.', 'Título', 'Redacción', 'Publicidad', 'Salida', 'Facturación', 'Portada')
        );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //Creamos las cabeceras
        $sheet->setCellValue('A1', 'Calendario');
        $sheet->setCellValue('B1', 'Num.');
        $sheet->setCellValue('C1', 'Título');
        $sheet->setCellValue('D1', 'Redacción');
        $sheet->setCellValue('E1', 'Publicidad');
        $sheet->setCellValue('F1', 'Salida');
        $sheet->setCellValue('G1', 'Facturación');
        $sheet->setCellValue('H1', 'Portada');

        //Consultamos los usuarios
        if(empty($select_calendar_filter)){
            $array_calendars = CalendarMagazine::select('calendars_magazines.number', 'calendars_magazines.title', 'calendars_magazines.drafting', 'calendars_magazines.commercial', 'calendars_magazines.output', 'calendars_magazines.billing',
                                                    'calendars_magazines.front_page', 'calendars.name as calendar_name')->leftJoin('calendars', 'calendars.id', '=', 'calendars_magazines.id_calendar')->get();
        }else{
            $array_calendars = CalendarMagazine::select('calendars_magazines.number', 'calendars_magazines.title', 'calendars_magazines.drafting', 'calendars_magazines.commercial', 'calendars_magazines.output', 'calendars_magazines.billing',
                                                    'calendars_magazines.front_page', 'calendars.name as calendar_name')->leftJoin('calendars', 'calendars.id', '=', 'calendars_magazines.id_calendar')->where('calendars_magazines.id_calendar', $select_calendar_filter)->get();
        }
        

        foreach($array_calendars as $key => $calendar){
            $sheet->setCellValue('A'.($key+2), $calendar->calendar_name);
            $sheet->setCellValue('B'.($key+2), $calendar->number);
            $sheet->setCellValue('C'.($key+2), $calendar->title);
            $sheet->setCellValue('D'.($key+2), $calendar->drafting);
            $sheet->setCellValue('E'.($key+2), $calendar->commercial);
            $sheet->setCellValue('F'.($key+2), $calendar->output);
            $sheet->setCellValue('G'.($key+2), $calendar->billing);
            $sheet->setCellValue('H'.($key+2), $calendar->front_page);
        }

        $writer = new Xlsx($spreadsheet);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.'calendarios.xlsx');
        $writer->save('php://output');
    }
    //END CALENDARIOS

    //ARTICULOS
    //Consultar areas
    function getAreas(){
        $array_areas = Area::get();
        $response['array_areas'] = $array_areas;
        return response()->json($response);
    }

    //Consultar sectores
    function getSectors($id = 0){
        if($id == 0){
            $array_sectors = Sector::get();
        }else{
            $array_sectors = Sector::where('id_area', $id)->get();
        }
        
        $response['array_sectors'] = $array_sectors;
        return response()->json($response);
    }

    //Consultar marcas
    function getBrands($id){
        $array_brands = Brand::where('id_sector', $id)->get();
        $response['array_brands'] = $array_brands;
        return response()->json($response);
    }

    //Consultar productos
    function getProducts($id){
        $array_products = Product::where('id_brand', $id)->get();
        $response['array_products'] = $array_products;
        return response()->json($response);
    }

    //Listado de artículos
    function listArticles(Request $request){
        //Elementos para la paginación 
        $pagination = $request->get('pagination');
        $query = $request->get('query');
        $start = 0;
        $skip = $pagination['perpage'];
        if ($pagination['page'] != 1) {
            $start = ($pagination['page'] - 1) * $pagination['perpage'];
            //Consultamos si hay tantos registros como para empezar en el numero de $start
            $num_articles = Article::count();
            if ($start >= $num_articles) {
                $skip = $skip - 1;
                $start = $start - 10;
                if ($start < 0) {
                    $start = 0;
                }
            }
        }

        //Barra de busqueda
        $search = '';
        if (isset($query['search_articles'])) {
            $search = $query['search_articles'];
        }

        $array_articles = Article::skip($start)
                            ->take($skip)
                            ->get();

        $total_articles = Article::count();

        //Devolución de la llamada con la paginación
        $meta['page'] = $pagination['page'];

        if ($total_articles < 1) {
            $meta['page'] = 1;
        }

        $meta['pages'] = 1;
        if (isset($pagination['pages'])) {
            $meta['pages'] = $pagination['pages'];
        }
        $meta['perpage'] = $pagination['perpage'];
        $meta['total'] = $total_articles;
        $meta['sort'] = 'asc';
        $meta['field'] = 'id';
        $response['meta'] = $meta;
        $response['data'] = $array_articles;
        return response()->json($response);
    }

    //Añadir artículo
    function addArticle(Request $request){
        if (!$request->has('id_product') || !$request->has('name') || !$request->has('name_eng') || !$request->has('price')) {
            $response['code'] = 1001;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        $id_product = $request->get('id_product');
        $name = $request->get('name');
        $name_eng = $request->get('name_eng');
        $price = $request->get('price');

        if (!isset($id_product) || empty($id_product) || !isset($name) || empty($name) || !isset($name_eng) || empty($name_eng) || !isset($price) || empty($price)) {
            $response['code'] = 1002;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        //Consultamos si existe el producto
        $product = Product::find($id_product);
        if(!$product){
            $response['code'] = 1003;
            return response()->json($response);
        }
        
        Article::create([
            'name' => $name,
            'english_name' => $name_eng,
            'pvp' => $price,
            'id_product' => $id_product,
        ]);

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Consultar información de un usuario
    function getInfoArticle($id){
        $article = Article::find($id);

        $response['article'] = $article;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Eliminar artículo
    function deleteArticle($id){
        //Consultamos si existe el usuario
        $article = Article::find($id);
        if(!$article){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //Eliminamos el calendario
        $article->delete();

        $response['code'] = 1000;
        return response()->json($response);
    }

    //END ARTICULOS

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

    public function customDateTris($date){
        $aux1 = explode("T", $date);
        $aux2 = explode('-', $aux1[0]);
        return $order_date = $aux2[2] . '-' . $aux2[1] . '-' . $aux2[0];
    }
}
