<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Quiz\Course;
use App\Models\Quiz\CourseQuizAttempt;
use App\Models\Quiz\QuizHistory;
use App\Models\Quiz\RewardHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_at',
        'last_login_ip',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }

        return $this->profile_photo_path;
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('id', $userId);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function quizAttempt()
    {
        return $this->hasOne(CourseQuizAttempt::class, 'user_id');
    }

    public function rewardHistory()
    {
        return $this->hasMany(RewardHistory::class, 'user_id');
    }

    public function quizHistory()
    {
        return $this->hasMany(QuizHistory::class, 'user_id');
    }
}
