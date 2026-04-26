<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('resource_id')->constrained()->onDelete('cascade');
            $table->foreignId('borrow_request_id')->nullable()->constrained()->onDelete('cascade');

            $table->string('issue_type'); // lost / damaged / unreturned
            $table->text('description')->nullable();

            $table->string('evidence_path')->nullable();

            $table->string('status')->default('pending'); // pending / approved / rejected

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_reports');
    }
};
