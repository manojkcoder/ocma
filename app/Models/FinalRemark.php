<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Committee;
use App\Models\Physician;

class FinalRemark extends Model
{
    use HasFactory;
    protected $fillable = ["physician_id","member_id","flag1","flag2","flag3","flag4","flag5","flag6","flag7","description1","description2","description3","description4","description5","description6","remark"];
    protected $casts = [
        "physician_id" => "integer",
        "member_id" => "integer",
        "flag1" => "boolean",
        "flag2" => "boolean",
        "flag3" => "boolean",
        "flag4" => "boolean",
        "flag5" => "boolean",
        "flag6" => "boolean",
        "flag7" => "boolean",
        "description1" => "string",
        "description2" => "string",
        "description3" => "string",
        "description4" => "string",
        "description5" => "string",
        "description6" => "string",
        "remark" => "string"
    ];
    public function physician(){
        return $this->belongsTo(Physician::class);
    }
    public function committee(){
        return $this->belongsTo(Committee::class);
    }
}