<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\TestNotification;

class NotifyTest extends Command
{
    protected $signature = 'notify:test';
    protected $description = 'Send a test notification to a user';

    public function handle()
    {
        $user = User::first(); // choose a user
        $user->notify(new TestNotification("This is a test notification!"));
        $this->info("Notification sent to user {$user->id}");
    }
}
