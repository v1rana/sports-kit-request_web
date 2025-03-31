<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GramPanchayatSarpanch;

class GramPanchayatSarpanchController extends Controller {
    public function index() {
        $data = GramPanchayatSarpanch::all();
        return view('sarpanch.index', compact('data'));
    }
}
