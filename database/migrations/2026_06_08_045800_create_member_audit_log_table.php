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
        Schema::create('member_audit_logs', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('changed_by')->constrained('users'); // admin changed the data
            
            // Strictly constrained scope of trackable actions
            $table->enum('table_name', [
                'members', 
                'loans', 
                'savings_transactions',
                'share_capital_transactions',
                'dividend_declarations',
                'dividend_allocations']);

            $table->enum('action', ['created', 'updated', 'deleted', 'restored']);
            
            // JSON columns store data arrays natively (perfect for structural snapshots)
            //json will save the changes as pair, what are the column name changed and the value pair of that column
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_audit_log');
    }
};
