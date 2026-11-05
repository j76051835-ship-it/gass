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
            $table->string('phone')->nullable()->after('supervisor');
            $table->date('birth_date')->nullable()->after('phone');
            $table->string('gender')->nullable()->after('birth_date');
            $table->string('marital_status')->nullable()->after('gender');
            $table->text('address')->nullable()->after('marital_status');
            $table->string('nationality')->nullable()->default('Indonesia')->after('address');
            $table->string('department')->nullable()->default('IT')->after('nationality');
            $table->string('employment_status')->nullable()->default('-')->after('department');
            $table->string('profile_photo')->nullable()->after('employment_status');
            $table->string('signature')->nullable()->after('profile_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'birth_date',
                'gender',
                'marital_status',
                'address',
                'nationality',
                'department',
                'employment_status',
                'profile_photo',
                'signature',
            ]);
        });
    }
};
