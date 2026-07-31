<?php

namespace App\Http\Controllers;

use App\Services\WhatsApp\WhatsAppBotSessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
  public function handle(Request $request, WhatsAppBotSessionService $sessionService): JsonResponse
  {
    $token = $request->bearerToken();
    $expectedToken = config('services.whatsapp.token');

    if (blank($expectedToken) || $token !== $expectedToken) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $data = $request->validate([
      'from' => ['required', 'string'],
      'type' => ['required', 'string', 'in:text,image'],
      'body' => ['nullable', 'string'],
      'tempPath' => ['nullable', 'string'],
    ]);

    try {
      $reply = $sessionService->handleMessage(
        $data['from'],
        $data['type'],
        $data['body'] ?? '',
        $data['tempPath'] ?? null
      );

      return response()->json(['status' => 'processed', 'reply' => $reply]);
    } catch (\Exception $e) {
      Log::error('Error processing WhatsApp webhook message: ' . $e->getMessage(), [
        'exception' => $e,
        'from' => $data['from'],
      ]);
      return response()->json(['error' => 'Internal Server Error'], 500);
    }
  }
}
