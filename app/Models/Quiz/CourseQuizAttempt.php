<?php

namespace App\Models\Quiz;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseQuizAttempt extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id', 'course_level_question_id', 'current_level_id',
    ];

    public function scopeForMechanics($query, $mmId)
    {
        return $query->where('user_id', $mmId);
    }
    public function scopeForUser($query, $userId)
    {
        return $query->select('course_quiz_attempts.*')->join('master_mechanics', 'master_mechanics.id', 'course_quiz_attempts.user_id')
            ->where('master_mechanics.user_id', $userId);
    }

    public function scopeForUpdateLevel($query, $mmId, $mmQId, $mmCLevel)
    {
        return $query->where([
            'user_id' => $mmId,
            'course_level_question_id' => $mmQId,
            'current_level_id' => $mmCLevel,
        ]);
    }

    public function getId()
    {
        return $this->id;
    }
    public function getUserId()
    {
        return $this->user_id;
    }
    public function setUserId($Id)
    {
        $this->user_id = $Id;
        return $this;
    }
    public function getCourseLevelQuestionId()
    {
        return $this->course_level_question_id;
    }

    public function setCourseLevelQuestionId($CourseLevelQuestionId)
    {
        $this->course_level_question_id = $CourseLevelQuestionId;
        return $this;
    }
    public function getCurrentLevel()
    {
        return $this->current_level_id;
    }
    public function setCurrentLevel($currentLevel)
    {
        $this->current_level_id = $currentLevel;
        return $this;
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation
    public function courseLevelQuestion()
    {
        return $this->belongsTo(CourseLevelQuestion::class, 'course_level_question_id');
    }

    public function rewardHistoryCurrentLevel()
    {
        return $this->hasOne(RewardHistory::class, 'course_level_id', 'current_level_id');
    }

    public function currentLevel()
    {
        return $this->belongsTo(CourseLevel::class, 'current_level_id', 'id');
    }
}
