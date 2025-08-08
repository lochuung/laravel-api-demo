<?php

namespace App\Listeners;

use App\Events\LowStockDetected;
use App\Mail\LowStockMail;
use Mail;

class SendLowStockWarningMail
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
    public function handle(LowStockDetected $event): void
    {
        $recipient = $event->product?->owner?->email;
        if ($recipient) {
            Mail::to($recipient)->send(new LowStockMail($event->product));
        }
    }
}
