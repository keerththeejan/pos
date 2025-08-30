<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        $perms = [
            'customer.menu.order_details',
            'customer.menu.invoices',
            'customer.menu.order_templates',
            'customer.menu.speed_order',
            'customer.menu.logout',
        ];
        foreach ($perms as $name) {
            if (! Permission::where('name', $name)->exists()) {
                Permission::create(['name' => $name, 'guard_name' => 'web']);
            }
        }
    }

    public function down(): void
    {
        // Intentionally keep permissions; if needed, uncomment to delete
        // foreach ([
        //     'customer.menu.order_details',
        //     'customer.menu.invoices',
        //     'customer.menu.order_templates',
        //     'customer.menu.speed_order',
        //     'customer.menu.logout',
        // ] as $name) {
        //     Permission::where('name', $name)->delete();
        // }
    }
};
