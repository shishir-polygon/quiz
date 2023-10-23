<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardHistory extends Model
{
    use HasFactory;

    const STATUS = [
        'pending', 'disbursed', 'cancel'
    ];

    protected $table = 'reward_histories';

    protected $fillable = [
        'user_id', 'course_id', 'course_level_id',
        'point', 'status', 'reward_amount',
        'account_no', 'account_type', 'disbursement_date', 'quiz_time'
    ];

    public function scopeGetLeaderBoard($query)
    {
        return $query->select('user_id', \DB::raw('SUM(point) as total_point'), \DB::raw('SUM(quiz_time) as total_quiz_time'))
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total_point')
            ->orderBy('total_quiz_time')
            ->get();
    }

    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    public function scopeMechanicReward($query, $userId, $cId, $lId)
    {
        return $query->where([
            'user_id' => $userId,
            'course_id' => $cId,
            'course_level_id' => $lId
        ]);
    }

    public function scopeGetRewardHistory()
    {

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

    public function getCourseId()
    {
        return $this->course_id;
    }

    public function setCourseId($CourseId)
    {
        $this->course_id = $CourseId;
        return $this;
    }

    public function getCourseLevels()
    {
        return $this->course_level_id;
    }

    public function setCourseLevels($CourseLevels)
    {
        $this->course_level_id = $CourseLevels;
        return $this;
    }

    public function getPoint()
    {
        return (int)$this->point;
    }

    public function setPoint($point)
    {
        $this->point = $point;
        return $this;
    }

    public function getTime()
    {
        return $this->quiz_time;
    }

    public function setTime($time)
    {
        $this->quiz_time = $time;
        return $this;
    }

    public function getRewardAmount()
    {
        return $this->reward_amount;
    }

    public function setRewardAmount($rewardPoint)
    {
        $this->reward_amount = $rewardPoint;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getAccountNo()
    {
        return $this->account_no;
    }

    public function setAccountNo($accountNo)
    {
        $this->account_no = $accountNo;
        return $this;
    }

    public function getAccountType()
    {
        return $this->account_type;
    }

    public function setAccountType($accountType)
    {
        $this->account_type = $accountType;
        return $this;
    }

    public function setDisbursement_date($val)
    {
        $this->disbursement_date = $val;
        return $this;
    }

    public function getDisbursement_date()
    {
        return $this->disbursement_date;
    }



    // Relation
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function courseLevel()
    {
        return $this->belongsTo(CourseLevel::class, 'course_level_id');
    }
}
