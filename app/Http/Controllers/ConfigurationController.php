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
use App\Models\Service;
use Auth;

//Batchs
use App\Models\Department;
use App\Models\Section;
use App\Models\Channel;
use App\Models\Project;
use App\Models\Chapter;
use App\Models\Batch;

use DB;

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
                        ->leftJoin('role_user', 'role_user.user_id', 'users.id_position')
                        ->leftJoin('roles', 'roles.id', 'role_user.role_id')
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
            || !isset($mobile) || empty($mobile) || !isset($extension) || empty($extension) || !isset($id_rol) || empty($id_rol) || !isset($commission) || !isset($status) || empty($status)) {
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
        $role_user = RoleUser::select('role_user.*', 'roles.name as role_name')->leftJoin('roles', 'roles.id', 'role_user.role_id')->where('role_user.user_id', $id)->first();
        $user['id_rol'] = $role_user->id_role;
        $user['role_name'] = $role_user['role_name'];
        $user['custom_date'] = $this->customDate($user->created_at);
        $response['user'] = $user;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Consultar información del usuario
    function getUser(){
        $user = User::select('users.*', 'positions.name as name_position', 'roles.name as role_name') 
                        ->leftJoin('positions', 'positions.id', 'users.id_position')
                        ->leftJoin('role_user', 'role_user.user_id', 'users.id')
                        ->leftJoin('roles', 'roles.id', 'role_user.role_id')
                        ->where('users.id', Auth::user()->id)->first();
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
            $array_calendars = CalendarMagazine::select('calendars_magazines.*', 'calendars.name as calendar_name')->leftJoin('calendars', 'calendars.id', '=', 'calendars_magazines.id_calendar')->orderBy('calendars_magazines.id', 'DESC')->skip($start)->take($skip)->get();
            $total_calendars = CalendarMagazine::count();
        }else{
            $array_calendars = CalendarMagazine::select('calendars_magazines.*', 'calendars.name as calendar_name')->leftJoin('calendars', 'calendars.id', '=', 'calendars_magazines.id_calendar')->where('calendars_magazines.id_calendar', $select_calendar_filter)->orderBy('calendars_magazines.id', 'DESC')->skip($start)->take($skip)->get();
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
            $array_calendars = CalendarMagazine::select('calendars_magazines.*', 'calendars.name as calendar_name')->leftJoin('calendars', 'calendars.id', '=', 'calendars_magazines.id_calendar')->orderBy('calendars_magazines.id', 'DESC')->get();
        }else{
            $array_calendars = CalendarMagazine::select('calendars_magazines.*', 'calendars.name as calendar_name')->leftJoin('calendars', 'calendars.id', '=', 'calendars_magazines.id_calendar')->where('calendars_magazines.id_calendar', $select_calendar_filter)->orderBy('calendars_magazines.id', 'DESC')->get();
        }
        
        $html = '';
        foreach($array_calendars as $calendar){
            $html .= '<tr data-row="0" class="datatable-row" style="left: 0px;">
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#calendar" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-dark">'.$calendar['calendar_name'].'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#number" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-dark">'.$calendar->number.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#title" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-dark">'.$calendar->title.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#drafting" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.$calendar->drafting.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#commercial" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.$calendar->commercial.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#output" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.$calendar->output.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#billing" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.$calendar->billing.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#front_page" aria-label="null">
                            <span class="mx-auto">
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
                                                    'calendars_magazines.front_page', 'calendars.name as calendar_name')->leftJoin('calendars', 'calendars.id', '=', 'calendars_magazines.id_calendar')->orderBy('calendars_magazines.id', 'DESC')->get();
        }else{
            $array_calendars = CalendarMagazine::select('calendars_magazines.number', 'calendars_magazines.title', 'calendars_magazines.drafting', 'calendars_magazines.commercial', 'calendars_magazines.output', 'calendars_magazines.billing',
                                                    'calendars_magazines.front_page', 'calendars.name as calendar_name')->leftJoin('calendars', 'calendars.id', '=', 'calendars_magazines.id_calendar')->orderBy('calendars_magazines.id', 'DESC')->where('calendars_magazines.id_calendar', $select_calendar_filter)->get();
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
    //Consultar departamentos
    function getDepartments(){
        $array_departments = Department::get();
        $response['array_departments'] = $array_departments;
        return response()->json($response);
    }
    
    //Consultar secciones
    function getSections($id = 0){
        if($id == 0){
            $array_sections = Section::get();
        }else{
            $array_sections = Section::where('id_department', $id)->get();
        }
        
        $response['array_sections'] = $array_sections;
        return response()->json($response);
    }

    //Consultar canales
    function getChannels($id = 0){
        if($id == 0){
            $array_channels = Channel::get();
        }else{
            $array_channels = Channel::where('id_section', $id)->get();
        }
        
        $response['array_channels'] = $array_channels;
        return response()->json($response);
    }

    //Consultar proyectos
    function getProjects($id = 0){
        if($id == 0){
            $array_projects = Project::get();
        }else{
            $array_projects = Project::where('id_channel', $id)->get();
        }
        
        $response['array_projects'] = $array_projects;
        return response()->json($response);
    }

    //Consultar capítulos
    function getChapters($id = 0){
        if($id == 0){
            $array_chapters = Chapter::get();
        }else{
            $array_chapters = Chapter::where('id_project', $id)->get();
        }
        
        $response['array_chapters'] = $array_chapters;
        return response()->json($response);
    }

    //Consultar lotes
    function getBatchs($id = 0){
        if($id == 0){
            $array_batchs = Batch::get();
        }else{
            $array_batchs = Batch::where('id_chapter', $id)->get();
        }
        
        $response['array_batchs'] = $array_batchs;
        return response()->json($response);
    }

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

    //Consultar artículos
    function getArticles($id){
        $array_articles = Article::where('id_batch', $id)->get();

        //Consultamos si un artículo está asociado a un calendario
        foreach($array_articles as $article){
            $calendar = Calendar::select('calendars.*')->leftJoin('projects', 'projects.id', 'calendars.id_project')
                                ->leftJoin('chapters', 'chapters.id_project', 'projects.id')
                                ->leftJoin('batchs', 'batchs.id_chapter', 'chapters.id')
                                ->where('batchs.id', $id)->first();
            if($calendar){
                $calendars_magazine = CalendarMagazine::where('id_calendar', $calendar->id)->orderBy('id', 'desc')->limit(10)->get();
                $array_calendars_magazines = array();
                foreach($calendars_magazine as $calendar_magazine){
                    $calendar_magazine_obj['number'] = $calendar_magazine->number;
                    $calendar_magazine_obj['title'] = $calendar_magazine->title;
                    $calendar_magazine_obj['output'] = $calendar_magazine->output;
                    $array_calendars_magazines[] = $calendar_magazine_obj;
                }
                $article['array_calendars_magazines'] = $array_calendars_magazines;
            }
        }
        $response['array_articles'] = $array_articles;
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

        /*$array_articles = Article::select('articles.*', 'products.name as product_name', 'brands.name as brand_name', 'sectors.name as sector_name', 'areas.name as area_name')
                                    ->leftJoin('products', 'products.id', 'articles.id_product')
                                    ->leftJoin('brands', 'brands.id', 'products.id_brand')
                                    ->leftJoin('sectors', 'brands.id_sector', 'sectors.id')
                                    ->leftJoin('areas', 'sectors.id_area', 'areas.id')
                                    ->where('articles.name', 'like', '%'.$search.'%');*/
        $array_articles = Article::select('articles.*', 'batchs.nomenclature as batchs_nomenclature', 
                                    'chapters.nomenclature as chapters_nomenclature', 
                                    'projects.nomenclature as projects_nomenclature',
                                    'channels.nomenclature as channels_name', 
                                    'sections.nomenclature as sections_nomenclature',
                                    'departments.nomenclature as departments_nomenclature')
                                    ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                                    ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                                    ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                                    ->leftJoin('channels', 'channels.id', 'projects.id_channel')
                                    ->leftJoin('sections', 'sections.id', 'channels.id_section')
                                    ->leftJoin('departments', 'departments.id', 'sections.id_department')
                                    ->where('articles.name', 'like', '%'.$search.'%');

        //Filtros del listado de artículos
        //Departamentos
        $select_articles_filter_departments = $request->get('select_articles_filter_departments');
        if (isset($select_articles_filter_departments) && !empty($select_articles_filter_departments)) {
            $array_articles = $array_articles->where('departments.id', $select_articles_filter_departments);
        }
        //Secciones
        $select_articles_filter_sections = $request->get('select_articles_filter_sections');
        if (isset($select_articles_filter_sections) && !empty($select_articles_filter_sections)) {
            $array_articles = $array_articles->where('sections.id', $select_articles_filter_sections);
        }
        //Canales
        $select_articles_filter_channels = $request->get('select_articles_filter_channels');
        if (isset($select_articles_filter_channels) && !empty($select_articles_filter_channels)) {
            $array_articles = $array_articles->where('channels.id', $select_articles_filter_channels);
        }
        //Proyectos
        $select_articles_filter_projects = $request->get('select_articles_filter_projects');
        if (isset($select_articles_filter_projects) && !empty($select_articles_filter_projects)) {
            $array_articles = $array_articles->where('projects.id', $select_articles_filter_projects);
        }
        //Capítulos
        $select_articles_filter_chapters = $request->get('select_articles_filter_chapters');
        if (isset($select_articles_filter_chapters) && !empty($select_articles_filter_chapters)) {
            $array_articles = $array_articles->where('chapters.id', $select_articles_filter_chapters);
        }
        //Lotes
        $select_articles_filter_batchs = $request->get('select_articles_filter_batchs');
        if (isset($select_articles_filter_batchs) && !empty($select_articles_filter_batchs)) {
            $array_articles = $array_articles->where('batchs.id', $select_articles_filter_batchs);
        }

        $array_articles = $array_articles->orWhere('articles.english_name', 'like', '%'.$search.'%');

        //Departamentos
        $select_articles_filter_departments = $request->get('select_articles_filter_departments');
        if (isset($select_articles_filter_departments) && !empty($select_articles_filter_departments)) {
            $array_articles = $array_articles->where('departments.id', $select_articles_filter_departments);
        }
        //Secciones
        $select_articles_filter_sections = $request->get('select_articles_filter_sections');
        if (isset($select_articles_filter_sections) && !empty($select_articles_filter_sections)) {
            $array_articles = $array_articles->where('sections.id', $select_articles_filter_sections);
        }
        //Canales
        $select_articles_filter_channels = $request->get('select_articles_filter_channels');
        if (isset($select_articles_filter_channels) && !empty($select_articles_filter_channels)) {
            $array_articles = $array_articles->where('channels.id', $select_articles_filter_channels);
        }
        //Proyectos
        $select_articles_filter_projects = $request->get('select_articles_filter_projects');
        if (isset($select_articles_filter_projects) && !empty($select_articles_filter_projects)) {
            $array_articles = $array_articles->where('projects.id', $select_articles_filter_projects);
        }
        //Capítulos
        $select_articles_filter_chapters = $request->get('select_articles_filter_chapters');
        if (isset($select_articles_filter_chapters) && !empty($select_articles_filter_chapters)) {
            $array_articles = $array_articles->where('chapters.id', $select_articles_filter_chapters);
        }
        //Lotes
        $select_articles_filter_batchs = $request->get('select_articles_filter_batchs');
        if (isset($select_articles_filter_batchs) && !empty($select_articles_filter_batchs)) {
            $array_articles = $array_articles->where('batchs.id', $select_articles_filter_batchs);
        }

        $total_articles =  $array_articles->count();
        if($request->get('status') == 0){
            $array_articles = $array_articles->skip($start)
                            ->take($skip);
        }
        
        $array_articles = $array_articles->get();
        

        foreach($array_articles as $article){
            //$article['publication'] = strtoupper($article->sector_name[0]).strtoupper($article->sector_name[1]).strtoupper($article->sector_name[2]).'-'.$article->brand_name.'-'.strtoupper($article->product_name[0]).strtoupper($article->product_name[1]).strtoupper($article->product_name[2]);
            $article['publication'] = strtoupper($article['departments_nomenclature']).'-'.strtoupper($article['sections_nomenclature']).'-'.strtoupper($article['channels_name']).'-'.strtoupper($article['projects_nomenclature']).'-'.strtoupper($article['chapters_nomenclature']).'-'.strtoupper($article['batchs_nomenclature']);
        }

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
        if (!$request->has('id_batch') || !$request->has('name') || !$request->has('name_eng') || !$request->has('price')) {
            $response['code'] = 1001;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        $id_batch = $request->get('id_batch');
        $name = $request->get('name');
        $name_eng = $request->get('name_eng');
        $price = $request->get('price');

        if (!isset($id_batch) || empty($id_batch) || !isset($name) || empty($name) || !isset($name_eng) || empty($name_eng) || !isset($price) || empty($price)) {
            $response['code'] = 1002;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        //Consultamos si existe el producto
        $batch = Batch::find($id_batch);
        if(!$batch){
            $response['code'] = 1003;
            return response()->json($response);
        }

        //Consultamos el Capítulo, Proyecto, Canal, Sección y Departamento del artículo
        $chapter = Chapter::find($batch->id_chapter);
        if(!$chapter){
            $response['code'] = 1003;
            return response()->json($response);
        }

        //Consultamos el Departamento, Sección, Canal y Proyecto del lote
        $project = Project::find($chapter->id_project);
        if(!$project){
            $response['code'] = 1003;
            return response()->json($response);
        }

        $channel = Channel::find($project->id_channel);
        if(!$channel){
            $response['code'] = 1003;
            return response()->json($response);
        }

        $section = Section::find($channel->id_section);
        if(!$section){
            $response['code'] = 1003;
            return response()->json($response);
        }

        $department = Department::find($section->id_department);
        if(!$department){
            $response['code'] = 1003;
            return response()->json($response);
        }

        //Creamos un objeto para el controller curl
        $requ_curls = new CurlController();

        //Consultamos el product family del artículo
        $company = config('constants.id_company_sage');
        $url = 'https://sage200.sage.es/api/sales/ProductFamilies?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Name%20eq%20%27'.
                        $department->nomenclature."-".
                        $section->nomenclature."-".
                        $channel->nomenclature."-".
                        $project->nomenclature."-".
                        $chapter->nomenclature."-".
                        $batch->nomenclature.
                        '%27';

        $data = json_decode($requ_curls->getSageCurl($url)['response'], true);
        $product_family_id = '';
        
        if(count($data['value']) == 0){
            //Si no existe creamos un product family
            $param['CompanyId'] = $company;
            $param['Name'] = $department->nomenclature."-".$section->nomenclature."-".$channel->nomenclature."-".$project->nomenclature."-".$chapter->nomenclature."-".$batch->nomenclature;
            $param['Code'] = $department->id.$section->id.$channel->id.$project->id.$chapter->id.$batch->id;
            $url = 'https://sage200.sage.es/api/sales/ProductFamilies?api-version=1.0';
            $response = json_decode($requ_curls->postSageCurl($url, $param)['response'], true);
            
            $product_family_id = $response['Id'];
            $product_family_code = $response['Code'];

        }else{
            $array_product_family = $data['value'];
            foreach($array_product_family as $product_family){
                $product_family_id = $product_family['Id'];
                $product_family_code = $product_family['Code'];
            }
        }

        //Consultamos si existe el artículo con este product family y nombre
        $custom_name = str_replace(' ', '%20', $name);
        $url = 'https://sage200.sage.es/api/sales/Products?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Name%20eq%20%27'.$custom_name.'%27%20and%20FamilyId%20eq%20%27'.$product_family_id.'%27';
        $data_product = json_decode($requ_curls->getSageCurl($url)['response'], true);
        if(count($data_product['value']) == 0){
            //Si no existe creamos un product family
            $param['CompanyId'] = $company;
            $param['Name'] = $name;
            $param['SalesPriceIncludingTaxes'] = false;
            $param['SalesPrice'] = $price;
            $param['FamilyId'] = $product_family_id;
            $url = 'https://sage200.sage.es/api/sales/Products?api-version=1.0';
            $response = json_decode($requ_curls->postSageCurl($url, $param)['response'], true);
            $product_code = $response['Code'];

        }else{
            $response['code'] = 1004;
            return response()->json($response);
        }

        Article::create([
            'name' => $name,
            'english_name' => $name_eng,
            'pvp' => $price,
            'id_batch' => $id_batch,
            'id_sage' => $product_code,
            'id_family_sage' => $product_family_code
        ]);

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Consultar información de un usuario
    function getInfoArticle($id){
        $article = Article::select('articles.*', 
                            'batchs.id as id_batch', 'batchs.name as batch_name', 
                            'chapters.id as id_chapter', 'chapters.name as chapter_name', 
                            'projects.id as id_project', 'projects.name as project_name', 
                            'channels.id as id_channel', 'channels.name as channel_name',
                            'sections.id as id_section', 'sections.name as section_name',
                            'departments.id as id_department', 'departments.name as department_name')
                            ->leftJoin('batchs', 'batchs.id', 'articles.id_batch')
                            ->leftJoin('chapters', 'chapters.id', 'batchs.id_chapter')
                            ->leftJoin('projects', 'projects.id', 'chapters.id_project')
                            ->leftJoin('channels', 'channels.id', 'projects.id_channel')
                            ->leftJoin('sections', 'sections.id', 'channels.id_section')
                            ->leftJoin('departments', 'departments.id', 'sections.id_department')
                            ->where('articles.id', $id)
                            ->first();


        //Consultamos los arrays de cada parametro según el artículo elegido
        $array_departments = Department::get();
        $array_sections = Section::where('id_department', $article->id_department)->get();
        $array_channels = Channel::where('id_section', $article->id_section)->get();
        $array_projects = Project::where('id_channel', $article->id_channel)->get();
        $array_chapters = Chapter::where('id_project', $article->id_project)->get();
        $array_batchs = Batch::where('id_chapter', $article->id_chapter)->get();

        $response['article'] = $article;
        $response['array_departments'] = $array_departments;
        $response['array_sections'] = $array_sections;
        $response['array_channels'] = $array_channels;
        $response['array_projects'] = $array_projects;
        $response['array_chapters'] = $array_chapters;
        $response['array_batchs'] = $array_batchs;
        $response['code'] = 1000;
        return response()->json($response);
    }

    //Eliminar artículo
    function deleteArticle($id){
        //Consultamos si existe el artículo
        $article = Article::find($id);
        if(!$article){
            $response['code'] = 1001;
            return response()->json($response);
        }

        //Consultamos si el artículo pertenece a algún servicio de alguna propuesta
        $service = Service::where('id_article', $article->id)->first();
        if($service){
            $response['code'] = 1002;
            return response()->json($response);
        }

        //Eliminamos el artículo
        $article->delete();

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Actualizar artículo
    function updateArticle(Request $request){
        if (!$request->has('id_article') || !$request->has('id_product') || !$request->has('name') || !$request->has('name_eng') || !$request->has('price')) {
            $response['code'] = 1001;
            $response['msg'] = "Missing or empty parameters";
            return response()->json($response);
        }

        $id = $request->get('id_article');
        $id_product = $request->get('id_product');
        $name = $request->get('name');
        $name_eng = $request->get('name_eng');
        $price = $request->get('price');

        if (!isset($id) || empty($id) || !isset($id_product) || empty($id_product) || !isset($name) || empty($name) || !isset($name_eng) || empty($name_eng) || !isset($price) || empty($price)) {
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

        //Consultamos el Area, Sector y Marca del artículo
        $brand = Brand::find($product->id_brand);
        if(!$brand){
            $response['code'] = 1003;
            return response()->json($response);
        }

        $sector = Sector::find($brand->id_sector);
        if(!$sector){
            $response['code'] = 1003;
            return response()->json($response);
        }

        $area = Area::find($sector->id_area);
        if(!$area){
            $response['code'] = 1003;
            return response()->json($response);
        }

        if($name != $product->name){
            /*//Creamos un objeto para el controller curl
            $requ_curls = new CurlController();

            //Consultamos el product family del artículo
            $company = config('constants.id_company_sage');
            $url = 'https://sage200.sage.es/api/sales/ProductFamilies?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Name%20eq%20%27'.$area->id."-".$sector->id."-".$brand->id."-".$product->id.'%27';
            $data = json_decode($requ_curls->getSageCurl($url)['response'], true);
            $product_family_id = '';
            if(count($data['value']) == 0){
                //Si no existe creamos un product family
                $param['CompanyId'] = $company;
                $param['Name'] = $area->id."-".$sector->id."-".$brand->id."-".$product->id;
                $url = 'https://sage200.sage.es/api/sales/ProductFamilies?api-version=1.0';
                $response = json_decode($requ_curls->postSageCurl($url, $param)['response'], true);
                $product_family_id = $response['Id'];
            }else{
                $array_product_family = $data['value'];
                foreach($array_product_family as $product_family){
                    $product_family_id = $product_family['Id'];
                }
            }

            //Consultamos si existe el artículo con este product family y nombre
            $custom_name = str_replace(' ', '%20', $name);
            $url = 'https://sage200.sage.es/api/sales/Products?api-version=1.0&$filter=CompanyId%20eq%20%27'.$company.'%27%20and%20Name%20eq%20%27'.$custom_name.'%27%20and%20FamilyId%20eq%20%27'.$product_family_id.'%27';
            $data_product = json_decode($requ_curls->getSageCurl($url)['response'], true);
            if(count($data_product['value']) == 0){
                //Si no existe creamos un product family
                $param['CompanyId'] = $company;
                $param['Name'] = $name;
                $param['SalesPriceIncludingTaxes'] = false;
                $param['SalesPrice'] = $price;
                $param['FamilyId'] = $product_family_id;
                $url = 'https://sage200.sage.es/api/sales/Products?api-version=1.0';
                $response = json_decode($requ_curls->postSageCurl($url, $param)['response'], true);
                $product_id = $response['Id'];

            }else{
                $response['code'] = 1004;
                return response()->json($response);
            }

            //¿Eliminamos el producto si se cambia el nombre?*/
        }

        //Consultamos si existe el artículo
        $article = Article::find($id);
        if(!$article){
            $response['code'] = 1004;
            return response()->json($response);
        }

        $article->name = $name;
        $article->english_name = $name_eng;
        $article->pvp = $price;
        $article->id_product = $id_product;
        $article->save();

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Actualizar exento de IVA de un artículo
    function changeExempt(Request $request){
        $id = $request->get('id');
        //Consultamos si existe el artículo
        $article = Article::find($id);
        if(!$article){
            $response['code'] = 1001;
            return response()->json($response);
        }
        
        $is_exempt = 0;
        if($article->is_exempt == 0){
            $is_exempt = 1;
        }
        $article->is_exempt = $is_exempt;
        $article->save();

        $response['code'] = 1000;
        return response()->json($response);
    }

    //Listar tabla de calendarios para exportar
    function listArticlesToExport(Request $request){
        $select_articles_filter_sectors = $request->get('select_articles_filter_sectors');
        $select_articles_filter_brands = $request->get('select_articles_filter_brands');
        $select_articles_filter_products = $request->get('select_articles_filter_products');
        $search_articles = $request->get('search_articles');

        //Barra de busqueda
        $search = '';
        if (isset($search_articles)) {
            $search = $search_articles;
        }

        $array_articles = Article::select('articles.*', 'products.name as product_name', 'brands.name as brand_name', 'sectors.name as sector_name', 'areas.name as area_name')
            ->leftJoin('products', 'products.id', 'articles.id_product')
            ->leftJoin('brands', 'brands.id', 'products.id_brand')
            ->leftJoin('sectors', 'brands.id_sector', 'sectors.id')
            ->leftJoin('areas', 'sectors.id_area', 'areas.id')
            ->where('articles.name', 'like', '%'.$search.'%');

        if(isset($select_articles_filter_sectors) && !empty($select_articles_filter_sectors)){
            $array_articles = $array_articles->where('sectors.id', $select_articles_filter_sectors);
        }

        if(isset($select_articles_filter_brands) && !empty($select_articles_filter_brands)){
            $array_articles = $array_articles->where('brands.id', $select_articles_filter_brands);
        }

        if(isset($select_articles_filter_products) && !empty($select_articles_filter_products)){
            $array_articles = $array_articles->where('products.id', $select_articles_filter_products);
        }

        $array_articles = $array_articles->orWhere('articles.english_name', 'like', '%'.$search.'%');

        if(isset($select_articles_filter_sectors) && !empty($select_articles_filter_sectors)){
            $array_articles = $array_articles->where('sectors.id', $select_articles_filter_sectors);
        }

        if(isset($select_articles_filter_brands) && !empty($select_articles_filter_brands)){
            $array_articles = $array_articles->where('brands.id', $select_articles_filter_brands);
        }

        if(isset($select_articles_filter_products) && !empty($select_articles_filter_products)){
            $array_articles = $array_articles->where('products.id', $select_articles_filter_products);
        }

        $array_articles = $array_articles->get();
        
        $html = '';
        foreach($array_articles as $article){
            $article['publication'] = strtoupper($article->sector_name[0]).strtoupper($article->sector_name[1]).strtoupper($article->sector_name[2]).'-'.$article->brand_name.'-'.strtoupper($article->product_name[0]).strtoupper($article->product_name[1]).strtoupper($article->product_name[2]);
            $is_exempt_str = 'No';
            if($article->is_exempt){
                $is_exempt_str = 'Sí';
            }
            $html .= '<tr data-row="0" class="datatable-row" style="left: 0px;">
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#calendar" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-dark">'.$article->id.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#number" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-dark">'.$article['publication'].'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#title" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-dark">'.$article->name.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#drafting" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.$article->english_name.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#commercial" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.$is_exempt_str.'</span>
                            </span>
                        </td>
                        <td style="width: 85px;" class="datatable-cell-center datatable-cell" data-field="#output" aria-label="null">
                            <span class="mx-auto">
                                <span class="text-gray font-weight-bold">'.number_format($article->pvp, 2, ',', '.').'</span>
                            </span>
                        </td>
                    </tr>';
        }

        $response['code'] = 1000;
        $response['array_articles'] = $html;
        return response()->json($response);
    }

    //Descargar tabla calendarios csv
    function downloadListArticlesCsv($select_calendar_filter){    
        //Creamos las columnas del fichero
        $array_custom_calendars = array (
            array('Referencia', 'Publicación', 'Nombre', 'Nombre Eng', 'Exento', 'PVP')
        );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //Creamos las cabeceras
        $sheet->setCellValue('A1', 'Refencia');
        $sheet->setCellValue('B1', 'Publicación');
        $sheet->setCellValue('C1', 'Nombre');
        $sheet->setCellValue('D1', 'Nombre Eng');
        $sheet->setCellValue('E1', 'Exento');
        $sheet->setCellValue('F1', 'PVP');

        //Consultamos los artículos
        $array_articles = Article::select('articles.*', 'products.name as product_name', 'brands.name as brand_name', 'sectors.name as sector_name', 'areas.name as area_name')
            ->leftJoin('products', 'products.id', 'articles.id_product')
            ->leftJoin('brands', 'brands.id', 'products.id_brand')
            ->leftJoin('sectors', 'brands.id_sector', 'sectors.id')
            ->leftJoin('areas', 'sectors.id_area', 'areas.id')
            ->get();

        foreach($array_articles as $key => $article){
            $article['publication'] = strtoupper($article->sector_name[0]).strtoupper($article->sector_name[1]).strtoupper($article->sector_name[2]).'-'.$article->brand_name.'-'.strtoupper($article->product_name[0]).strtoupper($article->product_name[1]).strtoupper($article->product_name[2]);
            $is_exempt_str = 'No';
            if($article->is_exempt){
                $is_exempt_str = 'Sí';
            }
            $sheet->setCellValue('A'.($key+2), $article->id);
            $sheet->setCellValue('B'.($key+2), $article['publication']);
            $sheet->setCellValue('C'.($key+2), $article->name);
            $sheet->setCellValue('D'.($key+2), $article->english_name);
            $sheet->setCellValue('E'.($key+2), $is_exempt_str);
            $sheet->setCellValue('F'.($key+2), $article->pvp);
        }

        $writer = new Xlsx($spreadsheet);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.'articulos.xlsx');
        $writer->save('php://output');
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
