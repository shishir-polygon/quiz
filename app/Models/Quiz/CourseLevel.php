<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLevel extends Model
{
    use HasFactory;


    protected $fillable = [
        'course_id', 'level', 'video_title', 'video_link', 'pass_mark', 'reward_amount',
    ];

    public function scopeFirstLevelOfCourse($query, $course)
    {
        return $query->where('level', 1)->where('course_id', $course);
    }

    public function scopeNextLevelOfCourse($query, $course, $level)
    {
        $query = $query->where('course_id', $course);
        $query = $query->where('level', $level+1);

        return $query;

    }

    public function scopeQuestionsForLevel($query, $levelId)
    {
        return $query->where('id',$levelId)->first();
    }

    public function scopeVideoForLevel($query, $level)
    {
        if ($level == 0)
            return $query->first();
        return $query->where('level',$level)->first();
    }

    public function scopeGetPreviousLevelId($query, $level)
    {
        $level = $level-1;
        if ($level == 0)
            return 0;
        return $query->where('level',$level)->first()->id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCourseId()
    {
        return $this->course_id;
    }

    public function setCourseId($CourseId)
    {
        $this->course_id = $CourseId;
        return $this;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    public function getVideoTitle(){
        return $this->video_title;
    }

    public function setVideoTitle($video_title){
        $this->video_title=$video_title;
        return $this;
    }

    public function getVideoLink()
    {
        return $this->video_link;
    }

    public function setVideoLink($videoLink)
    {
        $this->video_link = $videoLink;
        return $this;
    }

    public function getPassMark()
    {
        return $this->pass_mark;
    }

    public function setPassMark($passMark)
    {
        $this->pass_mark = $passMark;
        return $this;
    }

    public function getRewardAmount()
    {
        return $this->reward_amount;
    }

    public function setRewardAmount($rewardAmount)
    {
        $this->reward_amount = $rewardAmount;
        return $this;
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function courseLevelQuestions()
    {
        return $this->hasMany(CourseLevelQuestion::class, 'course_level_id');
    }

    public function courseLevelCompleted()
    {
        return $this->hasOne(RewardHistory::class, 'course_level_id', 'id');
    }
}
