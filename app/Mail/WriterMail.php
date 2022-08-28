<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WriterMail extends Mailable
{
    use Queueable, SerializesModels;
    public $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details=$details;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $details = $this->details;
        if (file_exists($details['attachment'])) {
            return $this->markdown('emails.toWriter')
            ->subject($details['subject'])
            ->attach(public_path($details['attachment']), [
                'as' => 'assignment.pdf',
                'mime' => 'application/pdf',
            ]);
        }else if($details['message']) {
            $details = $this->details;
            return $this->markdown('emails.toall')
            ->subject($details['subject']);
        } else {
            $details = $this->details;
            return $this->markdown('emails.toWriter')
            ->subject($details['subject']);
        }
    }
}
