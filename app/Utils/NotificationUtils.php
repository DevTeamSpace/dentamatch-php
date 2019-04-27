<?php

namespace App\Utils;

use App\Enums\AppMessageTarget;
use App\Mail\IncompleteProfile;
use App\Mail\PendingAccept;
use App\Mail\SetAvailability;
use App\Models\AppMessage;
use App\Models\Device;
use App\Models\JobseekerCertificates;
use App\Models\Notification;
use App\Models\RecruiterJobs;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Support\Facades\Mail;

class NotificationUtils
{
    private $adminSenderId;

    public function notifyCertificateExpire(JobseekerCertificates $seekerCertificate, $daysLeft)
    {
        $data = [
            'user_id' => $seekerCertificate->user_id,
            'message' => "$daysLeft days remaining for the expiry of " . $seekerCertificate->certificate->certificate_name,
            'title'   => 'Certification Expiry Reminder'
        ];
        $this->notifySeekerFromAdmin($data);
    }

    public function notifyInviteInactive($user)
    {
        $data = [
            'user_id' => $user->id,
            'message' => 'You have pending jobs to accept/reject',
            'title'   => 'Pending Invites',
            'email'   => $user->email,
            'name'    => $user->first_name,
            'mail'    => PendingAccept::class
        ];
        $this->notifySeekerFromAdmin($data);
    }

    public function notifySetAvailability($user)
    {
        $data = [
            'user_id' => $user->id,
            'message' => 'You had not yet set your availability dates',
            'title'   => 'Set Availability',
            'email'   => $user->email,
            'name'    => $user->first_name,
            'mail'    => SetAvailability::class
        ];
        $this->notifySeekerFromAdmin($data);
    }

    public function notifyProfileIncomplete($user)
    {
        $data = [
            'user_id' => $user->user_id,
            'message' => 'The profile completion is still pending.',
            'title'   => 'Profile Completion Reminder',
            'email'   => $user->email,
            'name'    => $user->first_name,
            'mail'    => IncompleteProfile::class
        ];
        $this->notifySeekerFromAdmin($data);
    }

    /**
     * @param RecruiterJobs[] $inactiveJobs
     */
    public function notifyJobsInactive($inactiveJobs)
    {
        $data = [];
        foreach ($inactiveJobs as $inactiveJob) {
            $data[] = [
                'message' => 'No job has been applied for last 30 days on <a href="/job/details/' . $inactiveJob->id . '"><b>' . $inactiveJob->jobTemplate->jobTitle->jobtitle_name . '</b></a>',
                'user_id' => $inactiveJob->jobTemplate->user_id
            ];
        }
        $this->notifyRecruitersFromAdmin($data);
    }

    /**
     * TODO remove
     * @param $subscriptions
     */
    public function notifySubscriptionExpire($subscriptions)
    {
        $data = array_map(function ($subscription) {
            return [
                'message' => 'You subscription will expire on ' . $subscription['subscription_expiry_date'],
                'user_id' => $subscription['user_id']
            ];
        }, $subscriptions);
        $this->notifyRecruitersFromAdmin($data);
    }

    public function notifyTempJobsExpire($jobs)
    {
        $data = array_map(function ($job) {
            return [
                'message' => "Temporary job for " . '<a href="/job/details/' . $job['id'] . '"><b>' . $job['jobtitle_name'] . "</b></a> is expiring on " . date('l, d M Y', strtotime($job['job_date'])),
                'user_id' => $job['user_id']
            ];
        }, $jobs);
        $this->notifyRecruitersFromAdmin($data);
    }

    public function notifyTempJobsRating($tempJobs)
    {
        $data = array_map(function ($job) {
            return [
                'message' => trans('messages.temp_job_pending_rating_command') . '<a href="/job/details/' . $job['id'] . '"><b>' . $job['jobtitle_name'] . '</b></a>',
                'user_id' => $job['user_id']
            ];
        }, $tempJobs);
        $this->notifyRecruitersFromAdmin($data);
    }

    public function notifyFromAdmin(AppMessage $appMessage)
    {
        $message = $appMessage->message;
        if (in_array($appMessage->message_to, [AppMessageTarget::ALL, AppMessageTarget::RECRUITERS])) {
            $users = User::getAllUserByRole(UserGroup::RECRUITER);
            $data = $users->map(function ($user) use ($message) {
                return [
                    'message' => "Admin Notification | " . $message,
                    'user_id' => $user->id
                ];
            })->all();
            $this->notifyRecruitersFromAdmin($data);
        }

        if (in_array($appMessage->message_to, [AppMessageTarget::ALL, AppMessageTarget::SEEKERS])) {
            $devices = Device::getAllSeekersDeviceToken();

            foreach ($devices as $deviceData) {
                $data = [
                    'user_id' => $deviceData->user_id,
                    'message' => $message,
                    'title'   => 'App Admin Update',
                    'device'  => $deviceData
                ];
                $this->notifySeekerFromAdmin($data);
            }
        }
    }

    private function notifySeekerFromAdmin(array $notification)
    {
        $userId = $notification['user_id'];
        $pushData = [
            'notificationData'   => $notification['message'],
            'notification_title' => $notification['title'],
            'sender_id'          => $this->getAdminId(),
            'receiver_id'        => $userId,
            'type'               => 1,
            'notificationType'   => Notification::OTHER
        ];

        $params['data'] = $pushData;

        $deviceModel = $notification['device'] ?? Device::getDeviceToken($userId);
        if ($deviceModel) {
            PushNotificationService::send($deviceModel, $notification['message'], $params, $userId);
            $data = ['sender_id' => $this->getAdminId(), 'receiver_id' => $userId, 'notification_data' => $notification['message'], 'notification_type' => Notification::OTHER];
            Notification::createNotification($data);
        } else if ($notification['email']) {
            $name = $notification['name'];
            Mail::to($notification['email'])->queue(new $notification['mail']($name));
        }
    }

    private function notifyRecruitersFromAdmin(array $notificationsData)
    {
        $insertData = [];
        foreach ($notificationsData as $notification) {
            $data = [
                'image'   => url('web/images/dentaMatchLogo.png'),
                'message' => $notification['message']
            ];
            $insertData[] = [
                'sender_id'         => $this->getAdminId(),
                'receiver_id'       => $notification['user_id'],
                'notification_data' => json_encode($data),
                'notification_type' => $notification['type'] ?? Notification::OTHER
            ];
        }
        Notification::createNotification($insertData);
    }

    private function getAdminId()
    {
        if (!$this->adminSenderId)
            $this->adminSenderId = User::getAdminUserDetailsForNotification()->id;

        return $this->adminSenderId;
    }
}

