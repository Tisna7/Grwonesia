<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WhatsAppController extends Controller
{
  public function index(Request $request, WhatsAppService $whatsApp): View
  {
    $business = $request->user()->business;

    return view('business.whatsapp', [
      'messages' => $business->waMessages()->with('customer')->latest()->paginate(15),
      'customerCount' => $business->customers()->whereNotNull('phone')->count(),
      'isLive' => $whatsApp->isLive(),
      'recentBroadcasts' => $business->marketingContents()
        ->where('type', 'wa_broadcast')
        ->latest()
        ->take(5)
        ->get(),
    ]);
  }

  public function broadcast(Request $request, WhatsAppService $whatsApp): RedirectResponse
  {
    $business = $request->user()->business;

    $request->validate([
      'body' => ['required', 'string', 'max:2000'],
    ]);

    $customers = $business->customers()->whereNotNull('phone')->get();

    if ($customers->isEmpty()) {
      return back()->with('error', 'Belum ada pelanggan dengan nomor WhatsApp.');
    }

    $result = $whatsApp->broadcast($business, $request->body, $customers);

    $mode = $whatsApp->isLive() ? 'terkirim' : 'tercatat (mode simulasi — gateway belum terhubung)';

    return back()->with('success', "Broadcast {$mode}: {$result['sent']} pesan, {$result['skipped']} dilewati.");
  }

  public function updatePhone(Request $request): RedirectResponse
  {
    $request->validate([
      'phone' => ['required', 'string'],
    ]);

    $user = $request->user();
    $phoneInput = $request->input('phone');
    $cleanedPhone = preg_replace('/\D/', '', $phoneInput);
    $resolvedJid = $cleanedPhone;

    // Call WA Gateway resolve JID
    try {
      $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Authorization' => 'Bearer ' . env('WA_GATEWAY_TOKEN'),
      ])->timeout(5)->get('http://localhost:3010/resolve', [
            'phone' => $cleanedPhone
          ]);

      if ($response->successful()) {
        $data = $response->json();
        if (!empty($data['exists']) && !empty($data['jid'])) {
          $jidParts = explode('@', $data['jid']);
          $resolvedJid = $jidParts[0];
        }
      }
    } catch (\Throwable $e) {
      \Log::warning('Gagal resolve JID bisnis dari WA Gateway: ' . $e->getMessage());
    }

    $user->update([
      'phone' => $resolvedJid,
      'raw_phone' => $phoneInput,
    ]);

    if ($user->business) {
      $user->business->update([
        'wa_number' => $phoneInput,
      ]);
    }

    return back()->with('success', "Nomor WhatsApp Toko berhasil ditautkan! (ID: {$phoneInput})");
  }
}

