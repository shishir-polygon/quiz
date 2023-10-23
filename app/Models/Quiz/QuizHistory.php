<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizHistory extends Model
{
    use HasFactory;

    protected $table = 'quiz_histories';

    protected $fillable = ['user_id', 'question_id', 'history'];

    public function scopeMechanicQuestion($query, $qId, $MMId)
    {
        return $query->where([
            'question_id' => $qId,
            'user_id' => $MMId
        ]);
    }

    public function getId()
    {
        return $this->id;
    }

    // Getter and Setter for user_id
    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($Id)
    {
        $this->user_id = $Id;
        return $this;
    }

    // Getter and Setter for level
    public function getQuestionId()
    {
        return $this->question_id;
    }

    public function setQuestionId($level)
    {
        $this->question_id = $level;
        return $this;
    }

    // Getter and Setter for history
    public function getHistory()
    {
        return $this->history;
    }

    public function setHistory($history)
    {
        $this->history = $history;
        return $this;
    }
}
