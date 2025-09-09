<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'location',
        'date',
        'quota',
        'description',
    ];

    public function booking()
    {
        return $this->hasMany(Booking::class, 'event_id');
    }
}
