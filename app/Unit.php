<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'units';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sku',
        'description',
        'company_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public static function filter_types()
    {
        return [
            2 => 'Both',
            1 => 'Time Based',
            0 => 'Unit Based'
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('id', '>', 0);
    }
}
