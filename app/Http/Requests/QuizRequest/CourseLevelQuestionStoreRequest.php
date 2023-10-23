<?php

namespace App\Http\Requests\MasterMechanics;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CourseLevelQuestionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'course_id'              => 'required|exists:mm_courses,id',

            'levels'                 => 'required|array',
            'levels.*.level_id'      => 'required|exists:mm_course_levels,id',
            'levels.*.video_title'   => 'required',
            'levels.*.video_link'    => 'required|mimes:mp4,3gp|max:102400',
            'levels.*.pass_mark'     => 'required|numeric',
            'levels.*.reward_amount' => 'required|numeric',
            'levels.*.csv'           => 'required|mimes:csv,txt',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()->withInput()->withErrors($validator)
        );
    }
}
