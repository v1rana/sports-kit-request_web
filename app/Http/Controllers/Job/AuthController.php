<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return response()->json([
            'id' => 1,
            'name' => 'test user',
            'pppId' => $request->pppId,
        ]);
    }
}
