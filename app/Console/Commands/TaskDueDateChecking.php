<?php

namespace App\Console\Commands;

use App\Mail\TaskDueDateReminderMail;
use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TaskDueDateChecking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:dueDateChecking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will check the task due date and send email reminder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = Task::all()->where('status', 0)->where('dueDate', '<=', date('Y-m-d'));

        foreach($tasks as $task){
            $email = $task->getEmail($task->personInCharge);
            Mail::to($email)->send(new TaskDueDateReminderMail($task));
        }

        echo "Task due date checking is done";
    }
}
