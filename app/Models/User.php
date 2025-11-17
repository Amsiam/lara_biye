<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements \Illuminate\Contracts\Auth\MustVerifyEmail, FilamentUser
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
        'is_admin',
        'profile_verified_at',
        'verification_notes',
        'hide_from_search',
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
            'is_admin' => 'boolean',
            'profile_verified_at' => 'datetime',
            'hide_from_search' => 'boolean',
        ];
    }

    /**
     * Check if user can access Filament admin panel
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }

    /**
     * Check if user is verified by admin
     */
    public function isProfileVerified(): bool
    {
        return !is_null($this->profile_verified_at);
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

    public function personal()
    {
        return $this->hasOne(PersonalAttitude::class);
    }

    public function language()
    {
        return $this->hasOne(Language::class);
    }

    public function lifestyle()
    {
        return $this->hasOne(LifeStyle::class);
    }

    public function family()
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

    public function hobby()
    {
        return $this->hasOne(HobbiesAndInterest::class);
    }


    public function partnerExpectation()
    {
        return $this->hasOne(PartnerExpectation::class);
    }

    public function parmanent()
    {
        return $this->hasOne(ResidencyInformation::class);
    }

    public function spiritualSocial()
    {
        return $this->hasOne(SpiritualAndSocialBackground::class);
    }

    public function siblingInfo()
    {
        return $this->hasMany(SiblingsInfo::class);
    }

    // public function astronomicInfo()
    // {
    //     return $this->hasOne(Astr::class);
    // }

    public function connection()
    {
        return $this->hasOne(Connection::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function visitedProfiles()
    {
        return $this->hasMany(VisitedProfile::class, 'user_id');
    }
    public function visitedBy()
    {
        return $this->hasMany(VisitedProfile::class, 'visited_user_id');
    }

    public function isVisited($id): bool
    {
        return $this->visitedProfiles()->where('visited_user_id', $id)->exists();
    }

    public function isConnected($connectionId)
    {
        return $this->connectedUsers()->where('connected_user_id', $connectionId)
            ->where('status', 'ACCEPTED')
            ->exists();
    }

    public function isConnectionPending($connectionId)
    {
        return $this->connectedUsers()->where('connected_user_id', $connectionId)
            ->where('status', 'PENDING')
            ->exists();
    }


    public function connectedUsers()
    {
        return $this->belongsToMany(User::class, 'connected', 'user_id', 'connected_user_id')
            ->withPivot('status');
    }

    public function rConnectedUsers()
    {
        return $this->belongsToMany(User::class, 'connected',  'connected_user_id', 'user_id')
            ->withPivot('status');
    }

    public function sendConnectionRequest(User $user)
    {
        if ($this->isConnected($user->id) || $this->isConnectionPending($user->id)) {
            return false; // Already connected or request is pending
        }
        if ($this->id === $user->id) {
            return false; // Cannot send a connection request to oneself
        }

        //check if that this user has already sent a request to the user
        if ($user->isConnectionPending($this->id)) {

            $this->connectedUsers()->attach($user->id, ['status' => 'ACCEPTED']);
            $user->connectedUsers()->updateExistingPivot($this->id, ['status' => 'ACCEPTED']);
            return true;
        }
        $this->connectedUsers()->attach($user->id, ['status' => 'PENDING']);

        return true;
    }

    public function hasSentConnectionRequest(User $user)
    {
        return $user->connectedUsers()->where('connected_user_id', $this->id)
            ->where('status', 'PENDING')->exists();
    }



    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Calculate field completion percentage for a model
     *
     * @param mixed $model
     * @return float (0.0 to 1.0)
     */
    private function calculateFieldCompletion($model): float
    {
        if (!$model) {
            return 0.0;
        }

        // Get all attributes
        $attributes = $model->getAttributes();

        // Fields to exclude from calculation
        $excludedFields = ['id', 'user_id', 'created_at', 'updated_at', 'is_shown', 'image_privacy', 'is_nid_verified', 'is_student_verified'];

        $totalFields = 0;
        $filledFields = 0;

        foreach ($attributes as $key => $value) {
            // Skip excluded fields
            if (in_array($key, $excludedFields)) {
                continue;
            }

            $totalFields++;

            // Check if field is filled (not null and not empty string)
            if ($value !== null && $value !== '') {
                $filledFields++;
            }
        }

        if ($totalFields === 0) {
            return 0.0;
        }

        return $filledFields / $totalFields;
    }

    /**
     * Calculate profile completion percentage
     *
     * @return float
     */
    public function profileCompletionPercentage(): float
    {
        $sections = [
            'basicInfo' => 15,        // Most important - 15%
            'education' => 10,        // Education and career - 10%
            'physical_attr' => 8,     // Physical attributes - 8%
            'location' => 8,          // Location - 8%
            'family' => 8,            // Family information - 8%
            'partnerExpectation' => 10, // Partner expectations - 10%
            'personal' => 7,          // Personal attitude - 7%
            'lifestyle' => 7,         // Lifestyle - 7%
            'hobby' => 6,             // Hobbies and interests - 6%
            'language' => 6,          // Language - 6%
            'spiritualSocial' => 6,   // Spiritual and social - 6%
            'parmanent' => 6,         // Permanent address - 6%
            'siblingInfo' => 3,       // Sibling info - 3%
        ];

        $completedPercentage = 0;

        foreach ($sections as $relation => $weight) {
            $data = $this->$relation;

            if ($relation === 'siblingInfo') {
                // For hasMany relationship, check if at least one record exists
                if ($data && $data->count() > 0) {
                    $completedPercentage += $weight;
                }
            } else {
                // For hasOne relationships, calculate field completion
                $fieldCompletion = $this->calculateFieldCompletion($data);
                $completedPercentage += ($weight * $fieldCompletion);
            }
        }

        return round($completedPercentage, 2);
    }
}
