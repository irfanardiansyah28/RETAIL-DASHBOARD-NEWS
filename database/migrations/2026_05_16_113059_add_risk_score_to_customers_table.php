<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales.customers', function (Blueprint $table) {

            if (!Schema::hasColumn('sales.customers', 'risk_score')) {
                $table
                    ->integer('risk_score')
                    ->default(0)
                    ->after('segment');
            }

            if (!Schema::hasColumn('sales.customers', 'risk_level')) {
                $table
                    ->string('risk_level')
                    ->default('Low')
                    ->after('risk_score');
            }

        });
    }

    public function down(): void
    {
        Schema::table('sales.customers', function (Blueprint $table) {

            if (Schema::hasColumn('sales.customers', 'risk_level')) {
                $table->dropColumn('risk_level');
            }

        });
    }
};