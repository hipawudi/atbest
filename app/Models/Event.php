<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['organization_id', 'category_code', 'credit', 'title_en', 'title_fn', 'start_date', 'end_date', 'description', 'remark', 'with_attendance', 'form_id'];
    protected $attributes = [
        'title_en' => '',
        'category_code' => '',
        'credit' => '',
        'start_date' => '',
    ];


    public function managers()
    {
        return $this->belongsToMany(Member::class, 'event_manager', 'event_id', 'member_id');
    }
}
