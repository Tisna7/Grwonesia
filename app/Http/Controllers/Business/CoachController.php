<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\AiChat;
use App\Services\Ai\BusinessCoachService;
use App\Services\Ai\GeminiClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoachController extends Controller
{
    public function index(Request $request, GeminiClient $gemini): View
    {
        $business = $request->user()->business;

        $chats = $business->aiChats()->latest()->take(15)->get();
        $activeChat = $request->filled('chat')
            ? $business->aiChats()->with('messages')->find($request->chat)
            : null;

        return view('business.coach', [
            'chats' => $chats,
            'activeChat' => $activeChat,
            'aiConfigured' => $gemini->isConfigured(),
        ]);
    }

    public function send(Request $request, BusinessCoachService $coach, ?AiChat $chat = null): JsonResponse
    {
        $business = $request->user()->business;

        $request->validate([
            'message' => ['required', 'string', 'max:3000'],
        ]);

        if ($chat) {
            abort_unless($chat->business_id === $business->id, 404);
        } else {
            $chat = AiChat::create(['business_id' => $business->id]);
        }

        $answer = $coach->reply($chat, $request->message);

        if ($answer === null) {
            return response()->json(['error' => 'AI belum tersedia. Periksa GEMINI_API_KEY atau kuota API.'], 503);
        }

        return response()->json([
            'data' => [
                'chat_id' => $chat->id,
                'answer' => $answer,
            ],
        ]);
    }
}
