<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }


    public function basicInfo()
    {
        return $this->hasOne(BasicInfo::class);
    }

    public function physical_attr()
    {
        return $this->hasOne(PhysicalAttribute::class);
    }

    public function lifestyle()
    {
        return $this->hasOne(Lifestyle::class);
    }

    public function familyDetail()
    {
        return $this->hasOne(FamilyInformation::class);
    }

    public function education()
    {
        return $this->hasOne(EducationCareer::class);
    }

    public function location()
    {
        return $this->hasOne(Location::class);
    }

    public function hobbyAndInterest()
    {
        return $this->hasOne(HobbiesAndInterest::class);
    }
}
