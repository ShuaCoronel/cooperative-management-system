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
        Schema::table('loan_payments', function (Blueprint $table) {
            //

            $table->foreignId('loan_schedule_id')
                ->nullable()
                ->after('loan_id')
                ->constrained('loan_schedules')
                ->restrictOnDelete();

            $table->string('payment_method')->after('penalty_paid');

            $table->string('reference_number')
                    ->nullable()
                    ->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_payments', function (Blueprint $table) {
            //
            $table->dropForeign(['loan_schedule_id']);
            $table->dropColumn([
                'loan_schedule_id', 
                'payment_method', 
                'reference_number'
            ]);
        });
    }
};
