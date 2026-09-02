<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_logs', function (Blueprint $table) {
            $table->id();
            $table->string('report_key');       // e.g. 'mediation-summary' — which report type
            $table->string('title');            // display name, e.g. "Mediation Summary Report"
            $table->string('label')->nullable(); // phase label, e.g. "Mediation 2.0"
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->text('url');                // direct link back to this exact report + params
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_logs');
    }
};
