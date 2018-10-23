<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Mail;

class Inspire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment(PHP_EOL.Inspiring::quote().PHP_EOL);
        $this->testEmails();die;
//        $startPoint = ['lat'=>28.472121,'lng'=>77.0703219,'desc'=>'Sridevi Road, Sector 29, Gurugram, Haryana 122009'];//iffcow chowk
//        $endPoint = ['lat'=>28.200516,'lng'=>76.6097473,'desc'=>'Circular Rd, Nai Basti, Rewari, Haryana 123110'];//nai basti rewari
//        $pickupArr = [
//            //'1'=>['lat'=>28.2813635,'lng'=>76.8550243,'type'=>'pickup1','desc'=>'Service Rd, Haryana 122413','distance'=>0],// bilaspur
//            '2'=>['lat'=>28.2038608,'lng'=>76.7679665,'type'=>'pickup2','desc'=>'NH919, Alamgirpur, Haryana 123106','distance'=>0],// dharuhera
//            '3'=>['lat'=>28.4505573,'lng'=>77.0292189,'type'=>'pickup3','desc'=>'Unnamed Road, Shanti Nagar, Shivaji Nagar, Sector 11, Gurugram, Haryana 122001','distance'=>0],// Rajiv chowk
//            '4'=>['lat'=>28.4585635,'lng'=>77.029903,'type'=>'pickup4','desc'=>'175-18, Jharsa Rd, Civil Lines, Gurugram, Haryana 122001','distance'=>0],// manesar toll
//            '5'=>['lat'=>28.4369717,'lng'=>77.0085613,'type'=>'pickup5','desc'=>'19, Hero Honda Rd, Pace City I, Sector 10A, Gurugram, Haryana 122001','distance'=>0]// hero honda
//            ];
//        $dropArr = [
//            //'1'=>['lat'=>28.3754522,'lng'=>76.8716633,'type'=>'drop1','desc'=>'IMT Manesar, Gurugram, Haryana','distance'=>0],// manesar
//            '2'=>['lat'=>28.1993363,'lng'=>76.6026756,'type'=>'drop2','desc'=>'862/b, Adrash Nagar, Qutubpur, Rewari, Haryana 123401','distance'=>0],//rewari haryana
//            '3'=>['lat'=>28.3581039,'lng'=>76.9267657,'type'=>'drop3','desc'=>'Kasan Rd, Power Supply Colony, Gurugram, Haryana 122051','distance'=>0],// manesar
//            '4'=>['lat'=>28.2137916,'lng'=>76.7945248,'type'=>'drop4','desc'=>'NH919, Dharuhera, Haryana 123106','distance'=>0],// dharuhera
//            '5'=>['lat'=>28.0905047,'lng'=>76.5525192,'type'=>'drop5','desc'=>'link Rd, Mohamadpur, Haryana 123501','distance'=>0]//bawal haryana
//            ];
        //Test case 1
//        $startPoint = ['lat'=>-16.86916,'lng'=>145.739,'desc'=>'Sridevi Road, Sector 29, Gurugram, Haryana 122009'];//iffcow chowk
//        $endPoint = ['lat'=>-16.91242,'lng'=>145.76837,'desc'=>'Circular Rd, Nai Basti, Rewari, Haryana 123110'];//nai basti rewari
//        $pickupArr = [
//            '1'=>['lat'=>-16.87384,'lng'=>145.74017,'type'=>'pickup1','desc'=>'175-18, Jharsa Rd, Civil Lines, Gurugram, Haryana 122001','distance'=>0],// manesar toll
//            '2'=>['lat'=>-16.89903,'lng'=>145.7476,'type'=>'pickup2','desc'=>'19, Hero Honda Rd, Pace City I, Sector 10A, Gurugram, Haryana 122001','distance'=>0]// hero honda
//            ];
//        $dropArr = [
//            '1'=>['lat'=>-16.9031,'lng'=>145.74205,'type'=>'drop1','desc'=>'NH919, Dharuhera, Haryana 123106','distance'=>0],// dharuhera
//            '2'=>['lat'=>-16.87934,'lng'=>145.74535,'type'=>'drop2','desc'=>'link Rd, Mohamadpur, Haryana 123501','distance'=>0]//bawal haryana
//            ];
        
        //Test case 2
        $startPoint = ['lat'=>28.4499,'lng'=>77.09903,'desc'=>'Sridevi Road, Sector 29, Gurugram, Haryana 122009'];//iffcow chowk
        $endPoint = ['lat'=>28.41952,'lng'=>77.10511,'desc'=>'Circular Rd, Nai Basti, Rewari, Haryana 123110'];//nai basti rewari
        $pickupArr = [
            '1'=>['lat'=>28.39407,'lng'=>77.28757,'type'=>'pickup1','desc'=>'175-18, Jharsa Rd, Civil Lines, Gurugram, Haryana 122001','distance'=>0],// manesar toll
            '2'=>['lat'=>28.37528,'lng'=>77.14877,'type'=>'pickup2','desc'=>'19, Hero Honda Rd, Pace City I, Sector 10A, Gurugram, Haryana 122001','distance'=>0]// hero honda
            ];
        $dropArr = [
            '1'=>['lat'=>28.48293,'lng'=>77.09684,'type'=>'drop1','desc'=>'NH919, Dharuhera, Haryana 123106','distance'=>0],// dharuhera
            '2'=>['lat'=>28.45795,'lng'=>77.09666,'type'=>'drop2','desc'=>'link Rd, Mohamadpur, Haryana 123501','distance'=>0]//bawal haryana
            ];
        
        //Test case 3
//        $startPoint = ['lat'=>-16.90899,'lng'=>145.72327,'desc'=>'Sridevi Road, Sector 29, Gurugram, Haryana 122009'];//iffcow chowk
//        $endPoint = ['lat'=>-16.92261,'lng'=>145.77443,'desc'=>'Circular Rd, Nai Basti, Rewari, Haryana 123110'];//nai basti rewari
//        $pickupArr = [
//            '1'=>['lat'=>-16.90863,'lng'=>145.72326,'type'=>'pickup1','desc'=>'175-18, Jharsa Rd, Civil Lines, Gurugram, Haryana 122001','distance'=>0],// manesar toll
//            '2'=>['lat'=>-16.90485,'lng'=>145.71657,'type'=>'pickup2','desc'=>'19, Hero Honda Rd, Pace City I, Sector 10A, Gurugram, Haryana 122001','distance'=>0]// hero honda
//            ];
//        $dropArr = [
//            '1'=>['lat'=>-16.90643,'lng'=>145.74285,'type'=>'drop1','desc'=>'NH919, Dharuhera, Haryana 123106','distance'=>0],// dharuhera
//            '2'=>['lat'=>-16.910524,'lng'=>145.741163,'type'=>'drop2','desc'=>'link Rd, Mohamadpur, Haryana 123501','distance'=>0]//bawal haryana
//            ];
        
        $routeArr=[0=>$startPoint];
        $routePath='via:';
        $this->createRoute($startPoint,$endPoint,$pickupArr,$dropArr,$routeArr,$routePath);
        array_push($routeArr, $endPoint);
        dd($routeArr,$routePath);
    }
    
    public function createRoute($startPoint,$endPoint,$pickupArr,$dropArr,&$routeArr,&$routePath){       
        $lastElement=$routeArr[count($routeArr)-1];
        $distance=null;$keytoAdd=null;
        foreach ($pickupArr as $key=>$pickup){
            $meterDistance=$this->calculateDistance($lastElement['lat'], $lastElement['lng'], $pickup['lat'], $pickup['lng']);
            if($distance==null || $distance>$meterDistance){
                $distance=$meterDistance;
                $keytoAdd=$key;
            }
            //echo $distance.'kk_'.$keytoAdd.'__'.$meterDistance.PHP_EOL;
        }
        
        $pickupArr[$keytoAdd]['distance']=$distance;
        array_push($routeArr, $pickupArr[$keytoAdd]);
        $routePath.=$pickupArr[$keytoAdd]['lat'].','.$pickupArr[$keytoAdd]['lng'].'|';
        unset($pickupArr[$keytoAdd]);
        if(isset($dropArr[$keytoAdd])){
            $pickupArr[$keytoAdd]=$dropArr[$keytoAdd];
            unset($dropArr[$keytoAdd]);
        }
        
        if(count($pickupArr)>0){
            $this->createRoute($startPoint,$endPoint,$pickupArr,$dropArr,$routeArr,$routePath);
        }
    }
    
    public function calculateDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000){
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
          cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
    
    public function testEmails(){
        $name='Jasvinder Singh';
        $email ='jasvinder.singh@appster.in';
        $url = url("/verification-code/klfjdlkfsdkf34234234");
        
        Mail::send('email.pending-accept', ['name' => $name, 'email' => $email], function($message) use($email,$name) {
            $message->to($email, $name)->subject(trans("messages.pending_invite_email"));
        });
        $this->comment(PHP_EOL.'First Sent'.PHP_EOL);
        Mail::send('email.set-availability', ['name' => $name, 'email' => $email], function($message) use($email,$name) {
            $message->to($email, $name)->subject(trans("messages.set_availability_email"));
        });
        $this->comment(PHP_EOL.'Second Sent'.PHP_EOL);
        Mail::send('email.pending-email-verification', ['name' => $name, 'url' => $url, 'email' => $email], function($message) use($email,$name) {
            $message->to($email, $name)->subject(trans("messages.pending_email"));
        });
        $this->comment(PHP_EOL.'Third Sent'.PHP_EOL);
        Mail::send('email.incomplete-profile', ['name' => $name, 'email' => $email], function($message ) use($email,$name) {
            $message->to($email, $name)->subject(trans("messages.incomplete_profile_mail"));
        });
        $this->comment(PHP_EOL.'Fourth Sent'.PHP_EOL);
        Mail::send('email.user-activation', ['name' => $name, 'url' => $url, 'email' => $email], function($message ) use($email,$name) {
            $message->to($email, $name)->subject(trans("messages.confirmation_link"));
        });
        $this->comment(PHP_EOL.'Fifth Sent'.PHP_EOL);
        Mail::send('email.reset-password-token', ['name' => $name, 'url' => url('password/reset', ['token' => 'dajdgadjhagdsjhasdga']), 'email' => $email], function($message) use ($email, $name) {
            $message->to($email, $name)->subject('Reset Password Request ');
        });
        $this->comment(PHP_EOL.'Sixth Sent'.PHP_EOL);
        Mail::send('email.admin-verify-jobseeker', ['name' => $name, 'email' => 'jasvinder@yopmail.com'], function($message ) use($email) {
            $message->to($email, "Dentamatch Admin")->subject(trans("messages.verify_seeker"));
        });
        $this->comment(PHP_EOL.'Seventh Sent'.PHP_EOL);
        Mail::send('email.new-invite', ['name' => $name ], function ($message) use ($email) {
            $message->to($email)->subject(trans("messages.new_job_invite"));
        });
        $this->comment(PHP_EOL.'Eighth Sent'.PHP_EOL);
        Mail::send('auth.emails.user-activation', ['url' => $url], function ($message) use ($email) {
            $message->to($email)->subject(trans("messages.confirmation_link"));
        });
        $this->comment(PHP_EOL.'Nineth Sent'.PHP_EOL);
    }
}