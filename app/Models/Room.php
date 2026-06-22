<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $guarded = [];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function inspections()
    {
        return $this->hasMany(RoomInspection::class);
    }

    public function connectingRoom()
    {
        return $this->belongsTo(Room::class, 'connecting_room_id');
    }
}
