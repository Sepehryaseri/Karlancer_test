<?php

namespace App\Listeners;

use App\Events\UserRegisterEvent;
use App\Mail\ActivationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmailListener
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
        Mail::to($event->user['email'])
            ->send(new ActivationMail($event->user['id']));
    }
}
