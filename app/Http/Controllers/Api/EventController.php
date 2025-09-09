<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Resources\MessageResource;
use App\Services\EventService;
use App\Http\Requests\StoreEventRequest;

class EventController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ) {
    }

    public function listEvents()    
    {
        try {
            $events = $this->eventService->listEvents();
            return EventResource::collection($events);

        } catch (\Exception $e) {
            return new MessageResource('no events list yet', [
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function addEvents(StoreEventRequest $request)
    {
        try {
            $event = $this->eventService->addEvents($request->validated());
            return new MessageResource('Event created successfully', new EventResource($event));

        } catch (\Exception $e) {
            return new MessageResource('Failed to add event', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function showEvent(int $id)
    {
        try {
            $event = $this->eventService->showEvent($id);
            return new EventResource($event);

        } catch (\Exception $e) {
            return new MessageResource('Failed to show event', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updateEvent(int $id, StoreEventRequest $request)
    {
        try {
            $event = $this->eventService->updateEvent($id, $request->validated());
            return new MessageResource('Event updated successfully', new EventResource($event));

        } catch (\Exception $e) {
            return new MessageResource('Failed to update event', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function deleteEvent(int $id)
    {
        try {
            $deleteEvent = $this->eventService->deleteEvent($id);
            return new MessageResource('Event deleted succussfully', $deleteEvent);

        } catch (\Exception $e) {
            return new MessageResource('Failed to delete event', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
