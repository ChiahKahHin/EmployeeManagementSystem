<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssignedMail extends Mailable
{
    use Queueable, SerializesModels;
    
    private User $employee;
    private Task $task;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $employee, Task $task)
    {
        $this->employee = $employee;
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Task Added Notification')
                    ->markdown('email.taskAssigned', ['employee' => $this->employee, 'task' => $this->task]);
    }
}
