<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable=['organization_id','category_code','title','content','sender','receiver','created_by','updated_by'];
    protected $casts=['receiver'=>'array'];
 
    protected $appends = [
        'received_members',
    ];

    public function getReceivedMembersAttribute()
    {
        if($this->receiver){
            return Member::whereIn('id',$this->receiver)->get();
        }else{
            return null;
        }
        
    }
    // public function received_member(){
    //     return $this->belongsTo(Member::class,'receiver');
    // }
}
