<?php

namespace App\Providers;

use App\Models\Notes\Note;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        $this->registerMorphMap();
    }

    protected function registerMorphMap(): void
    {
        Relation::morphMap(self::morphs());
    }

    public static function morphs(): array
    {
        return [
            User::MORPH_NAME => User::class,
            Note::MORPH_NAME => Note::class,
        ];
    }
}
