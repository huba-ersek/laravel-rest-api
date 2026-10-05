<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'arms',
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
