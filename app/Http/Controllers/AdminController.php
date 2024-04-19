<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Physician;

class AdminController extends Controller
{
    public function index(Request $request): Response{
        return Inertia::render("Admin/Dashboard");
    }
    public function getAllPhysicians(Request $request){
        $length = $request->length ? $request->length : 10;
        $search = $request->search ? $request->search : "";
        $physicians = Physician::where("form_status","submitted")->orderBy("id","desc");
        if(!empty($search)){
            $physicians = $physicians->where(function($query) use($search){
                $query->where('first_name','LIKE',"%{$search}%")
                      ->orWhere('last_name','LIKE',"%{$search}%")
                      ->orWhere('email','LIKE',"%{$search}%")
                      ->orWhere('medical_license','LIKE',"%{$search}%");
            });
        }
        if($length > 0){
            $output = $physicians->paginate($length);
        }else{
            $physicians = $physicians->get();
            $output = [
                "current_page" => 1,
                "data" => $physicians,
                "from" => 1,
                "to" => count($physicians),
                "total" => count($physicians),
                "last_page" => 1
            ];
        }
        return json_encode($output);
    }
}