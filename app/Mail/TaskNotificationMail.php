<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    private Task $task;
    private $reason;
    private $changeManager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Task $task, $reason = null, $changeManager = false)
    {
        $this->task = $task;
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
            $subject = "Task Approval Manager Delegate";
        }
        else{
            switch ($this->task->status) {
                case '0':
                    $subject = "New Task Added Notification";
                    break;
                
                case '1':
                    $subject = "Task is Waiting for Approval";
                    break;
                
                case '2':
                    $subject = "Task Rejected";
                    break;
    
                case '3':
                    $subject = "Task Approved";
                    break;
        }
           
        }
        return $this->subject($subject)
                    ->markdown('email.taskNotification', ['task' => $this->task, 'reason' => $this->reason, 'changeManager' => $this->changeManager]);
    }

    public function failed($e)
    {
        echo $e;
    }
}
