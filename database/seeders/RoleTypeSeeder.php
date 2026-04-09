<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTypeSeeder extends Seeder
{
    public function run()
    {
        // مسح البيانات القديمة (اختياري)
        DB::table('role_type_users')->truncate();
        
        // إضافة الـ Roles الجديدة
        $roles = [
            ['role_type' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['role_type' => 'Manager', 'created_at' => now(), 'updated_at' => now()],
            ['role_type' => 'HR', 'created_at' => now(), 'updated_at' => now()],
            ['role_type' => 'Employee', 'created_at' => now(), 'updated_at' => now()],
            ['role_type' => 'Accountant', 'created_at' => now(), 'updated_at' => now()],
        ];
        
        DB::table('role_type_users')->insert($roles);
    }
}