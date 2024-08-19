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
            //
            $table->text('address')->nullable();
            $table->integer('phone_number')->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->integer('views_counter')->default(0);
            $table->integer('likes_counter')->default(0);
            $table->integer('city_id')->nullable();
            $table->integer('province_id')->nullable();
            $table->text('cover')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('access_token')->nullable();
            $table->integer('role_id')->default(1);
            $table->string('username')->nullable();
            $table->integer('is_active')->default(1);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
