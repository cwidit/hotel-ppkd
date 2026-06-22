<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbOrderItem extends Model
{
    protected $guarded = [];

    public function fnbOrder()
    {
        return $this->belongsTo(FnbOrder::class);
    }

    public function fnbMenu()
    {
        return $this->belongsTo(FnbMenu::class);
    }
}
