<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
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

class AdminController extends Controller
{
    public function nominationDetails(Request $request,$id){
        $print = $request->print ? $request->print : '';
        $member1 = $member2 = $member1Remark = $member2Remark = "";
        $physician = Physician::where("id",$id)->first();
        if(empty($physician)){
            return Redirect::route("admin");
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
        $member = MemberAssign::where("physician_id",$physician->id)->first();
        if(!empty($member)){
            if(!empty($member->same_member_id)){
                $member1 = Committee::where("id",$member->same_member_id)->first();
                $member1Remark = FinalRemark::with('committee')->where(["physician_id" => $physician->id,"member_id" => $member->same_member_id])->first();
            }
            if(!empty($member->different_member_id)){
                $member2 = Committee::where("id",$member->different_member_id)->first();
                $member2Remark = FinalRemark::with('committee')->where(["physician_id" => $physician->id,"member_id" => $member->different_member_id])->first();
            }
        }
        return Inertia::render("Admin/NominationDetail",compact("physician","sameSpeciality","differentSpeciality","adminRemark","member1","member2","member1Remark","member2Remark","print"));
    }
    public function adminRemarks(Request $request){
        $remark = AdminRemark::where("physician_id",$request->physician_id)->first();
        if(empty($remark)){
            $remark = new AdminRemark();
            $remark->physician_id = $request->physician_id;
        }
        $remark->flag1 = $request->flag1;
        $remark->flag2 = $request->flag2;
        $remark->flag3 = $request->flag3;
        $remark->flag4 = $request->flag4;
        $remark->flag5 = $request->flag5;
        $remark->flag6 = $request->flag6;
        $remark->flag7 = $request->flag7;
        $remark->save();
        return json_encode(["status" => "success","message" => "You Evaluated the application successfully."]);
    }
    public function evaluateDetails(Request $request){
        $id = $request->id ? $request->id : '';
        $type = $request->type ? $request->type : '';
        $memberRating = "";
        $physician = Physician::where("id",$id)->first();
        if(!empty($physician)){
            $memberRating = Review::where(["physician_id" => $id,"type" => $type])->first();
            if(!empty($memberRating) && !empty($memberRating->scan_evalutaion_url)){
                $memberRating->extension = pathinfo($memberRating->scan_evalutaion_url,PATHINFO_EXTENSION);
            }
        }
        return Inertia::render("Admin/EvaluateDetails",compact("physician","memberRating","type"));
    }
    public function changeEvaluation(Request $request,$id,$type){
        $memberRating = Review::where(["physician_id" => $id,"type" => $type])->first();
        if(empty($memberRating)){
            return Redirect::route("admin");
        }
        return Inertia::render("Admin/ChangeEvaluation",compact("id","memberRating","type"));
    }
    public function updateChangeEvaluation(Request $request){
        $memberRating = Review::where(["physician_id" => $request->physician_id,"type" => $request->type])->first();
        if(empty($memberRating)){
            return json_encode(["status" => "success","message" => "Evaluation not found."]);
        }
        $memberRating->primary_specialty = $request->primary_specialty;
        $memberRating->save();
        return json_encode(["status" => "success","message" => "Date Updated Successfully."]);
    }
    public function uploadEvaluation(Request $request,$id,$type){
        return Inertia::render("Admin/UploadEvaluation",compact("id","type"));
    }
    public function updateEvaluation(Request $request){
        $memberRating = Review::where(["physician_id" => $request->physician_id,"type" => $request->type])->first();
        if(empty($memberRating)){
            $memberRating = new Review();
            $memberRating->physician_id = $request->physician_id;
            $memberRating->type = $request->type;
        }
        $file = $request->file("file");
        $fileName = $this->randomString(16) . '.' . $file->getClientOriginalExtension();
        $file->move("assets/attachments",$fileName);
        $memberRating->scan_evalutaion_url = "/assets/attachments/" . $fileName;
        $memberRating->save();
        return json_encode(["status" => "success","message" => "Date Updated Successfully."]);
    }
    public function deleteEvaluation(Request $request,$id,$type){
        $memberRating = Review::where(["physician_id" => $id,"type" => $type])->first();
        if(empty($memberRating)){
            return json_encode(["status" => "error","message" => "Evaluation not exist."]);
        }
        $memberRating->delete();
        return json_encode(["status" => "success","message" => "Evaluation deleted successfully."]);
    }
    public function assignMember(Request $request){
        $member = MemberAssign::where("physician_id",$request->item_id)->first();
        if(empty($member)){
            $member = new MemberAssign();
        }
        $member->physician_id = $request->item_id;
        if($request->type == 'same'){
            $member->same_member_id = $request->member_id;
        }else if($request->type == 'different'){
            $member->different_member_id = $request->member_id;
        }
        $member->save();
        return json_encode(["status" => "success","message" => "Member assigned successfully."]);
    }
    private function randomString($length = 16){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";	
        $size = strlen($chars);
        $string = '';
        for($i = 0;$i < $length;$i++){
            $string .= $chars[rand(0,$size - 1)];
        }
        return $string;
    }
}