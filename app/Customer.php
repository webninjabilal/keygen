<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'company_id',
        'name',
        'created_by'
    ];

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

    public function created_user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function machine()
    {
        return $this->hasMany('App\MachineUser', 'customer_id');
    }
}
