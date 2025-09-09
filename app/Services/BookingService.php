<?php

namespace App\Services;

use App\Repositories\BookingRepositoryInterface;
use App\Http\Resources\BookingResource;
use App\Models\Booking;

class BookingService
{
    public function __construct(
        protected BookingRepositoryInterface $bookingRepository
    ){
    }

    public function bookTicket(int $event_id, array $booking)
    {
        $booking = $this->bookingRepository->bookTicket($event_id, $booking);

        return $booking;
    }

    public function listBookings()
    {
        $bookings = $this->bookingRepository->listBookings();

        return $bookings;
    }
}