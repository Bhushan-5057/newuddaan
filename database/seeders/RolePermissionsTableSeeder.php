<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolePermissionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('role_permissions')->insert([
            [
                'id' => 1, 'role' => 'user', 'feature' => 'add fund', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 03:51:16', 'updated_at' => '2025-06-03 23:18:29'
            ],
            [
                'id' => 4, 'role' => 'user', 'feature' => 'add fund request', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 03:53:00', 'updated_at' => '2025-06-03 04:07:36'
            ],
            [
                'id' => 6, 'role' => 'user', 'feature' => 'fund request history', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 03:54:07', 'updated_at' => '2025-06-03 04:07:38'
            ],
            [
                'id' => 7, 'role' => 'user', 'feature' => 'fund transfer', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 03:54:51', 'updated_at' => '2025-06-03 04:07:39'
            ],
            [
                'id' => 8, 'role' => 'user', 'feature' => 'fund transfer history', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 03:55:08', 'updated_at' => '2025-06-03 04:07:39'
            ],
            [
                'id' => 9, 'role' => 'user', 'feature' => 'fund convert', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 03:55:41', 'updated_at' => '2025-06-03 04:07:37'
            ],
            [
                'id' => 10, 'role' => 'user', 'feature' => 'fund convert history', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 03:55:52', 'updated_at' => '2025-06-03 04:07:37'
            ],
            [
                'id' => 11, 'role' => 'admin', 'feature' => 'user', 'can_view' => 1,
                'can_create' => 1, 'can_edit' => 1, 'can_delete' => 1,
                'created_at' => '2025-06-03 04:26:12', 'updated_at' => '2025-06-03 04:26:31'
            ],
            [
                'id' => 12, 'role' => 'admin', 'feature' => 'fund', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 04:27:27', 'updated_at' => '2025-06-03 04:27:27'
            ],
            [
                'id' => 13, 'role' => 'admin', 'feature' => 'add fund', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 04:27:34', 'updated_at' => '2025-06-03 23:22:09'
            ],
            [
                'id' => 14, 'role' => 'admin', 'feature' => 'Transfer Fund History', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 04:28:06', 'updated_at' => '2025-06-03 04:32:55'
            ],
            [
                'id' => 15, 'role' => 'admin', 'feature' => 'Retrieve Fund', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 04:28:34', 'updated_at' => '2025-06-03 04:32:54'
            ],
            [
                'id' => 16, 'role' => 'admin', 'feature' => 'Retrieve Fund History', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 04:28:46', 'updated_at' => '2025-06-03 04:32:55'
            ],
            [
                'id' => 17, 'role' => 'admin', 'feature' => 'Pending Fund Request', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 04:28:56', 'updated_at' => '2025-06-03 04:32:53'
            ],
            [
                'id' => 18, 'role' => 'admin', 'feature' => 'Approved Fund Request', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 04:29:07', 'updated_at' => '2025-06-03 04:32:09'
            ],
            [
                'id' => 19, 'role' => 'admin', 'feature' => 'Cancelled Fund Request', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 04:29:18', 'updated_at' => '2025-06-03 04:32:09'
            ],
            [
                'id' => 20, 'role' => 'admin', 'feature' => 'Fund Convert History', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-03 05:52:13', 'updated_at' => '2025-06-03 05:52:16'
            ],
            [
                'id' => 21, 'role' => 'admin', 'feature' => 'Pin Details', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-04 06:41:49', 'updated_at' => '2025-06-04 06:42:31'
            ],
            [
                'id' => 22, 'role' => 'admin', 'feature' => 'Plan', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-04 06:42:01', 'updated_at' => '2025-06-04 06:42:34'
            ],
            [
                'id' => 23, 'role' => 'admin', 'feature' => 'Pin Details Add Custom Fields', 'can_view' => 1,
                'can_create' => 1, 'can_edit' => 1, 'can_delete' => 1,
                'created_at' => '2025-06-04 06:44:07', 'updated_at' => '2025-06-04 06:50:33'
            ],
            [
                'id' => 24, 'role' => 'admin', 'feature' => 'Pin Details List', 'can_view' => 1,
                'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0,
                'created_at' => '2025-06-04 06:44:20', 'updated_at' => '2025-06-04 06:54:48'
            ],
            [
                'id' => 25, 'role' => 'admin', 'feature' => 'Plan Add Custom Fields', 'can_view' => 1,
                'can_create' => 1, 'can_edit' => 1, 'can_delete' => 1,
                'created_at' => '2025-06-04 06:45:41', 'updated_at' => '2025-06-04 06:57:14'
            ],
            [
                'id' => 26, 'role' => 'admin', 'feature' => 'Plan List', 'can_view' => 1,
                'can_create' => 1, 'can_edit' => 1, 'can_delete' => 1,
                'created_at' => '2025-06-04 06:45:53', 'updated_at' => '2025-06-04 07:01:04'
            ],
            [
                'id' => 27, 'role' => 'admin', 'feature' => 'Registion Form', 'can_view' => 1,
                'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0,
                'created_at' => '2025-06-06 23:36:44', 'updated_at' => '2025-06-06 23:43:27'
            ],
            [
                'id' => 28, 'role' => 'admin', 'feature' => 'Mail Template', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-06 23:46:28', 'updated_at' => '2025-06-06 23:46:28'
            ],
            [
                'id' => 29, 'role' => 'admin', 'feature' => 'Withdrawal', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-07 00:07:51', 'updated_at' => '2025-06-07 00:07:51'
            ],
            [
                'id' => 30, 'role' => 'admin', 'feature' => 'Pending Withdrawal Requests', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-07 00:08:07', 'updated_at' => '2025-06-07 00:08:07'
            ],
            [
                'id' => 31, 'role' => 'admin', 'feature' => 'Approved Withdrawal Requests', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-07 00:08:30', 'updated_at' => '2025-06-07 00:08:30'
            ],
            [
                'id' => 32, 'role' => 'admin', 'feature' => 'Cancelled Withdrawal Requests', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-07 00:08:47', 'updated_at' => '2025-06-07 00:08:47'
            ],
            [
                'id' => 33, 'role' => 'admin', 'feature' => 'Order History', 'can_view' => 1,
                'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0,
                'created_at' => '2025-06-07 00:56:53', 'updated_at' => '2025-06-07 00:56:53'
            ]
        ]);
    }
}

