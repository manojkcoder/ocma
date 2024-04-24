<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Committee extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ["name","email","phone","password"];
    protected $casts = [
        "name" => "string",
        "email" => "string",
        "phone" => "string",
        "password" => "string"
    ];
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
}