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
            if(in_array("committee",$roles)){
                return redirect(route("member-login"));
            }else{
                return redirect(route("authenticate"));
            }
        }
        return $next($request);
    }
}
