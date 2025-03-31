<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GramPanchayatSarpanch;
use App\Models\MunicipalBodyMember;

class RegistrationController extends Controller {
    // Show the registration form
    public function create() {
        return view('registration.form');
    }

    // Store form data
    public function store(Request $request) {
        $request->validate([
            'district' => 'required|string|max:50',
            'block' => 'required|string|max:50',
            'type' => 'required|string',
            'area' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'designation' => 'required|string',
            'declaration_letter' => 'file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        // Handle file upload
        if ($request->hasFile('declaration_letter')) {
            $file = $request->file('declaration_letter');
            $filePath = $file->store('declarations', 'public');
        } else {
            $filePath = null;
        }

        // Store data in correct table based on type
        if ($request->type == 'gram_panchayat') {
            GramPanchayatSarpanch::create([
                'district' => $request->district,
                'block' => $request->block,
                'gram_panchayat' => $request->area,
                'sarpanch' => $request->name,
                'mob' => $request->input('mob', null)
            ]);
        } else {
            MunicipalBodyMember::create([
                'district' => $request->district,
                'block' => $request->block,
                'municipal_area' => $request->area,
                'wardmember' => $request->name,
                'mob' => $request->input('mob', null)
            ]);
        }

        return redirect()->route('register.form')->with('success', 'Registration successful!');
    }
}
