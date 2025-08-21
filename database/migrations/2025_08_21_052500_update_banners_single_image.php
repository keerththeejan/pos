<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('banners')) {
            return;
        }
        Schema::table('banners', function (Blueprint $table) {
            if (!Schema::hasColumn('banners', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('banners', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
        });

        // Migrate legacy image1 to image if present
        if (Schema::hasColumn('banners', 'image1')) {
            DB::statement('UPDATE banners SET image = COALESCE(image, image1)');
        }

        Schema::table('banners', function (Blueprint $table) {
            foreach (['image1','image2','image3','image4'] as $col) {
                if (Schema::hasColumn('banners', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('banners')) {
            return;
        }
        Schema::table('banners', function (Blueprint $table) {
            foreach (['image1','image2','image3','image4'] as $col) {
                if (!Schema::hasColumn('banners', $col)) {
                    $table->string($col)->nullable()->after('is_active');
                }
            }
        });

        // Restore image into image1
        if (Schema::hasColumn('banners', 'image')) {
            DB::statement('UPDATE banners SET image1 = COALESCE(image1, image)');
        }

        Schema::table('banners', function (Blueprint $table) {
            if (Schema::hasColumn('banners', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('banners', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
