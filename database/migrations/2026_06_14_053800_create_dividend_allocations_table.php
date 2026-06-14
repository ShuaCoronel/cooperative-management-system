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
        Schema::create('dividend_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dividend_declaration_id')->constrained('dividend_declarations')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();

            $table->decimal('time_weighted_share_capital', 16,4); // calculated weighted score, keep behind the scene in dashboard
            $table->decimal('pool_percentage', 5,4); // shows the percentage of total share earnings
            $table->decimal('allocated_amount',12,4); // final payout they actually receive


            $table->timestamp('created_at')->useCurrent();

            $table->unique(['dividend_declaration_id', 'member_id'], 'div_declaration_member_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dividend_allocations');
    }
};
