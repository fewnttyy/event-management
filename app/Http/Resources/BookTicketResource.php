<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'booking_id' => $this->booking_id,
            'event' => [
                'id' => $this->event->id,
                'title' => $this->event->title,
                'date' => $this->event->date,
            ],
            'quantity' => $this->quantity,
            'status' => $this->status,
        ];
    }
}
