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
    private $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(CarriedForwardLeave $carriedForwardLeave, $reason = null)
    {
        $this->carriedForwardLeave = $carriedForwardLeave;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Carried Forward Leave (".date('Y').")";
        $subject = null;

        switch ($this->carriedForwardLeave->status) {
            case 0:
                $subject = "Carried Forward Leave Waiting Approval";
                break;
            case 1:
                $subject = "Carried Forward Leave Rejected";
                break;
            case 2:
                $subject = "Carried Forward Leave Approved";
                break;
        }
        return $this->subject($subject)
                    ->markdown('email.carriedForwardLeave', ['carriedForwardLeave' => $this->carriedForwardLeave, 'reason' => $this->reason]);
    }
}
