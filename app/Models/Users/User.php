<?php

namespace App\Models\Users;

use App\Models\BaseAuthenticatableModel;
use Database\Factories\Users\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property string created_at
 * @property string updated_at
 *
 * @method static UserFactory factory(...$parameters)
 */
class User extends BaseAuthenticatableModel implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\Users\UserFactory> */
    use HasFactory;
    use Notifiable;

    public const TABLE = 'users';
    protected $table = self::TABLE;

    const MORPH_NAME = 'user';

    /** @var list<string> */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /** @var list<string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // TODO
        // @see https://filamentphp.com/docs/3.x/panels/installation#allowing-users-to-access-a-panel
//        return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
        return true;
    }
}
