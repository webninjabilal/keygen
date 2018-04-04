<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Machine extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'machines';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nick_name',
        'prefix',
        'serial_number',
        'is_time_base',
        'status',
        'notes',
        'sheet_id',
        'company_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function sheet()
    {
        return $this->belongsTo('App\Sheet', 'sheet_id');
    }

    public static function statuses($status = 0)
    {
        $statuses =  [
            1 => 'Enabled',
            2 => 'Disabled'
        ];
        if(isset($statuses[$status])) {
            return $statuses[$status];
        }
        return $statuses;
    }

    public function checkSerialAvail($serial_number, $sheet_id)
    {
        if($this->id > 0) {
            $machine = self::where('serial_number', $serial_number)->where('id', '!=', $this->id)->where('sheet_id', $sheet_id)->first();
        } else {
            $machine = self::where('serial_number', $serial_number)->where('sheet_id', $sheet_id)->first();
        }
        if(!$machine) return true;
        return false;
    }

    public static function all_serial($user, $type = '')
    {
        $query = self::where('user_id', $user->id);
        if($type != '') {
            $query->where('is_time_base', $type);
        }
        return $query->get();
        $data =  $query->pluck(DB::raw('CONCAT(nick_name, "(", prefix, "-", "serial_number", ")")'), 'id')->toArray();
        return [];
    }
}
