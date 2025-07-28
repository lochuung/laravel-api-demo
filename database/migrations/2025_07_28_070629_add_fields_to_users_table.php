<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('role')->default('User');
            $table->string('profile_picture')->default('images/default_profile.png');
            $table->boolean('is_active')->default(true);
            $table->boolean('email_verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_number');
            $table->dropColumn('address');
            $table->dropColumn('role');
            $table->dropColumn('profile_picture');
            $table->dropColumn('is_active');
            $table->dropColumn('email_verified');
        });
    }
};
