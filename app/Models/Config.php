<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

class Config extends Model
{
    use HasFactory;
    //protected $casts=['value'=>'json'];
    protected $fillable=['organization_id','key','value','remark'];
    protected $casts=['value'=>'json'];
    
    static function item($key,$organization=null)
    {
        $item=null;
        if($organization){
            $item = Config::where('organization_id',$organization->id)->where('key', $key)->first();
            if(empty($item)){
                $item = Config::where('organization_id',0)->where('key', $key)->first();
            }
        }elseif(Session::has('organization')){
            $item = Config::where('organization_id',session('organization')->id)->where('key', $key)->first();
            if(empty($item)){
                $item = Config::where('organization_id',0)->where('key', $key)->first();
            }
        }else{
            $item = Config::where('organization_id',0)->where('key', $key)->first();
        }
        if ($item) {
            return $item->value;
        } else {
            return false;
        }
    }

    // static function items($key,$organizationId=0)
    // {
    //     $items = Config::where('organization_id',$organizationId)->where('key', $key)->get();
    //     $collections=[];
    //     foreach($items as $item){
    //         $collections[]=json_decode($item->value,true);
    //     }
    //     return $collections;
    // }

    static function items($key='', $organization=null)
    {
        if($organization){
            $items = Config::where('organization_id',$organization->id)->where('key', 'categories_weights')->get();
        // }elseif(session('organization')){
        //     $items = Config::where('organization_id',session('organization')->id)->where('key', 'categories_weights')->get();
        }else{
            $items = Config::where('organization_id',0)->where('key', 'categories_weights')->get();
        }
        
        $collections=[];
        if(!empty($items)){
            foreach($items as $item){
                $collections[]=json_decode($item->value,true);
            }
        }
        return $collections;
    }

}
