<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private LeaveRequest $leaveRequest;
    private $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(LeaveRequest $leaveRequest, $reason = null)
    {
        $this->leaveRequest = $leaveRequest;
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
        
        return $this->subject($subject)
                    ->markdown('email.leaveRequest', ['leaveRequest' => $this->leaveRequest, 'reason' => $this->reason]);
    }

    public function failed($e)
    {
        echo $e;
    }
}
