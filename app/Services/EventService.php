<?php

namespace App\Services;

use App\Repositories\EventRepositoryInterface;
use App\Http\Resources\EventResource;
use App\Models\Event;

class EventService
{
    public function __construct(
        protected EventRepositoryInterface $eventRepository
    ) {
    }

    public function listEvents()
    {
        $listEvents = $this->eventRepository->listEvents();

        return $listEvents;
    }

    public function addEvents(array $data)
    {
        $event = $this->eventRepository->addEvents($data);

        return $event;
    }

    public function showEvent(int $id)
    {
        $event = $this->eventRepository->showEvent($id);

        return $event;
    }

    public function updateEvent(int $id, array $data)
    {
        $updateEvent = $this->eventRepository->updateEvent($id, $data);

        return $updateEvent;
    }

    public function deleteEvent(int $id)
    {
        $deleteEvent = $this->eventRepository->deleteEvent($id);

        return true;
    }
}