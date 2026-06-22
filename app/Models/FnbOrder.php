<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbOrder extends Model
{
    protected $guarded = [];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function fnbOrderItems()
    {
        return $this->hasMany(FnbOrderItem::class);
    }
}
