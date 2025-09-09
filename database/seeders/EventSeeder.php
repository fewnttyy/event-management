<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            ['title' => 'EN-Connect', 'location' => 'South Korea', 'date' => '2021-02-06', 'quota' => 100, 'description' => 'Fanmeeting'],
            ['title' => 'EN-Connect: Companion', 'location' => 'South Korea', 'date' => '2021-11-09', 'quota' => 100, 'description' => 'Fanmeeting'],
        ];

        foreach ($events as $data) {
            Event::create($data);
        }
    }
}
