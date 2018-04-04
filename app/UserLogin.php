<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_login';
    protected $fillable = [
        'ip'
    ];

    /**
     * @return mixed
     */

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeLatest($query)
    {
        $query->orderBy('updated_at', 'desc');
    }
}
