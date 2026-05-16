<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {

            if (!Schema::hasColumn('activity_logs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('activity_logs', 'action')) {
                $table->string('action')->nullable()->after('activity');
            }

            if (!Schema::hasColumn('activity_logs', 'module')) {
                $table->string('module')->nullable()->after('action');
            }

            if (!Schema::hasColumn('activity_logs', 'description')) {
                $table->text('description')->nullable()->after('module');
            }

            if (!Schema::hasColumn('activity_logs', 'old_value')) {
                $table->longText('old_value')->nullable()->after('description');
            }

            if (!Schema::hasColumn('activity_logs', 'new_value')) {
                $table->longText('new_value')->nullable()->after('old_value');
            }

            if (!Schema::hasColumn('activity_logs', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('new_value');
            }

            if (!Schema::hasColumn('activity_logs', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }

        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            //
        });
    }
};