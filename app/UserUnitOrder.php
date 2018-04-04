<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserUnitOrder extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_unit_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'unit_id',
        'machine_date',
        'filter_type',
        'machine_id',
        'quantity',
        'status',
        'billing_id',
        'license_key'
    ];

    protected $dates = ['machine_date'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function unit()
    {
        return $this->belongsTo('App\Unit', 'unit_id');
    }

    public function machine()
    {
        return $this->belongsTo('App\Machine', 'machine_id');
    }
}
