<?php

namespace App\Console\Commands;

use App\Utils\NotificationUtils;
use Illuminate\Console\Command;
use App\Models\AppMessage;

class AppMessageCommand extends Command
{
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
    protected $description = 'Cron to send push notification to users from admin';

    private $utils;

    /**
     * Create a new command instance.
     *
     * @param NotificationUtils $utils
     */
    public function __construct(NotificationUtils $utils)
    {
        $this->utils = $utils;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appMessages = AppMessage::toBeSent()->get();
        foreach ($appMessages as $appMessage) {
            $this->utils->notifyFromAdmin($appMessage);
            $appMessage->cron_message_sent = 1;
            $appMessage->save();
        }
    }
}
