<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run migration
     */
    public function up(): void
    {
        Schema::table(
            'activity_logs',
            function (Blueprint $table) {

                if (
                    !Schema::hasColumn(
                        'activity_logs',
                        'user_id'
                    )
                ) {

                    $table
                        ->foreignId('user_id')
                        ->nullable()
                        ->after('id')
                        ->constrained('users')
                        ->nullOnDelete();

                }

                if (
                    !Schema::hasColumn(
                        'activity_logs',
                        'action'
                    )
                ) {

                    $table
                        ->string('action')
                        ->nullable()
                        ->after('user_name');

                }

                if (
                    !Schema::hasColumn(
                        'activity_logs',
                        'module'
                    )
                ) {

                    $table
                        ->string('module')
                        ->nullable()
                        ->after('action');

                }

                if (
                    !Schema::hasColumn(
                        'activity_logs',
                        'description'
                    )
                ) {

                    $table
                        ->text('description')
                        ->nullable()
                        ->after('module');

                }

                if (
                    !Schema::hasColumn(
                        'activity_logs',
                        'old_value'
                    )
                ) {

                    $table
                        ->longText('old_value')
                        ->nullable()
                        ->after('description');

                }

                if (
                    !Schema::hasColumn(
                        'activity_logs',
                        'new_value'
                    )
                ) {

                    $table
                        ->longText('new_value')
                        ->nullable()
                        ->after('old_value');

                }

                if (
                    !Schema::hasColumn(
                        'activity_logs',
                        'ip_address'
                    )
                ) {

                    $table
                        ->string('ip_address')
                        ->nullable()
                        ->after('new_value');

                }

                if (
                    !Schema::hasColumn(
                        'activity_logs',
                        'user_agent'
                    )
                ) {

                    $table
                        ->text('user_agent')
                        ->nullable()
                        ->after('ip_address');

                }

            }
        );
    }

    /**
     * Reverse migration
     */
    public function down(): void
    {
        Schema::table(
            'activity_logs',
            function (Blueprint $table) {

                $columns = [

                    'user_id',
                    'action',
                    'module',
                    'description',
                    'old_value',
                    'new_value',
                    'ip_address',
                    'user_agent'

                ];

                foreach ($columns as $column) {

                    if (
                        Schema::hasColumn(
                            'activity_logs',
                            $column
                        )
                    ) {

                        $table->dropColumn(
                            $column
                        );

                    }

                }

            }
        );
    }
};