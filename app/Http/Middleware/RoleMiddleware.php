<?php
namespace App\Http\Middleware;
use Closure;

class RoleMiddleware
{
    public function handle($request,Closure $next,$roles){
        $isAuthenticate = $request->session()->get("isAuthenticate");
        $authType = $request->session()->get("authType");
        $roles = explode("|",$roles);
        if(empty($isAuthenticate) || empty($authType) || !in_array($authType,$roles)){
            return redirect(route("authenticate"));
        }
        return $next($request);
    }
}
