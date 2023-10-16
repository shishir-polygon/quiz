<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {

        $data = User::findOrFail(auth()->id());
        return view('pages.apps.user-profile.index',['user' => $data]);

    }
}
