<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'postcode',
        'latitude',
        'longitude',
        'opening_times',
        'closing_times',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'opening_times' => 'array',
        'closing_times' => 'array', 
    ];
}
