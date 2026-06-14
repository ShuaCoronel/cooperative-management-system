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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('loan_product_id')->constrained('loan_products');
            $table->text('purpose')->nullable();
            
            // Product Snapshots: We copy these values from loan_products at the exact moment of approval
            // simple explanation if theres a changes on rate, previous loan wont be affected based on the aggreement
            $table->decimal('principal_amount', 12,2); // total amount withou interest of loan
            $table->decimal('interest_rate', 5, 2);
            $table->enum('interest_method', ['flat', 'diminishing']);
            $table->enum('rate_period', ['monthly', 'annual']);

            $table->integer('term_months');
            $table->date('release_date');
            $table->date('due_date');
            $table->enum('status',['active', 'fully_paid','defaulted','restructured'])->default('active');

            $table->foreignId('created_by')->constrained('users'); //audit trail

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
