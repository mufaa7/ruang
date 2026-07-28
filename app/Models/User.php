<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'bio',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────

    public function deadlines(): HasMany
    {
        return $this->hasMany(Deadline::class);
    }

    public function papers(): HasMany
    {
        return $this->hasMany(Paper::class);
    }

    public function makalah(): HasMany
    {
        return $this->hasMany(Makalah::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function noteFolders(): HasMany
    {
        return $this->hasMany(NoteFolder::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'user_subjects')
            ->withPivot(['role', 'joined_at'])
            ->withTimestamps();
    }

    public function createdSubjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'created_by');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }

    public function collaboratingPapers(): BelongsToMany
    {
        return $this->belongsToMany(Paper::class, 'paper_collaborators')
            ->withPivot(['role', 'status', 'invited_at', 'responded_at'])
            ->withTimestamps();
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function flashcardTargets(): BelongsToMany
    {
        return $this->belongsToMany(FlashcardSet::class, 'flashcard_set_user_targets');
    }

    public function quizTargets(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'quiz_user_targets');
    }

    // ─── Accessors ───────────────────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        $initial = strtoupper(substr($this->name, 0, 1));
        return "https://ui-avatars.com/api/?name={$initial}&background=6366f1&color=fff&size=128";
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
