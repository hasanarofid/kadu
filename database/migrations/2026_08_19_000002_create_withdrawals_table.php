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
        if (!Schema::hasColumn('users', 'bank_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('bank_name')->nullable()->after('security_pin');
                $table->string('bank_account_number')->nullable()->after('bank_name');
                $table->string('bank_account_name')->nullable()->after('bank_account_number');
            });
        }

        if (!Schema::hasTable('withdrawals')) {
            Schema::create('withdrawals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('bank_name');
                $table->string('bank_account_number');
                $table->string('bank_account_name');
                $table->decimal('amount', 15, 2);
                $table->decimal('fee', 15, 2)->default(0);
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('admin_notes')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
        if (Schema::hasColumn('users', 'bank_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['bank_name', 'bank_account_number', 'bank_account_name']);
            });
        }
    }
};
