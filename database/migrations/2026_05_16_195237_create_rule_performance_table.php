<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'rule_performances',
            function(Blueprint $table){

                $table->id();

                $table->string(
                    'rule_name'
                );

                $table->integer(
                    'trigger_count'
                )->default(0);

                $table->integer(
                    'high_risk_count'
                )->default(0);

                $table->integer(
                    'medium_risk_count'
                )->default(0);

                $table->integer(
                    'low_risk_count'
                )->default(0);

                $table->timestamps();

            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'rule_performances'
        );
    }
};