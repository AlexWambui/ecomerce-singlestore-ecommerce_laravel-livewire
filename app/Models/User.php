<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\UserRoleScopes;
use App\Enums\UserRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, UserRoleScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'secondary_phone_number',
        'password',
        'role',
        'status',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'role' => UserRoles::class,
            'status' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Boot model events
     */
    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });

        static::deleting(function (User $user) {
            if ($user->image && Storage::disk('public')->exists('users/images/' . $user->image)) {
                Storage::disk('public')->delete('users/images/' . $user->image);
            }
        });
    }

    public function isActive(): bool
    {
        return $this->status === true;
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRoles::SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [
            UserRoles::SUPER_ADMIN,
            UserRoles::ADMIN,
            UserRoles::OWNER,
        ]);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }

    /**
     * Format a phone number like 2547xxxxxxx -> 07xx xxx xxx
     */
    protected function formatPhoneNumber(?string $number): ?string
    {
        if (empty($number)) {
            return null;
        }

        // Strip non-digits
        $cleaned = preg_replace('/\D/', '', $number);

        // Convert international (2547..., 2541...) to local (07..., 01...)
        if (str_starts_with($cleaned, '2547')) {
            $local = '0' . substr($cleaned, 3);
        } elseif (str_starts_with($cleaned, '2541')) {
            $local = '0' . substr($cleaned, 3);
        } elseif (str_starts_with($cleaned, '07') || str_starts_with($cleaned, '01')) {
            $local = $cleaned; // already local
        } else {
            return $number; // fallback, return as is
        }

        // Format as 07xx xxx xxx
        return preg_replace('/(\d{4})(\d{3})(\d{3})/', '$1 $2 $3', $local);
    }

    /**
     * Accessor for formatted primary phone number
     */
    public function getPrimaryPhoneFormattedAttribute(): ?string
    {
        return $this->formatPhoneNumber($this->phone_number);
    }

    /**
     * Accessor for formatted secondary phone number
     */
    public function getSecondaryPhoneFormattedAttribute(): ?string
    {
        return $this->formatPhoneNumber($this->secondary_phone_number);
    }

    /**
     * Accessor combining both numbers (formatted)
     */
    public function getPhoneNumbersAttribute(): string
    {
        $phones = array_filter([
            $this->primary_phone_formatted,
            $this->secondary_phone_formatted,
        ]);

        return $phones ? implode(' | ', $phones) : '-';
    }
}
