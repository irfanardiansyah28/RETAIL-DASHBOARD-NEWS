<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();

            $table->string('request_type');
            $table->string('module')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->string('title');
            $table->text('description')->nullable();

            $table->longText('payload')->nullable();

            $table->unsignedBigInteger('requested_by')->nullable();
            $table->string('requested_by_name')->nullable();

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('approved_by_name')->nullable();

            $table->string('status')->default('Pending');
            $table->text('decision_note')->nullable();
            $table->timestamp('decided_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_requests');
    }
};