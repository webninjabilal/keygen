<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'status'
    ];

    public function contacts()
    {
        return $this->belongsToMany('App\User','company_user')->withTimestamps();
    }


    public function assignContact($user,array $addtional)
    {
        return $this->contacts()->attach($user,$addtional);
    }


    public function sheet() {
        return $this->hasMany('App\Sheet', 'company_id');
    }

    public function unit() {
        return $this->hasMany('App\Unit', 'company_id');
    }

    public function machine() {
        return $this->hasMany('App\Machine', 'company_id');
    }

    public static function userCurrentCompany($user_id)
    {
        $currentCompany = DB::table('company_user')
            ->where('user_id',$user_id)
            ->orderBy('company_id','ASC')
            ->first();

        if(!$currentCompany)
            return false;

        return $currentCompany->company_id;
    }
}
