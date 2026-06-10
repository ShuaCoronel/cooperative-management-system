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
        Schema::create('loan_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
            $table->integer('period_number'); //month


            // financial breakdowns per installment (total will add up the principal and interest, used for clear tracking)
            $table->decimal('principal_due', 12, 2);
            $table->decimal('interest_due', 12, 2);
            $table->decimal('total_due', 12, 2); // principal_due + interest_due
            
            $table->enum('status', ['pending', 'paid', 'partial', 'overdue'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_schedules');
    }
};
