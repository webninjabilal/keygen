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
        'user_id',
        'machine_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function machine()
    {
        return $this->belongsTo('App\Machine', 'machine_id');
    }

    public function code()
    {
        return $this->hasMany('App\MachineUserCode', 'machine_user_id');
    }
}
