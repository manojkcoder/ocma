<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Physician extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ["firstans","secondans","thirdans","fourthans","designation","first_name","last_name","full_name","email","medical_license","date_issue","primary_specialty","organization","address1","address2","city","state","zip","phone","fax","primary_hospital_affiliation","special_interest","signature","form_status","mark_complete","scan_application_url","leadership_positions","elected_members","teaching_activities","medical_educations","medical_advances","participation_activities","other_activities"];
    protected $casts = [
        "firstans" => "boolean",
        "secondans" => "boolean",
        "thirdans" => "boolean",
        "fourthans" => "boolean",
        "mark_complete" => "boolean",
        "leadership_positions" => "array",
        "elected_members" => "array",
        "teaching_activities" => "array",
        "medical_educations" => "array",
        "medical_advances" => "array",
        "participation_activities" => "array",
        "other_activities" => "array"
    ];
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
}