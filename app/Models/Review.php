<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Physician;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ["physician_id","type","reviewer_name","primary_specialty","phone","signature","is_physician_care","evaluation_ans1","evaluation_ans2","rating_community","rating_judgement","rating_healthcare","rating_patients","rating_skills","scan_evalutaion_url"];
    protected $casts = [
        "physician_id" => "integer",
        "is_physician_care" => "boolean"
    ];
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
    public function physician(){
        return $this->belongsTo(Physician::class);
    }
}