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
        if (!Schema::hasColumn('users', 'security_pin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('security_pin')->nullable()->default('123456')->after('total_bonus');
                $table->decimal('bonus_uncashed', 15, 2)->default(400000)->after('security_pin');
            });
        }

        if (!Schema::hasTable('wallet_transactions')) {
            Schema::create('wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->enum('type', ['in', 'out'])->default('in');
                $table->string('category')->default('transfer');
                $table->decimal('amount', 15, 2);
                $table->string('description');
                $table->foreignId('related_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
        if (Schema::hasColumn('users', 'security_pin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['security_pin', 'bonus_uncashed']);
            });
        }
    }
};
