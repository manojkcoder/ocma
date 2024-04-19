<?php
namespace App\Http\Middleware;
use Closure;

class RoleMiddleware
{
    public function handle($request,Closure $next,$role){
        $isAuthenticate = $request->session()->get("isAuthenticate");
        $authType = $request->session()->get("authType");
        if(empty($isAuthenticate) || empty($authType) || $authType != $role){
            return redirect(route("authenticate"));
        }
        return $next($request);
    }
}
