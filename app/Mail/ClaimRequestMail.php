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
    private $reason;
    private $changeManager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ClaimRequest $claimRequest, $reason = null, $changeManager = false)
    {
        $this->claimRequest = $claimRequest;
        $this->reason = $reason;
        $this->changeManager = $changeManager;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = null;
        if($this->changeManager){
            $subject = "Claim Request Approval Manager Delegate";
        }
        else{
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
            }
        }
        return $this->subject($subject)
                    ->markdown('email.claimRequest', ['claimRequest' => $this->claimRequest, 'reason' => $this->reason, 'changeManager' => $this->changeManager]);
    }
}
