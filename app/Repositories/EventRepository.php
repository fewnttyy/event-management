<?php

namespace App\Repositories;

use App\Models\Event;

class EventRepository implements EventRepositoryInterface
{
    public function listEvents()
    {
        $user = auth()->user();

        if(!$user){
            throw new \Exception('User not logged in');
        }

        $event = Event::all();

        return $event;
    }

    public function addEvents(array $data)
    {
        $user = auth()->user();

        if(!$user){
            throw new \Exception('User not logged in');
        }

        if($user['role'] !== 'admin') {
            throw new \Exception('Only admin can add events');
        }

        $event = Event::create([
            'title' => $data['title'],
            'location' => $data['location'],
            'date' => $data['date'],
            'quota' => $data['quota'],
            'description' => $data['description'],
        ]);

        return $event;
    }

    public function showEvent(int $id)
    {
        $user = auth()->user();

        if(!$user){
            throw new \Exception('User not logged in');
        }

        $event = Event::find($id);

        if(!$event) {
            throw new \Exception('Event not found');
        }

        return $event;
    }

    public function updateEvent(int $id, array $data)
    {
        $user = auth()->user();

        if(!$user){
            throw new \Exception('User not logged in');
        }

        if($user['role'] !== 'admin') {
            throw new \Exception('Only admin can update events');
        }
        
        $event = Event::find($id);

        if(!$event) {
            throw new \Exception('Event not found');
        } 

        $event->update($data);

        return $event;
    }

    public function deleteEvent(int $id)
    {
        $user = auth()->user();

        if(!$user){
            throw new \Exception('User not logged in');
        }

        if($user['role'] !== 'admin') {
            throw new \Exception('Only admin can delete events');
        }

        $event = Event::find($id);

        if(!$event) {
            throw new \Exception('Event not found');
        }

        $event->delete();

        return true;
    }
}