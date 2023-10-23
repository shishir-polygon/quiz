<?php

namespace App\Http\Requests\MasterMechanics;

use App\Models\MasterMechanic\MasterMechanic;
use App\Services\MasterMechanics\QuizService;
use Illuminate\Foundation\Http\FormRequest;

class MasterMechanicAnswerRequest extends FormRequest
{
    public function __construct(private readonly QuizService $masterMechanicsService)
    {
        parent::__construct();
    }
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
            'submissions' => 'required|array',
            'submissions.*.q_id' => 'required|numeric',
            'submissions.*.answer' => 'required|array',
            'time' => 'required',
        ];
    }

}
