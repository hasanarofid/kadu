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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->foreignId('parent_id')->nullable()->after('username')->constrained('users')->nullOnDelete();
            $table->enum('position', ['left', 'right'])->nullable()->after('parent_id');
            $table->integer('left_count')->default(0)->after('position');
            $table->integer('right_count')->default(0)->after('left_count');
            $table->integer('left_points')->default(0)->after('right_count');
            $table->integer('right_points')->default(0)->after('left_points');
            $table->string('package_name')->default('Basic')->after('right_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn([
                'username',
                'parent_id',
                'position',
                'left_count',
                'right_count',
                'left_points',
                'right_points',
                'package_name',
            ]);
        });
    }
};
