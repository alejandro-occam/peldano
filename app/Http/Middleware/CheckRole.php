<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        $user = Auth::user();
       
        $role = is_array($roles)
            ? $roles
            : explode('|', $roles);
        
        foreach($role as $rol) {
            // Check if user has the role This check will depend on how your roles are set up
            if($user->hasRole($rol))
                return $next($request);
        }

        return redirect('login');
    }
}
