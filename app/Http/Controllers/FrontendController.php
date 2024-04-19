<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Physician;
use App\Models\Review;
use App\Jobs\InviteEmailJob;

class FrontendController extends Controller
{
    public function index(Request $request): Response{
        $license = $request->license ?? "";
        return Inertia::render("Frontend/Home",compact("license"));
    }
    public function authenticate(Request $request){
        // $isAuthenticate = $request->session()->get("isAuthenticate");
        // $authType = $request->session()->get("authType");
        // if(!empty($isAuthenticate) && !empty($authType)){
        //     if($authType == "physician"){
        //         return Redirect::route("start");
        //     }
        // }
        return Inertia::render("Frontend/Authenticate");
    }
    public function validateAuthenticate(Request $request){
        $status = "error";
        $message = "Invalid Passphrase";
        $isAuthenticate = "";
        $authType = "";
        if($request->password == env("AUTH_PHYSICIAN_PASS")){
            $isAuthenticate = 1;
            $authType = "physician";
            $status = "success";
            $message = "Validate Passphrase";
        }else if($request->password == env("AUTH_ADMIN_PASS")){
            $isAuthenticate = 1;
            $authType = "admin";
            $status = "success";
            $message = "Validate Passphrase";
        }
        if($isAuthenticate == 1){
            $request->session()->put("authType",$authType);
            $request->session()->put("isAuthenticate",$isAuthenticate);
        }
        return json_encode(compact("status","message","authType","isAuthenticate"));
    }
    public function evaluation(Request $request){
        $physician = "";
        $hasRating = false;
        $nominationId = !empty($request->id) ? base64_decode($request->id) : "";
        $type = !empty($request->type) ? base64_decode($request->type) : "";
        if($nominationId){
            $physician = Physician::where("id",$nominationId)->first();
            if($physician){
                $rating = Review::where("nomination_id",$physician->id)->first();
                if($rating){
                    $hasRating = true;
                }
            }
        }
        return Inertia::render("Frontend/Evaluation",compact("nominationId","type","physician","hasRating"));
    }
    public function submitReviews(Request $request){
        $review = new Review($request->all());
        $review->save();
        return json_encode(["status" => "success","message" => "The information has been received successfully."]);
    }
    public function start(Request $request): Response{
        return Inertia::render("Frontend/Start");
    }
    public function invite(Request $request): Response{
        $license = $request->session()->get("license");
        if(empty($license)){
            return Redirect::route("start");
        }
        $physician = Physician::where("medical_license",$license)->first();
        if(empty($physician)){
            return Redirect::route("start");
        }
        return Inertia::render("Frontend/Invite",compact("physician"));
    }
    public function invitePeople(Request $request): Response{
        $license = $request->session()->get("license");
        if(empty($license)){
            return Redirect::route("start");
        }
        $physician = Physician::where("medical_license",$license)->first();
        if(empty($physician)){
            return Redirect::route("start");
        }
        $encodedId = base64_encode($physician->id);
        $encodedSame = base64_encode("same");
        $encodedDifferent = base64_encode("different");
        return Inertia::render("Frontend/InvitePeople",compact("physician","encodedId","encodedSame","encodedDifferent"));
    }
    public function editNomination(Request $request): Response{
        $license = $request->session()->get("license");
        if(empty($license)){
            return Redirect::route("start");
        }
        $physician = Physician::where("medical_license",$license)->first();
        if(empty($physician)){
            return Redirect::route("start");
        }
        return Inertia::render("Frontend/EditNomination",compact("physician"));
    }
    public function checkApplication(Request $request){
        $license = $request->license;
        $physician = Physician::where("medical_license",$license)->first();
        $isFound = $physician ? true : false;
        $request->session()->put("license",$license);
        return json_encode(compact("isFound"));
    }
    public function savePhysician(Request $request){
        $physician = Physician::where("medical_license",$request->medical_license)->first();
        if(!$physician){
            $physician = new Physician($request->except("action"));
            $physician->save();
        }else{
            $physician->update($request->except("action"));
        }
        if($request->action == "save"){
            $physician->form_status = "saved";
            $physician->save();
        }else if($request->action == "submit"){
            $physician->form_status = "submitted";
            $physician->save();
        }
        return json_encode(["status" => "success","message" => "Your Application automatically saved."]);
    }
    public function updatePhysician(Request $request){
        $physician = Physician::findOrfail($request->id);
        $physician->update($request->except("action"));
        if($request->action == "save"){
            $physician->form_status = "saved";
            $physician->save();
        }else if($request->action == "submit"){
            $physician->form_status = "submitted";
            $physician->save();
        }
        return json_encode(["status" => "success","message" => "Your Application automatically saved."]);
    }
    public function sendInviteEmail(Request $request){
        $license = $request->session()->get("license");
        if(empty($license)){
            return Redirect::route("start");
        }
        $physician = Physician::where("medical_license",$license)->first();
        if(empty($physician)){
            return Redirect::route("start");
        }
        $encodedId = base64_encode($physician->id);
        $data = [
            "applicant_name" => ($physician->first_name ? $physician->first_name : "")." ".($physician->last_name ? $physician->last_name : ""),
            "link" => route("evaluation") . "?id=".$encodedId."&type=".base64_encode($request->specialty),
            "subject" => "Please Complete - OCMA Physicians of Excellence Physician Evaluation Form"
        ];
        $emails = explode("\n",$request->emails);
        if(count($emails)){
            $data["to_address"] = $emails;
            InviteEmailJob::dispatch($data);
        }
        return json_encode(["status" => "success","message" => "Email sent successfully."]);
    }
}