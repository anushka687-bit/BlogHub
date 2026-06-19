<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_image')->nullable()->after('email');
            $table->text('bio')->nullable()->after('profile_image');
            $table->string('phone_number')->nullable()->after('bio');
            $table->integer('age')->nullable()->after('phone_number');
            $table->string('gender')->nullable()->after('age');
            $table->boolean('is_admin')->default(false)->after('gender');
            $table->boolean('is_blocked')->default(false)->after('is_admin');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_image', 'bio', 'phone_number', 'age', 'gender', 'is_admin', 'is_blocked']);
        });
    }
};