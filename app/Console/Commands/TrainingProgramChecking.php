<?php

namespace App\Console\Commands;

use App\Models\TrainingProgram;
use Illuminate\Console\Command;

class TrainingProgramChecking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trainingProgram:checking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will check the training program and update the status';

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
        $trainingPrograms = TrainingProgram::all()->where('status', 0);

        foreach ($trainingPrograms as $trainingProgram) {
            if ($trainingProgram->dateAndTime == date("Y-m-d H:i:00")) {
                $trainingProgram->status = 1;
                $trainingProgram->save();
            }
        }
    }
}
