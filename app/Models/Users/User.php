<?php

namespace App\Models\Users;

use App\Models\BaseAuthenticatableModel;
use Database\Factories\Users\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property string created_at
 * @property string updated_at
 *
 * @method static UserFactory factory(...$parameters)
 */
class User extends BaseAuthenticatableModel
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
}
