<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\AdminRemark;
use App\Models\Committee;
use App\Models\FinalRemark;
use App\Models\MemberAssign;
use App\Models\Physician;
use App\Models\Review;

class MemberController extends Controller
{
    public function login(Request $request): Response{
        return Inertia::render("Frontend/Login");
    }
    public function authenticate(Request $request){
        $email = $request->email ? $request->email : "";
        $password = $request->password ? $request->password : "";
        $status = "error";
        $message = "Invalid credientials";
        $isAuthenticate = "";
        $authType = "";
        $committee = Committee::where(["email" => $email,"password" => $password])->first();
        if($committee){
            $isAuthenticate = 1;
            $authType = "committee";
            $status = "success";
            $message = "success";
            $request->session()->put("authType",$authType);
            $request->session()->put("memberId",$committee->id);
            $request->session()->put("isAuthenticate",$isAuthenticate);
        }
        return json_encode(compact("status","message","authType","isAuthenticate"));
    }
    public function remarks(Request $request): Response{
        return Inertia::render("Frontend/Remarks");
    }
    public function getAllRemarks(Request $request){
        $length = $request->length ? $request->length : 10;
        $search = $request->search ? $request->search : "";
        $memberId = $request->session()->get("memberId") ? $request->session()->get("memberId") : "";
        $physicians = Physician::with("members","attachments")->whereHas("members",function ($query) use ($memberId){
            $query->where("same_member_id",$memberId)->orWhere("different_member_id",$memberId);
        })->where("form_status","submitted")->orderBy("id","desc");
        if(!empty($search)){
            $physicians = $physicians->where(function($query) use($search){
                $query->where("first_name","LIKE","%{$search}%")
                      ->orWhere("last_name","LIKE","%{$search}%")
                      ->orWhere("email","LIKE","%{$search}%")
                      ->orWhere("medical_license","LIKE","%{$search}%");
            });
        }
        if($length > 0){
            $output = tap($physicians->paginate($length))->map(function($physician) use ($memberId){
                $memberRemark = FinalRemark::where(["physician_id" => $physician->id,"member_id" => $memberId])->first();
                $physician->memberRemark = $memberRemark ? true : false;
                $physician->encodeId = base64_encode($physician->id);
                return $physician;
            });
        }else{
            $physicians = $physicians->get()->map(function($physician) use ($memberId){
                $memberRemark = FinalRemark::where(["physician_id" => $physician->id,"member_id" => $memberId])->first();
                $physician->memberRemark = $memberRemark ? true : false;
                $physician->encodeId = base64_encode($physician->id);
                return $physician;
            });
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
    public function complete(Request $request){
        $encodeId = !empty($request->id) ? base64_decode($request->id) : "";
        $memberId = $request->session()->get("memberId") ? $request->session()->get("memberId") : "";
        $physician = Physician::where("id",$encodeId)->first();
        if(empty($physician)){
            return Redirect::route("remarks");
        }
        $sameSpeciality = Review::where(["physician_id" => $physician->id,"type" => "same"])->first();
        $differentSpeciality = Review::where(["physician_id" => $physician->id,"type" => "different"])->first();
        if(!empty($sameSpeciality) && !empty($sameSpeciality->scan_evalutaion_url)){
            $sameSpeciality->extension = pathinfo($sameSpeciality->scan_evalutaion_url,PATHINFO_EXTENSION);
        }
        if(!empty($differentSpeciality) && !empty($differentSpeciality->scan_evalutaion_url)){
            $differentSpeciality->extension = pathinfo($differentSpeciality->scan_evalutaion_url,PATHINFO_EXTENSION);
        }
        $adminRemark = AdminRemark::where("physician_id",$physician->id)->first();
        $memberRemark = FinalRemark::with('committee')->where(["physician_id" => $physician->id,"member_id" => $memberId])->first();
        return Inertia::render("Frontend/RemarkDetail",compact("physician","sameSpeciality","differentSpeciality","adminRemark","memberRemark","memberId"));
    }
    public function save(Request $request){
        $remark = FinalRemark::where(["physician_id" => $request->physician_id,"member_id" => $request->member_id])->first();
        if(empty($remark)){
            $remark = new FinalRemark();
            $remark->physician_id = $request->physician_id;
            $remark->member_id = $request->member_id;
        }
        $remark->flag1 = $request->flag1 ? $request->flag1 : null;
        $remark->flag2 = $request->flag2 ? $request->flag2 : null;
        $remark->flag3 = $request->flag3 ? $request->flag3 : null;
        $remark->flag4 = $request->flag4 ? $request->flag4 : null;
        $remark->flag5 = $request->flag5 ? $request->flag5 : null;
        $remark->flag6 = $request->flag6 ? $request->flag6 : null;
        $remark->description1 = $request->description1 ? $request->description1 : null;
        $remark->description2 = $request->description2 ? $request->description2 : null;
        $remark->description3 = $request->description3 ? $request->description3 : null;
        $remark->description4 = $request->description4 ? $request->description4 : null;
        $remark->description5 = $request->description5 ? $request->description5 : null;
        $remark->description6 = $request->description6 ? $request->description6 : null;
        $remark->save();
        return json_encode(["status" => "success","message" => "You Evaluated the application successfully."]);
    }
}