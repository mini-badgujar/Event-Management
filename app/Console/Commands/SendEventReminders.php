<?php

namespace App\Console\Commands;

use App\Notifications\EventReminderNotification;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notification to all event attendees that the event will start soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $event = \App\Models\Event::with('attendees.user')
            ->whereBetween('start_time', [now(), now()->addDay()])
            ->get();
        $eventCount = $event->count();
        $eventLable = Str::plural('event', $eventCount);

        $event->each(
            fn ($event) => $event->attendees->each(
                fn($attendee) => $attendee->user->notify(
                    new EventReminderNotification(
                        $event
                    )
                )
            )
        );


        $this->info("found {$eventCount} {$eventLable} .");
    }
}
