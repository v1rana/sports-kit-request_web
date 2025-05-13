<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MunicipalBodyMember;

class MunicipalBodyMemberController extends Controller {
    public function index() {
        $data = MunicipalBodyMember::all();
        return view('municipal.index', compact('data'));
    }
}
