<?php
namespace App\Exceptions;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    public function register(): void{
        $this->reportable(function(Throwable $e){
            //
        });
    }
    public function render($request,Throwable $e){
        $response = parent::render($request,$e);
        if($response->status() == 500){
            return Inertia::render('Errors/500');
        }elseif($response->status() == 503){
            return Inertia::render('Errors/503');
        }elseif($response->status() == 500){
            return Inertia::render('Errors/500');
        }elseif($response->status() == 404){
            return Inertia::render('Errors/404');
        }elseif($response->status() == 403){
            return Inertia::render('Errors/403');
        }elseif($response->status() === 419){
            return back()->with(['message' => 'The page expired, please try again.']);
        }
        return $response;
    }
}