<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ride_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // requester
            $table->foreignId('resource_id')->constrained()->onDelete('cascade');

            $table->string('pickup_location')->nullable();
            $table->string('destination')->nullable();

            $table->string('status')->default('pending'); // pending / accepted

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ride_requests');
    }
};
