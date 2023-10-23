<?php

namespace App\Http\Requests\MasterMechanics;

use App\Models\MasterMechanic\MasterMechanic;
use App\Services\MasterMechanics\QuizService;
use Illuminate\Foundation\Http\FormRequest;

class MasterMechanicsRequest extends FormRequest
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
            'course_id' => 'required|exists:mm_courses,id',
            'retailer_unique_id' => 'required|exists:users,unique_id',
        ];
    }

    public function setUpData($userId): MasterMechanic
    {
        $masterMechanics = new MasterMechanic();
        $userExists = $this->masterMechanicsService->validateUserExists($userId);
        if($userExists)
            throw new \Exception('User already enrolled as a Master Mechanic.');

        $masterMechanics->setCourseId($this->input('course_id'))->setUserId($userId)->setRetailer($this->input('retailer_unique_id'));
        return $masterMechanics;

    }

}
