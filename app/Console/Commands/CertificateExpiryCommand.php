<?php

namespace App\Console\Commands;

use App\Models\Configs;
use App\Utils\NotificationUtils;
use Illuminate\Console\Command;
use App\Models\JobseekerCertificates;

class CertificateExpiryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:certificateExpiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron to send push notification on certificate expiry before 30 days';

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
        $daysLeft = config(Configs::CERTIFICATE_EXPIRE_DAYS);
        $expiredOnDate = date('Y-m-d', strtotime("+ $daysLeft days"));
        $certificates = JobseekerCertificates::with('certificate')->where('validity_date', $expiredOnDate)->get();

        foreach ($certificates as $seekerCertificate) {
            $this->utils->notifyCertificateExpire($seekerCertificate, $daysLeft);
        }
    }
}
