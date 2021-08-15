<?php

namespace App\Mail;

use App\Models\ClaimRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClaimRequestMail extends Mailable
{
    use Queueable, SerializesModels;
    
    private ClaimRequest $claimRequest;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ClaimRequest $claimRequest)
    {
        $this->claimRequest = $claimRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = null;
        $subject = "Claim Request Waiting Approval";
        return $this->subject($subject)
                    ->markdown('email.claimRequest', ['claimRequest' => $this->claimRequest]);
    }
}
