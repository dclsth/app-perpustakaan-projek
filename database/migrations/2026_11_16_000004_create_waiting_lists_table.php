<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waiting_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('menunggu');
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
            $table->unique(['member_id', 'book_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waiting_lists');
    }
};
