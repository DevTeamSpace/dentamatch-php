<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserActivation extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $url;

    protected $viewName;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $url
     * @param string $view
     */
    public function __construct($name, $url, $view = 'email.user-activation')
    {
        $this->name = $name;
        $this->url = $url;
        $this->viewName = $view;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->viewName)->subject(trans('messages.confirmation_link'));
    }
}
