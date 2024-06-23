<?php

namespace App\Listeners;

use App\Events\UserRegisterEvent;
use Illuminate\Support\Facades\Cache;

class CacheRegistrationPendingListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegisterEvent $event): void
    {
        Cache::set('activation_pending_' . $event->user['id'], 1, 86400);
    }
}
