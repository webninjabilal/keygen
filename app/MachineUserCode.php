<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MachineUserCode extends Model
{
    use SoftDeletes;
    
    protected $table = 'machine_user_codes';

    protected $fillable = [
        'machine_user_id',
        'status',
        'serial_number',
        'used_date',
        'uses',
        'code',
        'created_by',
        'machine_id',
        'block_serial_number',
        'notes'
    ];

    public function machine_user()
    {
        return $this->belongsTo('App\MachineUser', 'machine_user_id');
    }

    public function created_user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function machine()
    {
        return $this->belongsTo('App\Machine', 'machine_id');
    }
}
