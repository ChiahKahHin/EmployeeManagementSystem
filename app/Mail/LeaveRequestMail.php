<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    private LeaveRequest $leaveRequest;
    private $reason;
    private $changeManager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(LeaveRequest $leaveRequest, $reason = null, $changeManager = false)
    {
        $this->leaveRequest = $leaveRequest;
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
            $subject = "Leave Approval Manager Delegate";
        }
        else{
            switch ($this->leaveRequest->leaveStatus) {
                case 0:
                    $subject = "Leave Request Waiting Approval";
                    break;
                case 1:
                    $subject = "Leave Request Rejected";
                    break;
                case 2:
                    $subject = "Leave Request Approved";
                    break;
                case 3:
                    $subject = "Leave Request Cancelled";
                    break;
            }
        }
        return $this->subject($subject)
                    ->markdown('email.leaveRequest', ['leaveRequest' => $this->leaveRequest, 'reason' => $this->reason, 'changeManager' => $this->changeManager]);
    }
}
