<?php

use App\Models\Notes\Link;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(Link::TABLE, function (Blueprint $table) {
            $table->json('reasons')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table(Link::TABLE, function (Blueprint $table) {
            $table->dropColumn('reasons');
        });
    }
};
