<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskDueDateReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private Task $task;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = null;
        if($this->task->dueDate == date('Y-m-d')){
            $subject = "Task Due Date is Reached";
        }
        else{
            $subject = "Task Overdue";
        }
        return $this->subject($subject)
                    ->markdown('email.taskDueDateReminder', ['task' => $this->task]);
    }
}
