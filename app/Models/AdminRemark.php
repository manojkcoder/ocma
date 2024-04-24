<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Physician;

class AdminRemark extends Model
{
    use HasFactory;
    protected $fillable = ["physician_id","flag1","flag2","flag3","flag4","flag5","flag6","flag7"];
    protected $casts = [
        "physician_id" => "integer",
        "flag1" => "boolean",
        "flag2" => "boolean",
        "flag3" => "boolean",
        "flag4" => "boolean",
        "flag5" => "boolean",
        "flag6" => "boolean",
        "flag7" => "boolean"
    ];
    public function physician(){
        return $this->belongsTo(Physician::class);
    }
}