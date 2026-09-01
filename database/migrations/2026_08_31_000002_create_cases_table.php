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
            $table->text('parties_name')->nullable(); // multi-line "X Vs Y & N Ors"

            // Nature of case. case_categories is created by the migration
            // above this one, so the FK can be declared inline here.
            $table->foreignId('case_category_id')
                ->nullable()
                ->constrained('case_categories')
                ->nullOnDelete();

            // Mediator assigned to the case.
            $table->foreignId('mediator_id')
                ->nullable()
                ->constrained('mediators')
                ->nullOnDelete();

            $table->date('first_mediation_date')->nullable();
            $table->date('final_result_date')->nullable();
            $table->date('received_date')->nullable();
            $table->date('assigned_date')->nullable();

            // Some cases have more than one sitting date. Stored as JSON
            // so you can add/show a repeatable date field on the form
            // without a separate table.
            $table->json('sitting_dates')->nullable();

            $table->enum('status', ['pending', 'settled', 'unsettled'])
                ->default('pending');

            // Amount given to the mediator — only entered when status is
            // settled/unsettled, but kept nullable so pending cases skip it.
            $table->decimal('amount', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
