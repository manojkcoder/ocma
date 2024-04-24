<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Physician;

class MemberAssign extends Model
{
    use HasFactory;
    protected $fillable = ["physician_id","same_member_id","different_member_id"];
    protected $casts = [
        "physician_id" => "integer",
        "same_member_id" => "integer",
        "different_member_id" => "integer"
    ];
    public function physician(){
        return $this->belongsTo(Physician::class);
    }
}