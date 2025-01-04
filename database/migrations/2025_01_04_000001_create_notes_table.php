<?php

use App\Models\Notes\Note;
use App\Models\Tags\Tag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Note::TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('status', 30);
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('text')->nullable();
            $table->unsignedInteger('weight')
                ->default(0);
            $table->timestamps();
        });

        if (config('database.default') === 'pgsql') {
            DB::statement("ALTER TABLE ".Note::TABLE." ADD COLUMN searchable TSVECTOR");
            DB::statement("CREATE INDEX notes_searchable_gin ON ".Note::TABLE." USING GIN(searchable)");
            DB::statement("CREATE TRIGGER notes_searchable_update BEFORE INSERT OR UPDATE ON ".Note::TABLE." FOR EACH ROW EXECUTE PROCEDURE tsvector_update_trigger('searchable', 'pg_catalog.russian', 'title', 'text')");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists(Note::TABLE);
    }
};
