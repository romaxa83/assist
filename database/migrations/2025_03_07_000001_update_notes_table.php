<?php

use App\Models\Notes\Note;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(Note::TABLE, function (Blueprint $table) {
            $table->dropColumn('links');
            $table->longText('text_html')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table(Note::TABLE, function (Blueprint $table) {
            $table->json('links')->after('anchors')->nullable();
            $table->dropColumn('text_html');
        });
    }
};