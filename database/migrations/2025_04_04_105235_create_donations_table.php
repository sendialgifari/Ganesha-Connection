<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->integer('donation_category_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->string('external_link')->nullable();
            $table->integer('goal_amount');
            $table->integer('collected_amount')->default(0);
            $table->integer('is_public')->default(0);
            $table->integer('ratings')->default(0);
            $table->string('topics')->nullable();
            $table->integer('views_counter')->default(0);
            $table->string('image')->nullable();
            $table->string('image_thumb')->nullable();
            $table->integer('is_active_comment')->default(1);
            $table->string('slug')->unique();
            $table->integer('total_comments')->default(0);
            $table->integer('total_comment_star_1')->default(0);
            $table->integer('total_comment_star_2')->default(0);
            $table->integer('total_comment_star_3')->default(0);
            $table->integer('total_comment_star_4')->default(0);
            $table->integer('total_comment_star_5')->default(0);
            $table->integer('is_selected')->default(0);
            $table->integer('admin_category_id')->nullable();
            $table->integer('is_verified')->default(0);
            $table->integer('admin_promotion_category_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
}; 