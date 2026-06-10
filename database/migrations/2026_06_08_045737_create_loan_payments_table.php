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
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
            $table->decimal('amount_paid',12,2);

            //hierarchy penaly get paid first, then interest, then principal
            $table->decimal('principal_paid', 12,2)->default(0.00);
            $table->decimal('interest_paid', 12, 2)->default(0.00);
            $table->decimal('penalty_paid', 12, 2)->default(0.00);
            
            $table->date('payment_date');
            $table->text('remarks')->nullable();
            
            // Audit: Track the exact employee who accepted this payment
            $table->foreignId('created_by')->constrained('users');
            
            $table->timestamp('created_at')->useCurrent();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payments');
    }
};
