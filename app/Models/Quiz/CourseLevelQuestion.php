<?php

namespace App\Models\Quiz;

use App\Services\MasterMechanics\Dto\QuestionAnswerDto;
use App\Services\MasterMechanics\Dto\QuestionOptionsDto;
use App\Services\MasterMechanics\Slabs\QuestionAnswerSlab;
use App\Services\MasterMechanics\Slabs\QuestionsOptionsSlab;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLevelQuestion extends Model
{
    use HasFactory;


    protected $fillable = [
        'course_level_id', 'question', 'options', 'answer',
    ];

    public function scopeForLevel($query, $levelId)
    {
        return $query->where('course_level_id', $levelId);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMasterMechanicCourseLevelId()
    {
        return $this->course_level_id;
    }

    public function setMasterMechanicCourseLevelId($masterMechanicCourseLevelId)
    {
        $this->course_level_id = $masterMechanicCourseLevelId;
        return $this;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion($question)
    {
        $this->question = $question;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function getDecodedAnswer(): QuestionAnswerDto
    {
        $answer = json_decode($this->answer, true);
        return new QuestionAnswerDto(array_map(function ($index, $item){
            return new QuestionAnswerSlab($index, $item);
        }, array_keys($answer), $answer));
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function setAnswer($answer)
    {
        $this->answer = $answer;
        return $this;
    }

    public function courseLevel()
    {
        return $this->belongsTo(CourseLevel::class, 'course_level_id');
    }

    public function course()
    {
        return $this->belongsTo(CourseLevel::class, 'course_level_id');
    }
}
