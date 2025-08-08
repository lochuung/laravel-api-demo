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
            $table->dropUnique('users_email_unique');
            $table->softDeletes();
            $table->dateTime('deleted_at_marker')
                ->storedAs("IF(`deleted_at` IS NULL, '1000-01-01 00:00:00', `deleted_at`)");
            $table->unique(['email', 'deleted_at_marker'], 'uniq_email_not_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('uniq_email_not_deleted');
            $table->dropColumn('deleted_at_marker');
            $table->dropSoftDeletes();
            $table->unique('email', 'users_email_unique');
        });
    }
};
