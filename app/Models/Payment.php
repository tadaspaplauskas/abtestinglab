<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'user_id',
        'email',
        'visitors',
        'plan',
        'quantity',
        'gross',
        'txn_id',
        'dump',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
