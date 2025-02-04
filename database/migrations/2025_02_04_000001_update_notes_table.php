<?php

use App\Models\Notes\Note;
use App\Models\Users\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(Note::TABLE, function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')
                ->references('id')
                ->on(User::TABLE)
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table(Note::TABLE, function (Blueprint $table) {
            $table->dropColumn('author_id');
        });
    }
};

