<?php

namespace App\Listeners;

use App\Events\ForgotPasswordEvent;
use App\Mail\ForgotPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendForgotPassswordNotification implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ForgotPasswordEvent $event)
    {
        Mail::to($event->user->email)->send(new ForgotPassword($event->user, $event->token));
    }
}
