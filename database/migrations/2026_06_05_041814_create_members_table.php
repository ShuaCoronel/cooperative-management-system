<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('member_id_number')->unique();
            $table->string('full_name');
            $table->date("date_of_birth");
            $table->enum('sex',['male','female']);
            $table->enum('civil_status', ['single','married','widowed','separated'])->default('single');
            $table->string('nationality');
            $table->string('home_address');
            $table->string('mobile_number');
            $table->string('email')->nullable(); // Anchor for Phase 2
            $table->string('valid_id_type');
            $table->string('valid_id_number')->nullable();
            $table->string('tin')->nullable();
            $table->string('occupation');
            $table->date('date_joined');

            $table->enum('membership_status', ['active','inactive','resigned','deceased']);

            //soft deletes for later retrieval or marked as deleted data
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('deletion_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
