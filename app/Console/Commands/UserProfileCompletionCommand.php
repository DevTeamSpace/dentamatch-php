<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserProfile;
use App\Models\Notification;
use DB;

class UserProfileCompletionCommand extends Command
{
    const IS_COMPLETED = 0;
    const NOTIFICATION_INTERVAL = 15;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:profileCompletion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to send push notification on user profile completion';

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
        $message = "dummy notification data";
        $userModel = UserProfile::select('jobseeker_profiles.user_id')
                        ->join('users', 'users.id', '=', 'jobseeker_profiles.user_id')
                        ->where('is_completed',static::IS_COMPLETED)
                        ->where(DB::raw("DATEDIFF(now(), users.created_at)"),'=', static::NOTIFICATION_INTERVAL)
                        ->get();
        
        if(!empty($userModel)) {
            foreach($userModel as $value) {
                $data = ['receiver_id'=>$value->user_id, 'notification_data'=>$message];
                
                Notification::createNotification($data);
                $this->info($value->user_id);
                
            }
        }
    }
}
