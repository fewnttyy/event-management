<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Event;
use App\Jobs\SendBookingConfirmationEmail;

class BookingRepository implements BookingRepositoryInterface
{
    public function bookTicket(int $event_id, array $booking)
    {
        $user = auth()->user();

        if(!$user) {
            throw new \Exception('User is not logged in');
        }

        $event = Event::find($event_id);

        if(!$event) {
            throw new \Exception('Event not found');
        }

        if($event->quota < $booking['quantity']) {
            throw new \Exception('Booking quantity is more than event quota');
        }

        $event->quota -= $booking['quantity'];
        $event->save();

        $booking = Booking::create([
            'user_id' => $user->id,
            'event_id' => $event_id,
            'date' => $booking['date'],
            'quantity' => $booking['quantity'],
            'status' => 'confirmed',
        ]);

        SendBookingConfirmationEmail::dispatch($booking);

        return $booking;
    }

    public function listBookings()
    {
        $user = auth()->user();

        if(!$user) {
            throw new \Exception('User is not logged in');
        }

        $bookings = Booking::where('user_id', $user->id)->get();

        if($bookings->isEmpty()){
            throw new \Exception('No bookings data yet');
        }

        return $bookings;
    }
}