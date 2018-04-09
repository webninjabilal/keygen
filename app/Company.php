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

    public static function childCompanies($company_id)
    {
        $list = [];
        $companyParent = self::findOrNew($company_id);
        $list[$companyParent->id] = $companyParent->name;
        $companies = self::where('parent_id',$company_id)->where('status',1)->get();
        if($companies) {
            foreach($companies as $company) {
                $list[$company->id] = $company->name;
                $child_companies = self::childCompanies($company->id);
                if(count($child_companies) > 0)
                    $list = $list + $child_companies;
            }
        }
        return $list;
    }

    /**
     * @param $user_id
     * @return array
     */
    public static function allUserCompanies($user_id)
    {
        $list = [];
        $companies = CompanyUser::where('user_id',$user_id)->orderby('company_id','ASC')->get();
        if($companies) {
            foreach($companies as $company) {
                $company = self::findOrFail($company->company_id);
                $list[$company->id] = $company->name;
            }
        }
        return $list;
    }

    public static function allCompanies()
    {
        return self::where('status', 1)->pluck('name', 'id')->toArray();
    }

    public static function userLevelCompanies($user_id)
    {
        $user = User::findOrFail($user_id);
        $company_id = self::userCurrentCompany($user_id);
        if($user->isAdmin()) {
            $companies = self::allCompanies();

            /*$companies = self::childCompanies($company_id);
            $companies = $companies + self::allUserCompanies($user_id);*/
        } else {
            $companies = self::allUserCompanies($user_id);
        }
        return $companies;
        return array_unique($companies);
    }
}
