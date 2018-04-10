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
        'status',
        'notes',
        'sheet_id',
        'company_id',
        'country_id',
        'random_numbers',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function sheet()
    {
        return $this->belongsTo('App\Sheet', 'sheet_id');
    }

    public function machine_user()
    {
        return $this->hasMany('App\MachineUser', 'machine_id');
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

    public function generate_code($used_date, $serial_number, $uses)
    {
        $random_numbers = $this->random_numbers;
        $random_numbers = nl2br($this->random_numbers);
        $random_numbers = explode('<br />',$random_numbers);
        //$random_numbers = (!empty($random_numbers)) ? explode(',', $random_numbers) : [];
        if(count($random_numbers) > 0) {
            $used_date_explode = explode('-', $used_date);
            
            $dayIndex       = $used_date_explode[2] + 1 - 1;
            $monthIndex     = $used_date_explode[1] + 1 - 1;
            $yearIndex      = $used_date_explode[0] - 1999 - 1;
            $dayCode        = (isset($random_numbers[$dayIndex])) ? $random_numbers[$dayIndex] : 0;
            $monthCode      = (isset($random_numbers[$monthIndex])) ? $random_numbers[$monthIndex] : 0;
            $yearCode       = (isset($random_numbers[$yearIndex])) ? $random_numbers[$yearIndex] : 0;

            $serial_formula_1   = floor($serial_number/10000);
            $serial_1_index     = $serial_formula_1;
            $serial_1           = (isset($random_numbers[$serial_1_index])) ? $random_numbers[$serial_1_index] : 0;

            $serial_formula_2   = ($serial_number - ($serial_formula_1 * 10000));
            $serial_2_index     = floor($serial_formula_2/100);
            $serial_2           = (isset($random_numbers[$serial_2_index])) ? $random_numbers[$serial_2_index] : 0;

            $serial_formula_3   = floor($serial_formula_2) - (floor($serial_formula_2/100) * 100);
            $serial_3_index     = ($serial_formula_3);
            $serial_3           = (isset($random_numbers[$serial_3_index])) ? $random_numbers[$serial_3_index] : 0;

            $uses_1_formula     = floor($uses/100);
            $uses_1_index       = ($uses_1_formula);
            $uses_1             = (isset($random_numbers[$uses_1_index])) ? $random_numbers[$uses_1_index] : 0;

            $uses_2_formula     =  $uses - ($uses_1_formula * 100);
            $uses_2_index       = ($uses_2_formula);
            $uses_2             = (isset($random_numbers[$uses_2_index])) ? $random_numbers[$uses_2_index] : 0;

            $average_1      = floor(($dayCode + $monthCode)/2);
            $average_2      = floor(($yearCode + $serial_1)/2);
            $average_3      = floor(($average_1    + $average_2)/2);
            $average_4      = floor(($serial_2     + $serial_3)/2);
            $average_5      = floor(($average_4    + $average_3)/2);
            $average_6      = floor(($uses_1       + $uses_2)/2);

            $average_7      =  $average_6 + $average_5;

            if(!empty($average_7)) {
                return $average_7;
            }
        }
        return false;
    }
}
