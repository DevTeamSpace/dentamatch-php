<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Providers\NotificationServiceProvider;
use App\Models\AppMessage;

class AppMessageCommand extends Command
{
    const IS_COMPLETED = 0;
    const NOTIFICATION_INTERVAL = 15;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:adminNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to send push notification to users by admin';

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
     * @return mixed
     */
    public function handle()
    {
        $appMessageModel = AppMessage::where('cron_message_sent', 0)->get();
        if(!empty($appMessageModel)) {
            foreach($appMessageModel as $appMessage) {
                if($appMessage->messageSent) {
                    NotificationServiceProvider::notificationFromAdmin($appMessage);
                    $appMessage->cronMessageSent=1;
                    $appMessage->save();
                }
            }
        }
        
    }
}
