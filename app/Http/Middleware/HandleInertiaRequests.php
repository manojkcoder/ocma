<?php
namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';
    public function version(Request $request): string|null{
        return parent::version($request);
    }
    public function share(Request $request): array{
        return [
            ...parent::share($request),
            "isAuthenticate" => $request->session()->get("isAuthenticate"),
            "authType" => $request->session()->get("authType"),
            'auth' => [
                'user' => $request->user()
            ],
            'flash' => function(){
                return [
                    'success' => Session::get('success'),
                    'error'   => Session::get('error')
                ];
            }
        ];
    }
}