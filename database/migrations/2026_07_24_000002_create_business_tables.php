<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('eyebrow')->nullable();
            $table->text('summary');
            $table->longText('description');
            $table->json('deliverables')->nullable();
            $table->string('icon')->default('settings');
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('city');
            $table->string('service');
            $table->text('description');
            $table->string('budget')->nullable();
            $table->date('deadline')->nullable();
            $table->string('attachment')->nullable();
            $table->string('status')->default('Nouveau');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('agency');
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('Nouveau');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('contact_messages'); Schema::dropIfExists('quote_requests');
        Schema::dropIfExists('services');
    }
};
