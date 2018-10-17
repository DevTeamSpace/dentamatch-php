<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Providers\NotificationServiceProvider;

class AppMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $adminMessageObj;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($adminMessageObj){
        $this->adminMessageObj = $adminMessageObj;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        if($this->adminMessageObj->messageSent) {
            NotificationServiceProvider::notificationFromAdmin($this->adminMessageObj);
            $this->adminMessageObj->cronMessageSent=1;
            $this->adminMessageObj->save();
        }
    }
}
