<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventBooking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'event_id', 'booking_date'];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function events()
    {
        return $this->belongsTo(Event::class,'event_id','id');
    }
}
