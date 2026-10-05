<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'zip_code',
        'city',
        'county_id',
        'population',
    ];

    public function county()
    {
        return $this->belongsTo(County::class);
    }
}
