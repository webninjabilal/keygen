<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sheet extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sheets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'prefix',
        'minimum',
        'maximum',
        'sheet_integers',
        'company_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
