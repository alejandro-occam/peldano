<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Auth;
use App\Models\User;
use App\Models\RoleUser;

class AdminUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $role_user = RoleUser::where('id_user',Auth::user()->id)->first();
        if ($role_user->id_role == 1){
            return $next($request);
        }

        Auth::logout();
        return redirect('/login');
    }
}
