<?php

namespace App\Http\Controllers\Government;

use App\Http\Controllers\Controller;
use App\Models\GovProgram;
use App\Services\Ai\EconomicInsightService;
use App\Services\Ai\GeminiClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(Request $request, GeminiClient $gemini): View
    {
        return view('government.programs', [
            'programs' => GovProgram::withCount('registrations')->latest()->paginate(10),
            'aiConfigured' => $gemini->isConfigured(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:pelatihan,bantuan,event,pameran'],
            'sector' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:3000'],
            'status' => ['required', 'in:draft,aktif,selesai'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'ai_recommended' => ['nullable', 'boolean'],
        ]);

        $validated['user_id'] = $request->user()->id;

        GovProgram::create($validated);

        return redirect()->route('government.programs')
            ->with('success', 'Program berhasil ditambahkan.');
    }

    public function update(Request $request, GovProgram $program): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,aktif,selesai'],
        ]);

        $program->update($validated);

        return back()->with('success', 'Status program diperbarui.');
    }

    public function destroy(GovProgram $program): RedirectResponse
    {
        $program->delete();

        return back()->with('success', 'Program dihapus.');
    }

    public function recommend(EconomicInsightService $service): JsonResponse
    {
        $result = $service->recommendPrograms();

        return $result !== null
            ? response()->json(['data' => $result])
            : response()->json(['error' => 'AI belum tersedia. Periksa GEMINI_API_KEY atau kuota API.'], 503);
    }
}
