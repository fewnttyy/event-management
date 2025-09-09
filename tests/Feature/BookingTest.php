<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use App\Models\Booking;
use App\Mail\BookingConfirmation;
use App\Repositories\BookingRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Exception;

class BookingTest extends TestCase
{
    protected $bookingRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookingRepository = $this->app->make(BookingRepository::class);
    }

    #[Test]
    public function test_user_can_book_ticket()
    {
        $user = User::factory()->create(['email' => 'fentygitlab@gmail.com']);
        $this->actingAs($user);

        $event = Event::create([
            'title' => 'event test',
            'location' => 'event test',
            'date' => '2025-09-08',
            'quota' => '10',
            'description' => 'event test'
        ]);

        $eventId = $event->id;

        $bookingData = [
            'user_id' => $user->id,
            'event_id' => $eventId,
            'date' => '2025-09-08',
            'quantity' => '2',
        ];

        $result = $this->bookingRepository->bookTicket($eventId, $bookingData);
        $this->assertNotNull($result);

        $event->delete();
        $user->delete();
    }

    #[Test]
    public function test_user_unauthenticated_cannot_book_ticket()
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

            $bookingData = [
                'user_id' => '1',
                'event_id' => $eventId,
                'date' => '2025-09-08',
                'quantity' => '2',
            ];
            $this->bookingRepository->bookTicket($eventId, $bookingData);
            $this->fail('Exception was not thrown when user not logged in');
        }catch(Exception $e) {
            $this->assertEquals('User is not logged in', $e->getMessage());
            $event->delete();
        }
    }

    #[Test]
    public function test_user_cannot_book_ticket_when_event_does_not_exist()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        try{
            $eventId = '0';

            $bookingData = [
                'user_id' => '1',
                'event_id' => $eventId,
                'date' => '2025-09-08',
                'quantity' => '2',
            ];

            $this->bookingRepository->bookTicket($eventId, $bookingData);
            $this->fail('Exception was not thrown when event does not exist');
        }catch(Exception $e) {
            $this->assertEquals('Event not found', $e->getMessage());
            $user->delete();
        }
    }

    #[Test]
    public function test_user_cannot_booking_when_quota_is_not_available()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        try{
            $event = Event::create([
                'title' => 'event test',
                'location' => 'event test',
                'date' => '2025-09-08',
                'quota' => '0',
                'description' => 'event test'
            ]);

            $eventId = $event->id;

            $bookingData = [
                'user_id' => $user->id,
                'event_id' => $eventId,
                'date' => '2025-09-08',
                'quantity' => '5',
            ];

            $this->bookingRepository->bookTicket($eventId, $bookingData);
            $this->fail('Exception was not thrown when quota event is less than quantity');
        }catch(Exception $e) {
            $this->assertEquals('This event quota is not available', $e->getMessage());
            $event->delete();
            $user->delete();
        }
    }

    #[Test]
    public function test_user_cannot_booking_when_quota_is_less()
    {
        $user = User::factory()->create();
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

            $bookingData = [
                'user_id' => $user->id,
                'event_id' => $eventId,
                'date' => '2025-09-08',
                'quantity' => '15',
            ];

            $this->bookingRepository->bookTicket($eventId, $bookingData);
            $this->fail('Exception was not thrown when quota event is less than quantity');
        }catch(Exception $e) {
            $this->assertEquals('Booking quantity is more than event quota', $e->getMessage());
            $event->delete();
            $user->delete();
        }
    }

    #[Test]
    public function test_user_can_see_their_list_bookings()
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

        $bookingData = Booking::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'date' => '2025-09-08',
            'quantity' => '15',
        ]);

        $result = $this->bookingRepository->listBookings();
        $this->assertNotNull($result);
        $bookingData->delete();
        $event->delete();
        $user->delete();
    }

    #[Test]
    public function test_user_unauthenticated_cannot_see_their_list_bookings()
    {
        try{
            $this->bookingRepository->listBookings();
            $this->fail('Exception was not thrown when user is not logged in');
        }catch(Exception $e) {
            $this->assertEquals('User is not logged in', $e->getMessage());
        }
    }

    #[Test]
    public function test_user_does_not_have_bookings_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        try{
            $this->bookingRepository->listBookings();
            $this->fail('Exception was not thrown when user does not have bookings data');
        }catch(Exception $e) {
            $user->delete();
            $this->assertEquals('No bookings data yet', $e->getMessage());
        }
    }
}
