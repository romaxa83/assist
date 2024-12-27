<?php

use App\Models\Tags\Tag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(Tag::TABLE, function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
            $table->integer('weight')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table(Tag::TABLE, function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('weight');
        });
    }
};

