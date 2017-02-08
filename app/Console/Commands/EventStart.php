<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Repositories\Push\PushNotificationApple;
use Log;

class EventStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:eventStart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to photographer & consumer that event is starting next 10 mins';

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
    public function handle(){
        $now = date('Y-m-d H:i:00');
        $eventObj = Event::where('status','Booked')->where('start_time',date('Y-m-d H:i:00',strtotime($now)+600));
        if($eventObj->count()>0){
            $events = $eventObj->get();
            $pushObject = new PushNotificationApple();
            foreach ($events as $event){
                $pushObject->notifyConsumerEventStartInFewMins($event);
                $pushObject->notifyPhotographerEventStartInFewMins($event);
            }
        }else {
            $this->info('No event for notification');
        }
    }
}
