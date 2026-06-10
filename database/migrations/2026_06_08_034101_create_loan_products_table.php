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
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();

            $table->string('name'); // kind of loan eg. student loan, emergency, etc
            $table->enum('interest_method',['flat','diminishin']); // flat fixed interest, diminishing interest based on the amount
            $table->enum('rate_period', ['monthly', 'annual']);
            
            $table->decimal('default_rate', 5, 2); // 5 mean the total digit, so it would be 3 whole 2 decimal
            $table->integer('max_term_months');
            $table->boolean('is_active')->default(true);



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};
