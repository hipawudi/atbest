<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Member extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'organization_id',
        'given_name',
        'family_name',
        'middle_name',
        'display_name',
        'gender',
        'dob',
        'email',
        'mobile',
        'country',
        'nationality',
        'city',
        'street',
        'zip',
        'vat',
        'address',
        'club',
        'avatar'
    ];

    protected $appends = ['avatar_url', 'member_number'];

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? Storage::url($this->avatar) : '';
    }

    public function getMemberNumberAttribute()
    {
        return substr('000000' . $this->id, -5);
    }
    public function createUser(): User
    {
        $user = new User();
        $user->email = $this->email;
        $user->name = $this->given_name;
        $user->password = 'need-to-set';
        $user->save();
        $this->user_id = $user->id;
        $this->save();
        return $user;
    }

    public function user()
    {
        return $this->belongsTo(User::class)->with('roles');
    }

    public function ownedBy($organization = null)
    {
        return $this->organization_id == $organization->id;
        //return in_array($organization->id,$this->organizations()->get()->pluck('id')->toArray());
    }

    // public function hasUser()
    // {
    //     return $this->user()->exists();
    // }

    // public function organizations(){
    //     return $this->belongsToMany(Organization::class);
    // }
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function belongsToOrganization($organization)
    {
        return $this->belongsToMany(Organization::class)->wherePivot('organization_id', $organization->id);
    }
    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }
    public function certificates()
    {
        return $this->belongsToMany(Certificate::class)->withPivot(
            'id',
            'display_name',
            'number',
            'number_display',
            'issue_date',
            'valid_from',
            'valid_until',
            'authorize_by',
            'rank',
            'avatar'
        );
    }
}
