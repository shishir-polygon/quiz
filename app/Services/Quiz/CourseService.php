<?php

namespace App\Services\MasterMechanics;


use App\Models\MasterMechanic\MasterMechanicCourse;
use App\Models\MasterMechanic\MasterMechanicCourseLevel;
use App\Models\MasterMechanic\MasterMechanicCourseLevelQuestion;

class CourseService
{
    public function courseList()
    {
        return MasterMechanicCourse::query()->where('status', 1);
    }

    public function courseListWithLevels($id = null)
    {
        return MasterMechanicCourse::query()
            ->when($id, function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->where('status', 1)
            ->with(['courseLevels'])
            ->get();
    }

    public function findCourseLevelById($id)
    {
        return MasterMechanicCourseLevel::query()->findOrFail($id);
    }

    public function updateCourseLevel($courseLevel, $course_id, $videoTitle, $videoName, $pass_mark, $reward_amount)
    {
        $courseLevel->setMasterMechanicCourseId($course_id)
            ->setVideoTitle($videoTitle)
            ->setVideoLink($videoName)
            ->setPassMark($pass_mark)
            ->setRewardAmount($reward_amount)
            ->save();
    }

    public function courseLevels($payload){
        return MasterMechanicCourseLevel::query()
            ->where('mm_course_id',$payload)
            ->get();
    }

    public function courseQuestions($id)
    {
        return MasterMechanicCourseLevelQuestion::query()
            ->select('id', 'mm_course_level_id', 'question', 'options', 'answer')
            ->whereHas('courseLevel', function ($query) use ($id) {
                $query->where('mm_course_id', $id);
            })
            ->with(['courseLevel' => function ($query) {
                $query->select('id', 'mm_course_id', 'level');
            }]);
    }

    public function deletePreviousCourseQuestion($payload)
    {
        MasterMechanicCourseLevelQuestion::query()
            ->where('mm_course_level_id', $payload)
            ->delete();
    }

}
