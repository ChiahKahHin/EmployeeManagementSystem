<?php

namespace App\Console\Commands;

use App\Mail\DelegationMail;
use App\Models\Delegation;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DelegationChecking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delegation:checking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will check the all the added delegation and update the status';

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
        $delegations = Delegation::whereIn('status', [0,1])->get();
        foreach ($delegations as $delegation) {
            if($delegation->status == 0 && $delegation->startDate == date('Y-m-d')){
                $delegation->status = 1;
                $users = User::where('reportingManager', $delegation->managerID)->get();
                foreach ($users as $user) {
                    $user->delegateManager = $delegation->delegateManagerID;
                    $user->save();
                }
                $delegation->save();

                Mail::to($delegation->getDelegateManager->email)->send(new DelegationMail($delegation));
                echo "New approval delegation is ongoing";
            }

            if($delegation->status == 1 && $delegation->endDate < date('Y-m-d')){
                $delegation->status = 2;
                $users = User::where('reportingManager', $delegation->managerID)->get();
                foreach ($users as $user) {
                    $user->delegateManager = null;
                    $user->save();
                }
                $delegation->save();
                echo "Approval delegation is done";
            }
        }
    }
}
