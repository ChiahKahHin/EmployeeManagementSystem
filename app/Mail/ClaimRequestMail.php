<?php

namespace App\Mail;

use App\Models\ClaimRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClaimRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    private ClaimRequest $claimRequest;
    private $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ClaimRequest $claimRequest, $reason = null)
    {
        $this->claimRequest = $claimRequest;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = null;
        switch($this->claimRequest->claimStatus){
            case 0:
                $subject = "Claim Request Waiting Approval";
                break;
            case 1:
                $subject = "Claim Request Rejected";
                break;
            case 2:
                $subject = "Claim Request Approved";
                break;
            case 3:
                $subject = "Claim Request Cancelled";
                break;
        }

        return $this->subject($subject)
                    ->markdown('email.claimRequest', ['claimRequest' => $this->claimRequest, 'reason' => $this->reason]);
    }

    public function failed($e)
    {
        echo $e;
    }
}
