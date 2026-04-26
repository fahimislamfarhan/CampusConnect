<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('borrow_requests', function (Blueprint $table) {
            $table->id();

            // user who is requesting
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // resource that is being requested
            $table->foreignId('resource_id')->constrained()->onDelete('cascade');

            // optional message
            $table->text('message')->nullable();

            // request status
            $table->string('status')->default('pending'); // pending, approved, rejected

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_requests');
    }
};
