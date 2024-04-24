<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Attachment;
use App\Models\Committee;
use App\Models\Physician;
use App\Models\Review;
use App\Models\MemberAssign;
use App\Models\AdminRemark;
use App\Models\FinalRemark;

class PhysicianController extends Controller
{
    public function index(Request $request): Response{
        $members = Committee::orderBy("name","ASC")->get(["id","name"]);
        return Inertia::render("Admin/Physician/Lists",compact("members"));
    }
    public function getAll(Request $request){
        $length = $request->length ? $request->length : 10;
        $search = $request->search ? $request->search : "";
        $formStatus = $request->status ? $request->status : "submitted";
        $physicians = Physician::with("attachments")->where("form_status",$formStatus)->orderBy("id","desc");
        if(!empty($search)){
            $physicians = $physicians->where(function($query) use($search){
                $query->where("first_name","LIKE","%{$search}%")
                      ->orWhere("last_name","LIKE","%{$search}%")
                      ->orWhere("email","LIKE","%{$search}%")
                      ->orWhere("medical_license","LIKE","%{$search}%");
            });
        }
        if($length > 0){
            $output = tap($physicians->paginate($length))->map(function($physician){
                $adminStatus = $member1Status = $member2Status = false;
                $same_member_id = $different_member_id = "";
                $sameReview = Review::where(["physician_id" => $physician->id,"type" => "same"])->first();
                $differentReview = Review::where(["physician_id" => $physician->id,"type" => "different"])->first();
                $members = MemberAssign::where("physician_id",$physician->id)->first();
                $adminRemark = AdminRemark::where("physician_id",$physician->id)->first();
                if($adminRemark){
                    $adminStatus = true;
                }
                if($members){
                    $member1FinalRemark = FinalRemark::where(["physician_id" => $physician->id,"member_id" => $members->same_member_id])->first();
                    $member2FinalRemark = FinalRemark::where(["physician_id" => $physician->id,"member_id" => $members->different_member_id])->first();
                    if($member1FinalRemark){
                        $member1Status = true;
                    }
                    if($member2FinalRemark){
                        $member2Status = true;
                    }
                    $same_member_id = $members->same_member_id;
                    $different_member_id = $members->different_member_id;
                }
                $physician->sameReview = $sameReview ? true : false;
                $physician->differentReview = $differentReview ? true : false;
                $physician->members = $members;
                $physician->same_member_id = $same_member_id;
                $physician->different_member_id = $different_member_id;
                $physician->adminStatus = $adminStatus;
                $physician->member1Status = $member1Status;
                $physician->member2Status = $member2Status;
                return $physician;
            });
        }else{
            $physicians = $physicians->get()->map(function($physician){
                $adminStatus = $member1Status = $member2Status = false;
                $sameReview = Review::where(["physician_id" => $physician->id,"type" => "same"])->first();
                $differentReview = Review::where(["physician_id" => $physician->id,"type" => "different"])->first();
                $members = MemberAssign::where("physician_id",$physician->id)->first();
                $adminRemark = AdminRemark::where("physician_id",$physician->id)->first();
                if($adminRemark){
                    $adminStatus = true;
                }
                if($members){
                    $member1FinalRemark = FinalRemark::where(["physician_id" => $physician->id,"member_id" => $members->same_member_id])->first();
                    $member2FinalRemark = FinalRemark::where(["physician_id" => $physician->id,"member_id" => $members->different_member_id])->first();
                    if($member1FinalRemark){
                        $member1Status = true;
                    }
                    if($member2FinalRemark){
                        $member2Status = true;
                    }
                }
                $physician->sameReview = $sameReview ? true : false;
                $physician->differentReview = $differentReview ? true : false;
                $physician->members = $members;
                $physician->adminStatus = $adminStatus;
                $physician->member1Status = $member1Status;
                $physician->member2Status = $member2Status;
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
    public function saved(Request $request): Response{
        return Inertia::render("Admin/Physician/Saved");
    }
    public function upload(Request $request): Response{
        return Inertia::render("Admin/Physician/Upload");
    }
    public function uploadApp(Request $request){
        $physician = Physician::where("medical_license",$request->medical_license)->first();
        if(!empty($physician)){
            return json_encode(["status" => "error","message" => "Application already exist with same medical license no."]);
        }
        $physician = new Physician();
        $physician->first_name = $request->first_name;
        $physician->last_name = $request->last_name;
        $physician->medical_license = $request->medical_license;
        $file = $request->file("file");
        $fileName = $this->randomString(16) . '.' . $file->getClientOriginalExtension();
        $file->move("assets/attachments",$fileName);
        $physician->scan_application_url = "/assets/attachments/" . $fileName;
        $physician->form_status = "saved";
        $physician->save();
        return json_encode(["status" => "success","message" => "Application saved successfully."]);
    }
    public function moveToSaved(Request $request,$id){
        $physician = Physician::where("id",$id)->first();
        if(empty($physician)){
            return json_encode(["status" => "error","message" => "Physician not exist."]);
        }
        $physician->form_status = "saved";
        $physician->save();
        return json_encode(["status" => "success","message" => "Application status has been successfully changed to saved."]);
    }
    public function markComplete(Request $request){
        $physician = Physician::where("id",$request->id)->first();
        if(empty($physician)){
            return json_encode(["status" => "error","message" => "Physician not exist."]);
        }
        $physician->mark_complete = $request->status;
        $physician->save();
        return json_encode(["status" => "success","message" => "Status has been successfully changed."]);
    }
    public function addAttachment(Request $request){
        $file = $request->file("image");
        $fileName = $this->randomString(16) . '.' . $file->getClientOriginalExtension();
        $file->move("assets/attachments",$fileName);
        $attachment = new Attachment();
        $attachment->physician_id = $request->physician_id;
        $attachment->document_name = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
        $attachment->document_path = "/assets/attachments/" . $fileName;
        $attachment->document_type = $file->getClientOriginalExtension();
        $attachment->save();
        return json_encode(["status" => "success","message" => "Your file has been uploaded successfully.","attachment" => $attachment]);
    }
    public function deleteAttachment(Request $request,$id){
        $attachment = Attachment::where("id",$id)->first();
        if(empty($attachment)){
            return json_encode(["status" => "error","message" => "Attachment not exist."]);
        }
        if(file_exists(public_path($attachment->document_path))){
            unlink(public_path($attachment->document_path));
        }
        $attachment->delete();
        return json_encode(["status" => "success","message" => "Attachment has been deleted successfully."]);
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