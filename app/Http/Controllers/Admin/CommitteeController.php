<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Committee;
use App\Jobs\RemarkEmailJob;

class CommitteeController extends Controller
{
    public function index(Request $request): Response{
        return Inertia::render("Admin/Committee");
    }
    public function getAll(Request $request){
        $length = $request->length ? $request->length : 10;
        $search = $request->search ? $request->search : "";
        $committees = Committee::orderBy("id","desc");
        if(!empty($search)){
            $committees = $committees->where(function($query) use($search){
                $query->where('name','LIKE',"%{$search}%")->orWhere('email','LIKE',"%{$search}%")->orWhere('phone','LIKE',"%{$search}%");
            });
        }
        if($length > 0){
            $output = $committees->paginate($length);
        }else{
            $committees = $committees->get();
            $output = [
                "current_page" => 1,
                "data" => $committees,
                "from" => 1,
                "to" => count($committees),
                "total" => count($committees),
                "last_page" => 1
            ];
        }
        return json_encode($output);
    }
    public function add(Request $request){
        $email = $request->email ? $request->email : "";
        $existCommittee = Committee::where("email",$email)->first();
        if(!empty($existCommittee)){
            return json_encode(["status" => "error","message" => "Email already in use."]);
        }
        $committee = new Committee($request->all());
        $committee->password = $this->randomString(6);
        $committee->save();
        $data = [
            "link" => route("member-login"),
            "subject" => "OCMA - Committee Member Credentials",
            "to_address" => $committee->email,
            "login_email" => $committee->email,
            "login_password" => $committee->password
        ];
        RemarkEmailJob::dispatch($data);
        return json_encode(["status" => "success","message" => "A Committee Member has been successfully added."]);
    }
    public function resend(Request $request,$id){
        $committee = Committee::where("id",$id)->first();
        if(empty($committee)){
            return json_encode(["status" => "error","message" => "Committee member not exist."]);
        }
        $data = [
            "link" => route("member-login"),
            "subject" => "OCMA - Committee Member Credentials",
            "to_address" => $committee->email,
            "login_email" => $committee->email,
            "login_password" => $committee->password
        ];
        RemarkEmailJob::dispatch($data);
        return json_encode(["status" => "success","message" => "Resend credentials to committee member."]);
    }
    public function delete(Request $request,$id){
        $existCommittee = Committee::where("id",$id)->first();
        if(empty($existCommittee)){
            return json_encode(["status" => "error","message" => "Committee member not exist."]);
        }
        $existCommittee->delete();
        return json_encode(["status" => "success","message" => "Committee member deleted successfully."]);
    }
    private function randomString($length = 10){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
        $size = strlen($chars);
        $str = '';
        for($i = 0;$i < $length;$i++){
            $str .= $chars[rand(0,$size - 1)];
        }
        return $str;
    }
}