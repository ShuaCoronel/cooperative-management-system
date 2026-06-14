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
        Schema::create('dividend_declarations', function (Blueprint $table) {
            $table->id();
            $table->date('period_start'); // start date for weighted time calculation of share earnings
            $table->date('period_end'); // end period for time calculation
            $table->decimal('total_amount', 12,2); // total pool earning of the shared total capital
            $table->date('declaration_date'); // data finalizing the total earnings to be distributed
            $table->enum('status', ['draft','finalized'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dividend_declarations');
    }
};
