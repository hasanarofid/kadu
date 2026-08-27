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
        // 1. Add tokens column to users table if not exists
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'tokens')) {
                $table->integer('tokens')->default(10)->after('email');
            }
        });

        // 2. Create token_packages table
        Schema::create('token_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('tokens');
            $table->decimal('price', 12, 2);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Create token_transactions table (Midtrans payment orders)
        Schema::create('token_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('token_package_id')->nullable()->constrained('token_packages')->onDelete('set null');
            $table->string('order_id')->unique();
            $table->integer('tokens');
            $table->decimal('amount', 12, 2);
            $table->string('payment_status')->default('pending'); // pending, paid, failed, cancelled
            $table->string('payment_method')->default('midtrans');
            $table->string('snap_token')->nullable();
            $table->string('payment_url')->nullable();
            $table->timestamps();
        });

        // 4. Create token_logs table (usage history: generate RPP / purchase / admin topup)
        Schema::create('token_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('rpp_id')->nullable()->constrained('rpps')->onDelete('set null');
            $table->string('type'); // deduct, purchase, admin_topup
            $table->integer('tokens');
            $table->integer('balance_after');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_logs');
        Schema::dropIfExists('token_transactions');
        Schema::dropIfExists('token_packages');
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'tokens')) {
                $table->dropColumn('tokens');
            }
        });
    }
};
