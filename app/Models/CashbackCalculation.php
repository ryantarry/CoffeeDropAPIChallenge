<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashbackCalculation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cashback_calculations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ristretto',
        'espresso',
        'lungo',
        'cashback'
    ];

}
