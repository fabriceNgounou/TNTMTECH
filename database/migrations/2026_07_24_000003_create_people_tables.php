<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->text('summary');
            $table->string('audience');
            $table->text('prerequisites')->nullable();
            $table->string('duration');
            $table->string('format');
            $table->unsignedBigInteger('price')->nullable();
            $table->json('program')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
        Schema::create('training_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained()->cascadeOnDelete();
            $table->string('name'); $table->string('email'); $table->string('phone');
            $table->string('city'); $table->string('company')->nullable();
            $table->text('message')->nullable(); $table->string('status')->default('Nouvelle');
            $table->timestamps();
        });
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title'); $table->string('slug')->unique();
            $table->string('city'); $table->string('contract_type');
            $table->longText('description'); $table->json('missions')->nullable();
            $table->date('deadline')->nullable(); $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name'); $table->string('email'); $table->string('phone');
            $table->string('city'); $table->text('message')->nullable();
            $table->string('cv_path'); $table->string('status')->default('Nouvelle');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('applications'); Schema::dropIfExists('jobs');
        Schema::dropIfExists('training_registrations'); Schema::dropIfExists('trainings');
    }
};
