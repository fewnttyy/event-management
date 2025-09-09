<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

use App\Models\Booking;
use App\Models\User;
use App\Mail\DailyBookingReport;
use Carbon\Carbon;

class SendDailyBookingReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $admins = User::where('role', 'admin')->get();

        $bookings = Booking::with(['user', 'event'])
                           ->whereDate('created_at', Carbon::today())
                           ->get();

        if ($admins->isEmpty()) {
            return;
        }

        foreach($admins as $admin) {
            Mail::to($admin->email)->send(new DailyBookingReport($bookings));
        }
    }
}
