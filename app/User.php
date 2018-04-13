<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;

    use EntrustUserTrait { restore as private restoreB; }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'gender',
        'phone',
        'password',
        'user_name',
        'customer_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['full_name'];

    function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role')->withTimestamps();
    }

    public function isAdmin()
    {
        if($this->id == 1) {
            return true;
        }
        return $this->hasRole('Admin');
    }

    public function isCustomer()
    {
        if($this->id == 1) {
            return false;
        }
        return $this->hasRole('Customer');
    }

    /*public function hasRole($name)
    {
        foreach ($this->roles as $role)
        {
            if ($role->name == $name) return true;
        }
        return false;
    }*/


    public static function getUsersByRoles(Array $roles)
    {
        return User::whereHas('roles', function($q)  use ($roles)
        {
            $q->whereIn('role_id', $roles);
        })->where('status',1)->get();
    }

    public static function getUserRoleName($user_id) {
        $role_id = self::getUserRoleId($user_id);
        $role  = Role::where('id', $role_id)->first();
        return ($role) ? $role->name : '';
    }

    public static function allRoles()
    {
        return [
            '1' =>'Admin',
             '2' =>'Customer',
            //'4' =>'Affiliate',
        ];
    }

    public static function getUserRoleId($user_id){
        $role_assign = DB::table('role_user')->where('user_id',$user_id)->first();
        return ($role_assign) ? $role_assign->role_id : false;
    }

    public static function gender_list()
    {
        return [
            'male' => 'Male',
            'female' => 'Female',
        ];
    }

    public function assignRole($role)
    {
        $this->roles()->detach();
        return $this->roles()->attach($role);
    }

    public function login() {
        return $this->hasMany('App\UserLogin', 'user_id');
    }

    public function sheet() {
        return $this->hasMany('App\Sheet', 'user_id');
    }

    public function unit() {
        return $this->hasMany('App\Unit', 'user_id');
    }

    public function machine() {
        return $this->hasMany('App\Machine', 'user_id');
    }

    public function customer() {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function log()
    {
        return $this->hasMany('App\Log','user_id');
    }




    public function unit_order() {
        return $this->hasMany('App\UserUnitOrder', 'user_id');
    }

    public function company()
    {
        return $this->belongsToMany('App\Company','company_user')->withTimestamps();
    }

    public static function company_user($company_id) {
        $listing = [];
        $users = self::whereHas('company', function($query) use ($company_id) {
            $query->where('company_id', $company_id);
        });

        $users = $users->get();
        if (count($users) > 0) {
            foreach ($users as $user) {
                $listing[$user->id] = $user->full_name;
            }
        }
        return $listing;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

}
