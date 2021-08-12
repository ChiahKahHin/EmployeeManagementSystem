<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskApprovedOrRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    private Task $task;
    private $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Task $task, $reason = null)
    {
        $this->task = $task;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = ($this->task->status == 3) ? "Task Approved" : "Task Rejected";

        return $this->subject($subject)
                    ->markdown('email.taskApprovedOrRejected', ['task' => $this->task, 'reason' => $this->reason]);
    }
}
