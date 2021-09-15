<?php

namespace App\Mail;

use App\Models\Delegation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DelegationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private Delegation $delegation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Delegation $delegation)
    {
        $this->delegation = $delegation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = null;
        
        switch ($this->delegation->status) {
            case 0:
                $subject = "New Approval Delegation";
                break;

            case 1:
                $subject = "Approval Delegation Ongoing";
                break;

            case 3:
                $subject = "Approval Delegation Cancellation";
                break;

            case 4:
                $subject = "Approval Delegation Cancellation";
                break;
        }

        return $this->subject($subject)
                    ->markdown('email.delegationNotification', ['delegation' => $this->delegation]);
    }
}
