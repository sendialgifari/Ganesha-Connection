<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webinar_comments', function (Blueprint $table) {
            $table->id();
            $table->integer('webinar_id');
            $table->integer('user_id');
            $table->string('comment');
            $table->integer('ratings')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webinar_comments');
    }
}; 