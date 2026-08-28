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
        Schema::table('a_i_conversations', function (Blueprint $table): void {
            $table->string('session_id', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('a_i_conversations', function (Blueprint $table): void {
            $table->uuid('session_id')->change();
        });
    }
};
