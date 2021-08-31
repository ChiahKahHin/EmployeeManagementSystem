<?php

namespace App\Mail;

use App\Models\Memo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MemoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private Memo $memo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Memo $memo)
    {
        $this->memo = $memo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("New Memo")
                    ->markdown('email.memo', ['memo' => $this->memo]);
    }

    public function failed($e)
    {
        echo $e;
    }
}
