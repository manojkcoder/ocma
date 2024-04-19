<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;

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

Route::group(["middleware" => "role:physician"],function(){
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