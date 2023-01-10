<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use JWTAuth;
use Illuminate\Http\Request;
use JWTAuthException;
use Auth;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated($request, $user)
    {
        // your code
        return redirect()->intended($this->redirectTo); // add this line on the end of authenticated
    }

    //API
    /**
     * Login a user instance by api
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginapi(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
           if (!$token = JWTAuth::attempt($credentials)) {
               $code = 1001;
               $response['code'] = $code;
               $response['message'] = 'Invalid email or password';
               return response()->json($response);

            
           }
        } catch (JWTAuthException $e) {
            $code = 1002;
            $response['code'] = $code;
            $response['message'] = 'Failed to create token';
            return response()->json($response);
        }

        $email = $request->get('email');
        $user = User::where('email', $email)->first();

        if(!$user){
            $response['code'] = 1001;
            return response()->json($response);  
        }

        $code = 1000;
        $response['code'] = $code;
        $response['message'] = 'Logged!';
        $response['user_id'] = $user->id;
        $response['token'] = compact('token')['token'];
        return response()->json($response);
    }
}
