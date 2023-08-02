<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'profile_password_edit',
            ],
            [
                'id'    => 18,
                'title' => 'scooter_management_access',
            ],
            [
                'id'    => 19,
                'title' => 'scooter_create',
            ],
            [
                'id'    => 20,
                'title' => 'scooter_edit',
            ],
            [
                'id'    => 21,
                'title' => 'scooter_show',
            ],
            [
                'id'    => 22,
                'title' => 'scooter_delete',
            ],
            [
                'id'    => 23,
                'title' => 'scooter_access',
            ],
            [
                'id'    => 24,
                'title' => 'scooter_status_create',
            ],
            [
                'id'    => 25,
                'title' => 'scooter_status_edit',
            ],
            [
                'id'    => 26,
                'title' => 'scooter_status_show',
            ],
            [
                'id'    => 27,
                'title' => 'scooter_status_delete',
            ],
            [
                'id'    => 28,
                'title' => 'scooter_status_access',
            ],
        ];

        Permission::insert($permissions);
    }
}
