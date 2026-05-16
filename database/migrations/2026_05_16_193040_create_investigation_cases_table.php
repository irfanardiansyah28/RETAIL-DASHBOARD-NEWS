<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investigation_cases', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('risk_flag_id');

            $table->string('case_number')->unique();

            $table->string('title');

            $table->text('description')->nullable();

            $table->string('priority')
                ->default('Medium');

            $table->string('status')
                ->default('Open');

            $table->unsignedBigInteger('assigned_to')
                ->nullable();

            $table->text('investigation_note')
                ->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investigation_cases');
    }
};