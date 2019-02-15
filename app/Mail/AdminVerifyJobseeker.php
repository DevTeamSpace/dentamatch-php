<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminVerifyJobseeker extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $email
     */
    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.admin-verify-jobseeker')->subject(trans('messages.verify_seeker'));
    }
}
