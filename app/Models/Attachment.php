<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Physician;
use Carbon\Carbon;

class Attachment extends Model
{
    use HasFactory;
    protected $fillable = ["physician_id","document_name","document_path","document_type","document_size","document_caption"];
    protected $casts = [
        "physician_id" => "integer",
        "document_name" => "string",
        "document_path" => "string",
        "document_type" => "string",
        "document_size" => "string",
        "document_caption" => "string"
    ];
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
    public function physician(){
        return $this->belongsTo(Physician::class);
    }
}