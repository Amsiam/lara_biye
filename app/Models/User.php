<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class User extends Authenticatable implements \Illuminate\Contracts\Auth\MustVerifyEmail, FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = strtoupper(Str::random(10));
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'is_admin',
        'profile_verified_at',
        'verification_notes',
        'hide_from_search',
        'referral_code',
        'referrer_id',
        'profile_completion',
        'last_reminded_at',
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
            'last_reminded_at' => 'datetime',
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
        return $this->profile_verified_at !== null;
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

    public function isConnected(int $connectionId): bool
    {
        return $this->connectedUsers()->where('connected_user_id', $connectionId)
            ->where('status', 'ACCEPTED')
            ->exists();
    }

    public function isConnectionPending(int $connectionId): bool
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
        return $this->belongsToMany(User::class, 'connected', 'connected_user_id', 'user_id')
            ->withPivot('status');
    }

    public function connectionHistory()
    {
        return $this->hasMany(ConnectionHistory::class);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referrer_id');
    }

    public function sendConnectionRequest(User $user)
    {
        if ($this->isConnected($user->id)) {
            return false; // Already connected
        }
        if ($this->id === $user->id) {
            return false; // Cannot send a connection request to oneself
        }

        // Check balance
        $currentConnection = $this->connection()->first();
        // Use getAttribute because 'connection' column conflicts with Model::$connection property
        $balance = $currentConnection ? (int) $currentConnection->getAttribute('connection') : 0;

        if ($balance < 1) {
            throw new \Exception('Insufficient connections balance.');
        }

        $mailCallback = null;

        $result = DB::transaction(function () use ($user, &$mailCallback) {
            if ($user->connectedUsers()->where('connected_user_id', $this->id)->where('status', 'PENDING')->exists()) {
                $this->connectedUsers()->attach($user->id, ['status' => 'ACCEPTED']);
                $user->connectedUsers()->updateExistingPivot($this->id, ['status' => 'ACCEPTED']);
                $this->connection()->decrement('connection', 1);
                $this->connectionHistory()->create([
                    'amount' => -1,
                    'type' => 'connection_request_accepted',
                    'description' => 'Accepted connection request from ' . $user->name,
                ]);
                $mailCallback = fn() => Mail::to($user->email)->send(new \App\Mail\ConnectionAcceptedMail($this, $user));
                return true;
            }

            if ($this->hasSentConnectionRequest($user)) {
                return true;
            }

            $this->connectedUsers()->attach($user->id, ['status' => 'PENDING']);
            $this->connection()->decrement('connection', 1);
            $this->connectionHistory()->create([
                'amount' => -1,
                'type' => 'connection_request_sent',
                'description' => 'Sent connection request to ' . $user->name,
            ]);
            $mailCallback = fn() => Mail::to($user->email)->send(new \App\Mail\ConnectionRequestMail($this, $user));
            return true;
        });

        if ($mailCallback) {
            try {
                $mailCallback();
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send connection email: ' . $e->getMessage());
            }
        }

        return $result;
    }

    public function hasSentConnectionRequest(User $user): bool
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

        $excludedFields = array_flip(['id', 'user_id', 'created_at', 'updated_at', 'is_shown', 'image_privacy', 'is_nid_verified', 'is_student_verified']);

        $totalFields = 0;
        $filledFields = 0;

        foreach ($attributes as $key => $value) {
            if (isset($excludedFields[$key])) {
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

    public function updateProfileCompletion(): float
    {
        $percentage = $this->profileCompletionPercentage();

        if ($this->profile_completion != $percentage) {
            $this->profile_completion = $percentage;
            $this->saveQuietly(); // Avoid triggering events if possible
        }

        return $percentage;
    }
}
