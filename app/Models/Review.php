<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ["nomination_id","type","reviewer_name","primary_specialty","phone","signature","is_physician_care","evaluation_ans1","evaluation_ans2","rating_community","rating_judgement","rating_healthcare","rating_patients","rating_skills"];
    protected $casts = [
        "nomination_id" => "integer",
        "is_physician_care" => "boolean"
    ];
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
}