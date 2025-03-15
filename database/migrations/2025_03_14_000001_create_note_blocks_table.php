<?php

use App\Models\Notes\Block;
use App\Models\Notes\Note;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Block::TABLE, function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('note_id')
                ->references('id')
                ->on(Note::TABLE)
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('type', 20);
            $table->string('lang', 20);
            $table->longText('content');

            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_collapse')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Block::TABLE);
    }
};
