<?php

namespace App\Services\MasterMechanics\Response;

class AnswerSubmissionResponse
{
    public array $text;
    public string $rewardAmount = '0';
    public bool $pass = false;
    public function __construct(public readonly string $questions,
                                public readonly string $rightAnswer,
                                public readonly string $wrongAnswer,
                                public readonly string $point,
                                public readonly bool $nextLevel)
    {
    }

    public function setRewardAmount($rewardAmount)
    {
        $this->rewardAmount = $rewardAmount;
        return $this;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function setPass($pass)
    {
        $this->pass = $pass;
        return $this;
    }

    public function toArray()
    {
        return [
            'text' => $this->text,
            'questions' => $this->questions,
            'rightAnswer' => $this->rightAnswer,
            'wrongAnswer' => $this->wrongAnswer,
            'point' => $this->point,
            'rewardAmount' => $this->rewardAmount,
            'pass' => $this->pass,
            'nextLevel' => $this->nextLevel,
        ];
    }
}
