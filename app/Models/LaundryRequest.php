<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaundryRequest extends Model
{
    protected $guarded = [];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function laundryService()
    {
        return $this->belongsTo(LaundryService::class);
    }

    public function foUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id_fo');
    }

    public function hkUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id_hk');
    }
}
