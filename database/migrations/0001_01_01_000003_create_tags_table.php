<?php

use App\Models\Tags\Tag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Tag::TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('color', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Tag::TABLE);
    }
};
