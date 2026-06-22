<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $guarded = [];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function reservationRooms()
    {
        return $this->hasMany(ReservationRoom::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function fnbOrders()
    {
        return $this->hasMany(FnbOrder::class);
    }

    public function laundryRequests()
    {
        return $this->hasMany(LaundryRequest::class);
    }

    public function extraCharges()
    {
        return $this->hasMany(ExtraCharge::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
