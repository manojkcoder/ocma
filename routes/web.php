<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CommitteeController;
use App\Http\Controllers\Admin\PhysicianController;
use App\Http\Controllers\MemberController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/authenticate",[FrontendController::class,"authenticate"])->name("authenticate");
Route::post("/validate-authenticate",[FrontendController::class,"validateAuthenticate"])->name("validate-authenticate");
Route::get("/evaluation",[FrontendController::class,"evaluation"])->name("evaluation");
Route::post("/submit-reviews",[FrontendController::class,"submitReviews"])->name("submit-reviews");

Route::get("/member-login",[MemberController::class,"login"])->name("member-login");
Route::post("/member-authenticate",[MemberController::class,"authenticate"])->name("member-authenticate");

Route::group(["middleware" => "role:physician|admin",],function(){
    Route::get("/",[FrontendController::class,"index"])->name("home");
    Route::get("/start",[FrontendController::class,"start"])->name("start");
    Route::get("/invite",[FrontendController::class,"invite"])->name("invite");

    Route::get("/edit-nomination",[FrontendController::class,"editNomination"])->name("edit-nomination");
    Route::get("/invite-people",[FrontendController::class,"invitePeople"])->name("invite-people");

    Route::post("/check-application-via-license",[FrontendController::class,"checkApplication"])->name("check-application-via-license");
    Route::post("/save-physician",[FrontendController::class,"savePhysician"])->name("save-physician");
    Route::post("/update-physician",[FrontendController::class,"updatePhysician"])->name("update-physician");

    Route::post("/send-invite-email",[FrontendController::class,"sendInviteEmail"])->name("send-invite-email");
});
Route::group(["middleware" => "role:admin"],function(){
    Route::get("/admin",[PhysicianController::class,"index"])->name("admin");
    Route::get("/all-physicians",[PhysicianController::class,"getAll"])->name("physicians.all");
    Route::get("/saved-applications",[PhysicianController::class,"saved"])->name("physicians.saved");
    Route::get("/upload-application",[PhysicianController::class,"upload"])->name("physicians.upload");
    Route::post("/upload-application",[PhysicianController::class,"uploadApp"])->name("physicians.upload");
    Route::post("/upload-attachment",[PhysicianController::class,"addAttachment"])->name("physicians.upload-attachment");
    Route::delete("/delete-attachment/{id}",[PhysicianController::class,"deleteAttachment"])->name("physicians.delete-attachment");
    Route::get("/move-to-saved/{id}",[PhysicianController::class,"moveToSaved"])->name("physicians.move-to-saved");
    Route::post("/mark-complete",[PhysicianController::class,"markComplete"])->name("physicians.mark-complete");

    Route::get("/nomination-details/{id}",[AdminController::class,"nominationDetails"])->name("nomination-details");
    Route::get("/evaluate-details",[AdminController::class,"evaluateDetails"])->name("evaluate-details");
    Route::get("/change-evaluation/{id}/{type}",[AdminController::class,"changeEvaluation"])->name("change-evaluation");
    Route::post("/change-update-evaluation",[AdminController::class,"updateChangeEvaluation"])->name("change-update-evaluation");
    Route::get("/upload-evaluation/{id}/{type}",[AdminController::class,"uploadEvaluation"])->name("upload-evaluation");
    Route::get("/delete-evaluation/{id}/{type}",[AdminController::class,"deleteEvaluation"])->name("delete-evaluation");
    Route::post("/update-evaluation",[AdminController::class,"updateEvaluation"])->name("update-evaluation");
    Route::post("/assign-member",[AdminController::class,"assignMember"])->name("assign-member");
    
    Route::post("/admin-remarks",[AdminController::class,"adminRemarks"])->name("admin-remarks");

    Route::get("/committees",[CommitteeController::class,"index"])->name("committees");
    Route::get("/committees/all",[CommitteeController::class,"getAll"])->name("committees.all");
    Route::post("/committees/add",[CommitteeController::class,"add"])->name("committees.add");
    Route::delete("/all-committees/delete/{id}",[CommitteeController::class,"delete"])->name("committees.delete");
});
Route::group(["middleware" => "role:committee"],function(){
    Route::get("/remarks",[MemberController::class,"remarks"])->name("remarks");
    Route::get("/remarks/all",[MemberController::class,"getAllRemarks"])->name("remarks.all");
    Route::get("/nomination/details",[MemberController::class,"complete"])->name("remarks.complete");
    Route::post("/remarks/save",[MemberController::class,"save"])->name("remarks.save");
});