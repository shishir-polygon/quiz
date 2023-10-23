<?php

namespace App\Services\MasterMechanics;


use App\Models\MasterMechanic\MasterMechanic;
use App\Models\MasterMechanic\MasterMechanicCourseLevelQuestion;
use App\Models\MasterMechanic\MasterMechanicCourseQuizAttempt;
use App\Models\MasterMechanic\MasterMechanicQuizHistory;
use App\Models\MasterMechanic\MasterMechanicRewardHistory;
use App\Services\MasterMechanics\Response\AnswerSubmissionResponse;
use Carbon\Carbon;

class RewardHistoryService
{
    public function leaderBoard($user)
    {
        $masterMechanic = MasterMechanic::where('user_id', $user->id)->first();
        $data = MasterMechanicRewardHistory::forCourse($masterMechanic->mm_course_id)->getLeaderBoard();
        return $this->processLeaderBoard($data, $user);
    }

    private function processLeaderBoard($data, $user)
    {
        $leaderBoard = array();
        $myPosition = 0;
        $myPoint = 0;
        foreach ($data as $key => $value) {
            if ($value->masterMechanic->user->id == $user->id) {
                $myPosition = $key + 1;
                $myPoint = $value->total_point;
            }
            if ($value->total_point <= 0)
                continue;
            $leaderBoard[] = [
                'position' => $key + 1,
                'point' => $value->total_point,
                'name' => $value->masterMechanic->user->name,
                'profile_picture' => $value->masterMechanic->user->profile_image,
                'workshop_name' => $value->masterMechanic->user->business_name,
                'division' => $value->masterMechanic->user->division->name,
                'district' => $value->masterMechanic->user->district->name,
            ];
        }

        return ['leaderBoard' => $leaderBoard, 'my_position' => $myPosition, 'my_point' => $myPoint];
    }

    // search master mechanic reward history & return the column by id
    public function findById($id)
    {
        return MasterMechanicRewardHistory::query()->findOrFail($id)->first();
    }

    public function historyList()
    {
        return MasterMechanicRewardHistory::query()
            ->select('id', 'master_mechanic_id', 'mm_course_id', 'mm_course_level_id', 'point', 'reward_amount', 'account_type', 'account_no', 'disbursement_date', 'status', 'created_at');
    }

    public function historyDataExport($payload = null)
    {
        return MasterMechanicRewardHistory::query()
            ->when($payload, function ($query) use ($payload) {
                return $query->where('status', $payload);
            })
            ->select('id', 'master_mechanic_id', 'mm_course_id', 'mm_course_level_id', 'point', 'reward_amount', 'account_type', 'account_no', 'disbursement_date', 'status', 'quiz_time', 'created_at')
            ->with([
                'masterMechanic' => function ($query) {
                    $query->select('id', 'user_id');
                },
                'masterMechanic.user' => function ($query) {
                    $query->select('id', 'name', 'unique_id', 'account_type', 'account_no');
                },
                'masterMechanicCourse' => function ($query) {
                    $query->select('id', 'name');
                },
                'masterMechanicCourseLevel' => function ($query) {
                    $query->select('id', 'level');
                }
            ])
            ->orderBy('id', 'DESC')
            ->get();
    }


    public function rewardHistory($user, $masterMechanicsService = new QuizService())
    {
        $mm = $masterMechanicsService->mechanicsCourseAndState($user->id);

        if (!isset($mm->rewardHistory))
            return [];
        $mmRewardHistory = $mm->rewardHistory;

        return $this->prepareRewardHisorty($mmRewardHistory);
    }

    private function prepareRewardHisorty($mmRewardHistory)
    {
        $list = array();
        foreach ($mmRewardHistory as $reward) {
            $list[] = [
                'level' => $reward->masterMechanicCourseLevel->level,
                'reward_amount' => $reward->reward_amount,
                'status' => $reward->status,
                'created_at' => Carbon::parse($reward->created_at),
            ];
        }
        return $list;
    }
}
