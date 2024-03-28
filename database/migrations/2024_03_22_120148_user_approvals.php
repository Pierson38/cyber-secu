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
            $table->dateTime('approval_at')->nullable();
            $table->unsignedBigInteger('approval_by')->nullable();
            $table->foreign('approval_by')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('approval_at');
            $table->dropColumn('approval_by');

            $table->dropForeign('approval_user_id');
        });
    }
};
