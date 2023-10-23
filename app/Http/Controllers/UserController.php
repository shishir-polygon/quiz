<?php

namespace App\Http\Controllers;

use App\Http\Requests\MasterMechanics\MasterMechanicsRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Services\Quiz\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Polygon\OTP\AuthorizationCode;

class UserController extends Controller
{
    public function index()
    {

        $data = User::findOrFail(auth()->id());
        return view('pages.apps.user-profile.index',['user' => $data]);

    }
    public function login(Request $request)
    {
//        dd($request->email);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('MyApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }

    }

    public function show()
    {
        dd(Auth::user());
    }

    public function registration(Request $request): JsonResponse
    {
//        $request->validated();

        $email =  new AuthorizationCode($request->authorization_code);
        dd($email->validate()->getFull());
//        $customer = $this->customerRepository->findByMobile($mobile);
//
//        if (!$customer) $customer = $this->register($mobile);
//
//        $jwt_token = auth()->guard($this->guard)->login($customer);
//
//        Event::dispatch('customer.after.login', $customer->email ?: "");
//
//        $customer = auth($this->guard)->user();
//
//        return response()->json([
//            'token' => $jwt_token,
//            'message' => 'Logged in successfully.',
//            'data' => new CustomerResource($customer)
//        ]);
    }

    public function dashboard(QuizService $quizService)
    {
        $user = Auth::user();

        $data = $quizService->processDashboard($user->id);

        if (!$data)
            return $this->error('You are not Enrolled as Master');

        return $this->success('test', $data);
    }

    public function point_history()
    {
        $user = $this->authUser();

        $point_history = $this->masterMechanicsService->point_history($user->id);

        $data = [
            'point_history' => PointHistoryResource::collection($point_history)
        ];

        return $this->success(ResponseMessages::SUCCESS, $data);
    }

    public function history()
    {
        $user = $this->authUser();

        $quiz_history = $this->masterMechanicsService->history($user->id);

        $data = [
            'quiz_history' => $quiz_history
        ];
        return $this->success(ResponseMessages::SUCCESS, $data);
    }
}
