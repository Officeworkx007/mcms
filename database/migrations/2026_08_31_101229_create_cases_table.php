<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();

            $table->string('case_no')->nullable();
            $table->text('parties_name')->nullable();

            $table->foreign('case_category_id')->references('id')->on('case_categories')->nullOnDelete();

            $table->foreignId('mediator_id')->nullable()->constrained('mediators')->nullOnDelete();

            $table->date('first_mediation_date')->nullable();
            $table->date('final_result_date')->nullable();
            $table->date('received_date')->nullable();
            $table->date('assigned_date')->nullable();

            $table->json('sitting_dates')->nullable();

            $table->enum('status', ['pending', 'settled', 'unsettled'])
                ->default('pending');

            $table->decimal('amount', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
