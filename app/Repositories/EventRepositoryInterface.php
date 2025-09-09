<?php

namespace App\Repositories;

interface EventRepositoryInterface
{
    public function listEvents();
    
    public function addEvents(array $data);

    public function showEvent(int $id);

    public function updateEvent(int $id, array $data);

    public function deleteEvent(int $id);
} 