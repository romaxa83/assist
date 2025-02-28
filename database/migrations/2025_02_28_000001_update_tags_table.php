<?php

use App\Models\Notes\Note;
use App\Models\Tags\Tag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(Tag::TABLE, function (Blueprint $table) {
            $table->dropColumn('weight');

            $table->unsignedInteger('public_attached')->default(0);
            $table->unsignedInteger('private_attached')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table(Tag::TABLE, function (Blueprint $table) {
            $table->integer('weight')->default(0);

            $table->dropColumn('public_attached');
            $table->dropColumn('private_attached');
        });
    }
};

