<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\GovProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProgramSignupController extends Controller
{
    public function store(Request $request, GovProgram $program): RedirectResponse
    {
        $business = $request->user()->business;

        abort_unless(
            GovProgram::relevantFor($business)->whereKey($program->id)->exists(),
            404,
        );

        $business->programRegistrations()->firstOrCreate([
            'gov_program_id' => $program->id,
        ]);

        return back()->with('success', "Berhasil mendaftar ke program \"{$program->title}\".");
    }
}
