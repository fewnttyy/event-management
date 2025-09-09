<?php

namespace App\Repositories;

interface BookingRepositoryInterface
{
    public function bookTicket(int $event_id, array $booking);

    public function listBookings();
}
