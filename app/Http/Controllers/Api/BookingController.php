<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookTicketResource;
use App\Http\Resources\ListBookingsResource;
use App\Http\Resources\MessageResource;
use App\Services\BookingService;
use App\Http\Requests\BookingRequest;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {
    }

    public function bookTicket (int $event_id, BookingRequest $request)
    {
        try {
            $booking = $this->bookingService->bookTicket($event_id, $request->validated());
            return new MessageResource('Booking sucessful', new BookTicketResource($booking));

        } catch (\Exception $e) {
            return new MessageResource('Failed to booking event', [
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function listBookings ()
    {
        try {
            $bookings = $this->bookingService->listBookings();
            return ListBookingsResource::collection($bookings);
            
        } catch (\Exception $e) {
            return new MessageResource('No bookings data yet', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
