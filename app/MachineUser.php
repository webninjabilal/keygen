<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MachineUser extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'machine_users';

    protected $fillable = [
        'customer_id',
        'machine_id',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function machine()
    {
        return $this->belongsTo('App\Machine', 'machine_id');
    }

    public function code()
    {
        return $this->hasMany('App\MachineUserCode', 'machine_user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
