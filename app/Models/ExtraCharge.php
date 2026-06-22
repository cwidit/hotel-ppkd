<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraCharge extends Model
{
    protected $guarded = [];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
