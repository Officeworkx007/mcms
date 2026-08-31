<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mediators', function (Blueprint $table) {
            $table->id();
            $table->string('advocate_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('email')->nullable();
            $table->string('enrollment_no')->nullable();
            $table->string('experience')->nullable(); // e.g. "8 years" — kept as string for flexibility
            $table->boolean('is_active')->default(true); // used to archive/hide from dropdowns later, not nullable
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mediators');
    }
};
