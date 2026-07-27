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
}
