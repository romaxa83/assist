<?php

use App\Models\Notes\Link;
use App\Models\Notes\Note;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Link::TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('link', 500);
            $table->string('name')->nullable();

            $table->unsignedBigInteger('note_id')
                ->references('id')
                ->on(Note::TABLE)
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('to_note_id')
                ->nullable()
                ->references('id')
                ->on(Note::TABLE)
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->boolean('active');
            $table->boolean('is_external');
            $table->json('attributes')->nullable();
            $table->timestamp('last_check_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Link::TABLE);
    }
};
