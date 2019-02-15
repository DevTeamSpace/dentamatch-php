<?php

use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::query()->count())
            return;

        $uniqueCode = uniqid();
        $user =  [
            'email' => env('ADMIN_EMAIL'),
            'password' => bcrypt('password'),
            'verification_code' => $uniqueCode,
            'is_verified' => 1
        ];
        $userDetails = User::query()->create($user);
        $userGroupModel = new UserGroup();
        $userGroupModel->group_id = UserGroup::ADMIN;
        $userGroupModel->user_id = $userDetails->id;
        $userGroupModel->save();
    }
}
