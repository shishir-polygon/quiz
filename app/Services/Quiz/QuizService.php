<?php

namespace App\Services\Quiz;

use App\Models\Quiz\CourseLevel;
use App\Models\Quiz\CourseLevelQuestion;
use App\Models\Quiz\CourseQuizAttempt;
use App\Models\Quiz\QuizHistory;
use App\Models\Quiz\RewardHistory;
use App\Models\User;
use App\Services\MasterMechanics\CourseEnum;
use App\Services\MasterMechanics\Response\AnswerSubmissionResponse;

class QuizService
{
    public function masterMechanicList()
    {
        return User::query()
            ->with([
                'rewardHistory' => function ($query) {
                    $query->selectRaw('master_mechanic_id, SUM(point) as total_point, SUM(reward_amount) as total_reward_amount, SUM(quiz_time) as quiz_time')
                        ->groupBy('master_mechanic_id');
                }
            ]);
//            ->withSum('rewardHistory','point')
//            ->withSum('rewardHistory','reward_amount');
    }

    public function masterMechanicListExport($course_id = null)
    {
        return User::query()
            ->select('id', 'user_id', 'retailer_unique_id', 'course_id', 'created_at')
            ->when($course_id, function ($query) use ($course_id) {
                return $query->whereHas('course', function ($q) use ($course_id) {
                    $q->where('id', $course_id);
                });
            })
            ->with([
                'user' => function($query){
                    $query->select('id', 'name', 'unique_id');
                },
                'course' => function ($query) {
                    $query->select('id', 'name');
                },

                'quizAttempt.currentLevel' =>function($query){
                    $query->select('id', 'level');
                },
                'rewardHistory' => function ($query) {
                    $query->selectRaw('master_mechanic_id, SUM(point) as total_point, SUM(reward_amount) as total_reward_amount, SUM(quiz_time) as quiz_time')
                        ->groupBy('master_mechanic_id');
                }
            ])
            ->get();
    }


    public function validateUserExists($userId)
    {
        $user = User::where('user_id', $userId)->first();

        if ($user) {
            return true;
        }

        return false;
    }

    public function storeAttempt(User $users, $userCourseQuizAttempt = null)
    {
        $userCourseQuizAttempt = $userCourseQuizAttempt ?: new CourseQuizAttempt();
        $userCourseQuizAttempt
            ->setUserId($users->id)
            ->setCurrentLevel(0)
            ->setCourseLevelQuestionId(0)
            ->save();
    }

    public function courseAndState($userId)
    {
        return User::with(['course', 'quizAttempt', 'rewardHistory'])
            ->forUser($userId)
            ->first();
    }

    public function mechanicsCourseStateAndQuizHistory($userId)
    {
        return User::with(['course', 'quizAttempt', 'rewardHistory', 'quizHistory'])
            ->forUser($userId)
            ->first();
    }

    public function processDashboard($userId)
    {
        $quizCourse = $this->courseAndState($userId);
        if ($quizCourse == null)
            return false;
        if ($quizCourse->quizAttempt == null) {
            $this->storeAttempt($quizCourse);
            $quizCourse = $this->courseAndState($userId);
        }
        $currentLevel = $quizCourse->quizAttempt->currentLevel;
        if ($quizCourse->course->name == CourseEnum::BIKE->value)
            $name = array('en' => CourseEnum::BIKE, 'bn' => CourseEnum::BIKE_BN);
        elseif ($quizCourse->course->name == CourseEnum::CAR->value)
            $name = array('en' => CourseEnum::CAR, 'bn' => CourseEnum::CAR_BN);
        elseif ($quizCourse->course->name == CourseEnum::HEAVY_VEHICLE->value)
            $name = array('en' => CourseEnum::HEAVY_VEHICLE, 'bn' => CourseEnum::HEAVY_VEHICLE_BN);

        $totalCourseLevel = $quizCourse->course->courseLevels->count() ?? 0;
        $cLevel = $currentLevel ? (int)$currentLevel->level : 0;

        $date = strtotime("August 15, 2023 12:01 AM");
        $remaining = $date - time();
        $days_remaining = floor($remaining / 86400);
        $hours_remaining = floor(($remaining % 86400) / 3600);
        $min = floor(($remaining % 3600) / 60);
        $startExam = false;
//        if ($remaining <= 0)
//        {
//            $startExam = true;
//        }

        $data = [
            "enrolled_at" => $name,
            "total_point" => $currentLevel ? $quizCourse->rewardHistory->sum('point') : 0,
            "current_state" => ($quizCourse->quizAttempt->course_level_question_id != 0) ? true : false,
            "current_level" => $cLevel,
            'total_level' => $totalCourseLevel,
            'completed_level' => isset($currentLevel->courseLevelCompleted) && $currentLevel->courseLevelCompleted()->where('master_mechanic_id', $quizCourse->id)->first()->reward_amount == 0 ? $cLevel - 1 : $cLevel,
            'quiz_start' => $startExam
        ];
        if (!$startExam)
        {
//            $data['countdown_text'] = [
//                "en" => "Quiz play time will be accessible after $days_remaining days and $hours_remaining:$min. Come here on August 15th",
//                "bn" => "কুইজ খেলতে পারবেন $days_remaining দিন $hours_remaining:$min মিনিট পরে, ফিরে আসুন ১৫ ই আগস্ট",
//            ];
            $data['countdown_text'] = [
                "en" => "Quiz will be coming soon",
                "bn" => "অতি শীঘ্রই কুইজ খেলতে পারবেন",
            ];
        }
        return $data;
    }

    public function point_history($userId)
    {
        return RewardHistory::query()
            ->select('id', 'master_mechanic_id', 'course_level_id', 'point', 'created_at', 'quiz_time')
            ->whereHas(
                'masterMechanic', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with([
                'masterMechanicCourseLevel' => function ($query) {
                    $query->select('id', 'level');
                }
            ])
            ->get();
    }

    public function history($userId)
    {
        $history = RewardHistory::query()
            ->select('id', 'master_mechanic_id', 'course_level_id', 'point', 'created_at', 'quiz_time')
            ->whereHas(
                'masterMechanic', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('point', '>', 0)
            ->with('masterMechanicCourseLevel')
            ->get();

        $data = $this->processQuizHistory($history);
        return $data;
    }

    private function processQuizHistory($history)
    {
        $histories = [];
        foreach ($history as $data) {
            $level = (int)$data->masterMechanicCourseLevel->level;
            $video_title = $data->masterMechanicCourseLevel->video_title;
            $video = $data->masterMechanicCourseLevel->video_link;
            $questions = (int)$data->masterMechanicCourseLevel->courseLevelQuestions->count();
            $reward = $data->masterMechanicCourseLevel->reward_amount;
            $time = (int)$data->quiz_time;
            $point = (int)$data->point;
            $rightAnswer = (int)$data->point;
            $wrongAnswer = $questions - $data->point;

            $histories[] = [
                'level'             => $level,
                'video_title'       => $video_title,
                'video_link'        => $video,
                'total_questions'   => $questions,
                'reward_amount'     => $reward,
                'total_point'       => $point,
                'time'              => $time,
                'right_answer'      => $rightAnswer,
                'wrong_answer'      => $wrongAnswer,
            ];
        }
        return $histories;
    }

    public function submitAnswer($userId, $mechanicAnswerRequest, $courseLevelQuestion = new CourseLevelQuestion())
    {
        $submissions = $mechanicAnswerRequest->submissions;
        $user = $this->courseAndState($userId);

        if (!$user) {
            return ['status' => false, 'msg' => 'Please Enroll as Master Mechanic'];
        }
        if ($user->quizAttempt->course_level_question_id == 0)
            return ['status' => false, 'msg' => 'Your are requested to see the video'];

        $rewardHistory = $this->getRewardHistory($user);

        foreach ($submissions as $submission) {
            $question = $courseLevelQuestion->find($submission['q_id']);
            $answer = $question->getDecodedAnswer();
            $mechanicsAnswer = $submission['answer'];

            $quizHasPermission = $this->storeQuizHistory($user->id, $question, $mechanicsAnswer);

            if ($answer->getKeys() === $mechanicsAnswer && $quizHasPermission) {
                $this->updateRewardHistory($user, $question, $rewardHistory);
            }
        }

        $questionCount = $this->getQuestionCount($user->quizAttempt->currentLevel->id, $courseLevelQuestion);
        $rightAnswer = $rewardHistory->getPoint() ?: 0;
        $wrongAnswer = $questionCount - $rightAnswer;
        $nextLevel = (bool)$this->getNextCourseLevel($user, $user->quizAttempt->currentLevel->level);

        $response = new AnswerSubmissionResponse(
            $questionCount,
            $rightAnswer,
            $wrongAnswer,
            $rightAnswer,
            $nextLevel
        );
        if ($rightAnswer >= $user->quizAttempt->currentLevel->pass_mark) {
            $rewardAmount = $user->quizAttempt->currentLevel->reward_amount;
            $rewardHistory->setTime($mechanicAnswerRequest->time)
                ->setRewardAmount($rewardAmount)
                ->save();

            $response = $response->setText([
                'en' => 'Congratulations! You complete the level Successfully',
                'bn' => 'চমৎকার কাজ',
            ])->setRewardAmount($rewardAmount)->setPass(true);

            // The user need to see the video of next level now
            $user->quizAttempt->setMasterMechanicCourseLevelQuestionId(0)->save();
        } else {

            $response = $response->setText([
                'en' => 'Sorry! You did not pass the Quiz',
                'bn' => 'দুঃখিত!',
            ]);

            $mechanicsLevel = $user->quizAttempt->currentLevel->level;
            $currentLevelId = $user->course->courseLevels()->videoForLevel($mechanicsLevel)->id;
            $questions = $user->course->courseLevels()->questionsForLevel($currentLevelId)->courseLevelQuestions->pluck('id')->toArray();

            QuizHistory::whereIn('question_id', $questions)->delete();
            RewardHistory::mechanicReward($user->id,
                $user->course_id,
                $user->quizAttempt->current_level_id)
                ->first()
                ->setPoint(0)
                ->setTime($mechanicAnswerRequest->time)
                ->save();
        }

        return $response->toArray();
    }

    public function getQuestionCount($levelId, $courseLevelQuestion = new CourseLevelQuestion())
    {
        return $courseLevelQuestion->forLevel($levelId)->count();
    }

    private function updateRewardHistory($user, $question, $reward = null): RewardHistory
    {
        if ($reward != null) {
            $reward->setPoint($reward->getPoint() + 1)->save();

            $attempt = CourseQuizAttempt::forMechanics($user->id)->first();
            $attempt->setCourseLevelQuestionId($question->id)->save();
        } else {
            $reward = new RewardHistory();
            $reward->setId($user->id)
                ->setCourseId($user->course_id)
                ->setCourseLevels($user->quizAttempt->current_level_id)
                ->setPoint(1)
                ->setRewardAmount(0)
                ->save();
        }

        return $reward;
    }

    private function storeQuizHistory($Id, $question, $answer, $history = null)
    {
        $history = $history ?: new QuizHistory();
        $history = $history->mechanicQuestion($question->id, $Id)->first();
        $historyArray = [
            'question' => $question->question,
            'options' => $question->options,
            'correct_answer' => $question->answer,
            'answer' => $answer,
        ];

        if (!$history) {
            $history = new QuizHistory();
            $history->setQuestionId($question->id)
                ->setId($Id)
                ->setHistory(json_encode([$historyArray]))
                ->save();
        } else {
            return false;
        }

        return true;
    }

    public function getRewardHistory($user)
    {
        return RewardHistory::mechanicReward(
            $user->id,
            $user->course_id,
            $user->quizAttempt->current_level_id
        )->first();
    }

    public function getQuizAttempt($mechanicsDetails)
    {
        if (isset($mechanicsDetails->quizAttempt)) {
            return CourseQuizAttempt::forUpdateLevel(
                $mechanicsDetails->id,
                $mechanicsDetails->quizAttempt->course_level_question_id,
                $mechanicsDetails->quizAttempt->current_level_id
            )->first();
        } else {
            return new CourseQuizAttempt();
        }
    }

    public function getCurrentLevel($mechanicsDetails)
    {
        return isset($mechanicsDetails->quizAttempt) && $mechanicsDetails->quizAttempt->current_level_id != 0
            ? $mechanicsDetails->quizAttempt->currentLevel->level
            : 0;
    }

    public function getNextCourseLevel($mechanicsDetails, $level)
    {
        return CourseLevel::nextLevelOfCourse($mechanicsDetails->course_id, $level)->first();
    }

    public function saveQuizAttempt($quizAttempt, $mechanicsDetails, $course)
    {
        $quizAttempt->setId($mechanicsDetails->id)
            ->setCurrentLevel($course->id)
            ->setCourseLevelQuestionId($course->courseLevelQuestions->first()->id)
            ->save();

    }

    public function saveRewardHistory($reward, $mechanicsDetails, $course)
    {
        $reward->setId($mechanicsDetails->id)
            ->setCourseId($course->course_id)
            ->setCourseLevels($course->id)
            ->setPoint(0)
            ->setRewardAmount(0)
            ->save();
    }

    public function videoSeenResponse($userId)
    {
        $mechanicsDetails = $this->courseAndState($userId);

        $questions = $this->getQuestionCount($mechanicsDetails->quizAttempt->currentLevel->id);
        $requiredCorrect = $mechanicsDetails->quizAttempt->currentLevel->pass_mark;
        $reward = $mechanicsDetails->quizAttempt->currentLevel->reward_amount;

        return [
            [
                'label' => [
                    'bn' => 'মোট প্রশ্ন :',
                    'en' => 'Total Question :'
                ],
                'value' => (string)$questions
            ],
            [
                'label' => [
                    'bn' => 'ন্যূনতম সঠিক উত্তর :',
                    'en' => 'Minimum Acceptance :'
                ],
                'value' => $requiredCorrect
            ],
            [
                'label' => [
                    'bn' => 'রিওয়ার্ডের পরিমাণ :',
                    'en' => 'Reward Amount :'
                ],
                'value' => '৳ ' . $reward
            ]
        ];

    }
}
