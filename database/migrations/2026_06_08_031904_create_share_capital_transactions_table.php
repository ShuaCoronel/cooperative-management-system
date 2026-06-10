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
        Schema::create('share_capital_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->decimal('amount',12,2);
            $table->enum('type',['deposit','withdrawal']);
            $table->date('trasanction_date');
            $table->text('remarks')->nullable();

            //track which admin process the transaction?
            $table->foreignId('created_by')->constrained('users');

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('share_capital_transactions');
    }
};
