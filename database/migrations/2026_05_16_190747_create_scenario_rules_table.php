<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scenario_rules', function (Blueprint $table) {
            $table->id();

            $table->string('rule_name');
            $table->string('module')->default('Customer');

            $table->string('condition_field');
            $table->string('operator');
            $table->string('condition_value');

            $table->string('risk_type');
            $table->string('severity')->default('Medium');
            $table->string('title');
            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scenario_rules');
    }
};