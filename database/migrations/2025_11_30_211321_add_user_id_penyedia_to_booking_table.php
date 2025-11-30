<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id_penyedia')->nullable();

            $table->foreign('user_id_penyedia')
                ->references('user_id')->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropForeign(['user_id_penyedia']);
            $table->dropColumn('user_id_penyedia');
        });
    }
};
