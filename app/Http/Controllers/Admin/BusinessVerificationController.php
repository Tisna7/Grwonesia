<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Services\PlatformMetricsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessVerificationController extends Controller
{
    public function index(PlatformMetricsService $metrics): View
    {
        return view('admin.businesses', [
            'businesses' => $metrics->businessesWithRisk(),
        ]);
    }

    public function update(Request $request, Business $business): RedirectResponse
    {
        $validated = $request->validate([
            'verification_status' => ['required', 'in:pending,verified,rejected'],
            'verification_note' => ['nullable', 'string', 'max:500'],
        ]);

        $business->update([
            'verification_status' => $validated['verification_status'],
            'verification_note' => $validated['verification_note'] ?? null,
            'verified_at' => $validated['verification_status'] === 'verified' ? now() : null,
        ]);

        return back()->with('success', "Status verifikasi {$business->name} diperbarui.");
    }
}
