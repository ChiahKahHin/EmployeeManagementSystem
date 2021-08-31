<?php

namespace App\Mail;

use App\Models\CarriedForwardLeave;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CarriedForwardLeaveMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private CarriedForwardLeave $carriedForwardLeave;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(CarriedForwardLeave $carriedForwardLeave)
    {
        $this->carriedForwardLeave = $carriedForwardLeave;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Carried Forward Leave (".date('Y').")";
        return $this->subject($subject)
                    ->markdown('email.carriedForwardLeave', ['carriedForwardLeave' => $this->carriedForwardLeave]);
    }
}
