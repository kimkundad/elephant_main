<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ลบ cache ก่อน
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // สร้าง permissions ตัวอย่าง (ปรับตามระบบจริงได้)
        $permissions = [
            'manage users',
            'manage tours',
            'manage bookings',
            'manage pages',
            'view backend',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // สร้าง roles
        $superAdmin = Role::firstOrCreate(['name' => 'superAdmin', 'guard_name' => 'web']);
        $admin      = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $customer   = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        // กำหนด permission ให้แต่ละ role
        $superAdmin->givePermissionTo(Permission::all());

        $admin->givePermissionTo([
            'manage tours',
            'manage bookings',
            'manage pages',
            'view backend',
        ]);

        $customer->givePermissionTo([
            'view backend', // ถ้าต้องการให้ล็อกอินเข้าหลังบ้านบางส่วนได้
        ]);
    }
}
