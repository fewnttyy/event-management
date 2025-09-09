<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

use Exception;

class EventTest extends TestCase
{
    // use RefreshDatabase;
    protected $eventRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventRepository = $this->app->make(EventRepository::class);
    }

    #[Test]
    public function test_user_can_get_list_events()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $result = $this->eventRepository->listEvents();

        $this->assertNotNull($result);
        $user->delete();
    }

    #[Test]
    public function test_user_unauthenticated_cannot_get_list_events()
    {
        try{
            $result = $this->eventRepository->listEvents();
            $this->fail('Exception was not thrown when user is not logged in');
        }catch(Exception $e) {
            $this->assertEquals('User not logged in', $e->getMessage());
        }
    }

    #[Test]
    public function test_admin_can_add_events()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $eventData = [
            'title' => 'event test',
            'location' => 'event test',
            'date' => '2025-09-08',
            'quota' => '10',
            'description' => 'event test',
        ];

        $result = $this->eventRepository->addEvents($eventData);

        $this->assertNotNull($result);

        $event = Event::where('title', $eventData['title'])->first();
        $event->delete();
        $admin->delete();
    }

    #[Test]
    public function test_user_unauthenticated_cannot_add_events()
    {
        try{
            $eventData = [
                'title' => 'event test',
                'location' => 'event test',
                'date' => '2025-09-08',
                'quota' => '10',
                'description' => 'event test',
            ];
            $result = $this->eventRepository->addEvents($eventData);
            $this->fail('Exception was not thrown when user is not logged in');
        }catch(Exception $e){
            $this->assertEquals('User not logged in', $e->getMessage());
        }
    }

    #[Test]
    public function test_only_admin_who_can_add_events()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        try{
            $eventData = [
                'title' => 'event test',
                'location' => 'event test',
                'date' => '2025-09-08',
                'quota' => '10',
                'description' => 'event test',
            ];
            $result = $this->eventRepository->addEvents($eventData);
            $this->fail('Exception was not thrown when user was not an admin');
        }catch(Exception $e) {
            $this->assertEquals('Only admin can add events', $e->getMessage());
            $user->delete();
        }
    }

    #[Test]
    public function test_user_can_show_event()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $event = Event::create([
            'title' => 'event test',
            'location' => 'event test',
            'date' => '2025-09-08',
            'quota' => '10',
            'description' => 'event test'
        ]);

        $eventId = $event->id;

        $result = $this->eventRepository->showEvent($eventId);

        $this->assertNotNull($result);
        $event->delete();
        $user->delete();
    }

    #[Test]
    public function test_user_unauthenticated_cannot_show_event()
    {
        $event = Event::create([
            'title' => 'event test',
            'location' => 'event test',
            'date' => '2025-09-08',
            'quota' => '10',
            'description' => 'event test'
        ]);

        $eventId = $event->id;

        try{
            $this->eventRepository->showEvent($eventId);
            $this->fail('Exception was not thrown when user is not logged in');
        }catch(Exception $e) {
            $this->assertEquals('User not logged in', $e->getMessage());
            $event->delete();
        }
    }

    #[Test]
    public function test_user_cannot_show_event_who_does_not_exists()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $eventId = 0;

        try{
            $this->eventRepository->showEvent($eventId);
            $this->fail('Exception was not thrown when event not found');
        }catch(Exception $e) {
            $this->assertEquals('Event not found', $e->getMessage());
            $user->delete();
        }
    }

    #[Test]
    public function test_admin_can_update_event()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $event = Event::create([
            'title' => 'event test',
            'location' => 'event test',
            'date' => '2025-09-08',
            'quota' => '10',
            'description' => 'event test'
        ]);

        $eventData = [
            'title' => 'event test update',
            'location' => 'event test update',
            'date' => '2025-09-08',
            'quota' => '10',
            'description' => 'event test update',
        ];

        $eventId = $event->id;

        $result = $this->eventRepository->updateEvent($eventId, $eventData);

        $this->assertNotNull($result);
        $event->delete();
        $admin->delete();
    }

    #[Test]
    public function test_user_unauthenticated_cannot_update_event()
    {
        try{
            $event = Event::create([
                'title' => 'event test',
                'location' => 'event test',
                'date' => '2025-09-08',
                'quota' => '10',
                'description' => 'event test'
            ]);

            $eventData = [
                'title' => 'event test update',
                'location' => 'event test update',
                'date' => '2025-09-08',
                'quota' => '10',
                'description' => 'event test update',
            ];

            $eventId = $event->id;

            $this->eventRepository->updateEvent($eventId, $eventData);
            $this->fail('Exception was not thrown when user not logged in');
        }catch(Exception $e) {
            $this->assertEquals('User not logged in', $e->getMessage());
            $event->delete();
        }
    }

    #[Test]
    public function test_only_admin_who_can_update_event()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        try{
            $event = Event::create([
                'title' => 'event test',
                'location' => 'event test',
                'date' => '2025-09-08',
                'quota' => '10',
                'description' => 'event test'
            ]);

            $eventData = [
                'title' => 'event test update',
                'location' => 'event test update',
                'date' => '2025-09-08',
                'quota' => '10',
                'description' => 'event test update',
            ];

            $eventId = $event->id;

            $this->eventRepository->updateEvent($eventId, $eventData);
            $this->fail('Exception was not thrown when user was not an admin');
        }catch(Exception $e) {
            $this->assertEquals('Only admin can update events', $e->getMessage());
            $event->delete();
            $user->delete();
        }
    }

    #[Test]
    public function test_user_cannot_update_event_who_does_not_exists()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        $eventData = [
            'title' => 'event test update',
            'location' => 'event test update',
            'date' => '2025-09-08',
            'quota' => '10',
            'description' => 'event test update',
        ];

        $eventId = 0;

        try{
            $this->eventRepository->updateEvent($eventId, $eventData);
            $this->fail('Exception was not thrown when event not found');
        }catch(Exception $e) {
            $this->assertEquals('Event not found', $e->getMessage());
            $user->delete();
        }
    }

    #[Test]
    public function test_admin_can_delete_event()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $event = Event::create([
            'title' => 'event test',
            'location' => 'event test',
            'date' => '2025-09-08',
            'quota' => '10',
            'description' => 'event test'
        ]);

        $eventId = $event->id;

        $result = $this->eventRepository->deleteEvent($eventId);

        $this->assertTrue($result);
        $admin->delete();
    }

    #[Test]
    public function test_user_unauthenticated_cannot_delete_event()
    {
        try{
            $event = Event::create([
                'title' => 'event test',
                'location' => 'event test',
                'date' => '2025-09-08',
                'quota' => '10',
                'description' => 'event test'
            ]);

            $eventId = $event->id;

            $result = $this->eventRepository->deleteEvent($eventId);
            $this->fail('Exception was not thrown when user not logged in');
        }catch(Exception $e) {
            $this->assertEquals('User not logged in', $e->getMessage());
            $event->delete();
        }
    }

    #[Test]
    public function test_only_admin_who_can_delete_events()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        try{
            $event = Event::create([
                'title' => 'event test',
                'location' => 'event test',
                'date' => '2025-09-08',
                'quota' => '10',
                'description' => 'event test'
            ]);

            $eventId = $event->id;

            $this->eventRepository->deleteEvent($eventId);
            $this->fail('Exception was not thrown when user not logged in');
        }catch(Exception $e) {
            $this->assertEquals('Only admin can delete events', $e->getMessage());
            $event->delete();
            $user->delete();
        }
    }

    #[Test]
    public function test_user_cannot_delete_event_who_does_not_exists()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $eventId = 0;

        try{
            $this->eventRepository->deleteEvent($eventId);
            $this->fail('Exception was not thrown when event not found');
        }catch(Exception $e) {
            $this->assertEquals('Event not found', $e->getMessage());
            $admin->delete();
        }
    }
}