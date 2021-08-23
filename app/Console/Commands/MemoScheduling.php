<?php

namespace App\Console\Commands;

use App\Mail\MemoMail;
use App\Models\Memo;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MemoScheduling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'memo:scheduling';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will check the memo scheduling date and send email reminder';

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
        $memos = Memo::all()->where('memoStatus', 0);
        $user = new User();

        foreach($memos as $memo){
            if($memo->memoScheduled == date("Y-m-d H:i:00")){
                $recipient = explode(',', $memo->memoRecipient);
                if(in_array(0, $recipient)){
                    $emails = $user->getEmployeeEmail();
                }
                else{
                    $emails = $user->getEmployeeEmail($recipient);
                }
                Mail::to($emails)->send(new MemoMail($memo));
                $memo->memoStatus = 1;
                $memo->save();
            }
        }
    }
}
