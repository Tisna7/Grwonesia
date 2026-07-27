<?php

namespace App\Services;

use App\Services\Ai\GeminiClient;

class PolicySimulatorService
{
    public function __construct(
        private readonly GeminiClient $gemini,
        private readonly GovernmentMetricsService $metrics,
    ) {}

    /**
     * Simulasi dampak kebijakan — model elastisitas deterministik.
     *
     * Asumsi elastisitas (sederhana, transparan untuk demo):
     * - Subsidi ongkir: tiap 10% subsidi → omzet naik ~7% (elastisitas 0.7, diminishing)
     * - Bantuan alat produksi: tiap Rp10jt → kapasitas naik ~3%
     * - Pelatihan digital: tiap batch 25 UMKM → omzet peserta naik ~12%, menyebar ke ~15% populasi
     */
    public function simulate(array $params): array
    {
        $subsidiPct = (float) ($params['subsidi_ongkir'] ?? 0);           // 0-50 (%)
        $bantuanAlat = (float) ($params['bantuan_alat'] ?? 0);            // Rp juta
        $pelatihanBatch = (int) ($params['pelatihan_batch'] ?? 0);        // jumlah batch
        $durasi = max(1, (int) ($params['durasi_bulan'] ?? 3));           // bulan
        $sector = $params['sector'] ?? null;

        $stats = $this->metrics->regionalStats();
        $baselineMonthly = $stats['omzet_30'];

        if ($sector) {
            $sectorRow = collect($stats['category_trends'])->firstWhere('category', $sector);
            $baselineMonthly = $sectorRow['omzet'] ?? $baselineMonthly * 0.3;
        }

        // Efek subsidi ongkir: diminishing returns (akar kuadrat)
        $subsidiEffect = $subsidiPct > 0 ? 0.07 * sqrt($subsidiPct / 10) * ($subsidiPct / 10) : 0;
        $subsidiEffect = min($subsidiEffect, 0.45);

        // Efek bantuan alat: linear, cap 20%
        $alatEffect = min($bantuanAlat / 10 * 0.03, 0.20);

        // Efek pelatihan: 12% untuk peserta, penetrasi 15% per batch (cap 60% populasi)
        $penetration = min($pelatihanBatch * 0.15, 0.6);
        $pelatihanEffect = 0.12 * $penetration;

        $totalUplift = $subsidiEffect + $alatEffect + $pelatihanEffect;

        // Proyeksi bulanan: ramp-up 60% bulan pertama, penuh setelahnya
        $monthly = [];
        $cumulativeExtra = 0.0;
        for ($m = 1; $m <= $durasi; $m++) {
            $ramp = $m === 1 ? 0.6 : 1.0;
            $projected = $baselineMonthly * (1 + $totalUplift * $ramp);
            $cumulativeExtra += $projected - $baselineMonthly;
            $monthly[] = [
                'month' => 'Bulan '.$m,
                'baseline' => round($baselineMonthly),
                'projected' => round($projected),
            ];
        }

        // Estimasi biaya program
        $subsidiCost = $baselineMonthly * ($subsidiPct / 100) * 0.35 * $durasi; // asumsi ongkir ~35% dari nilai order disubsidi
        $alatCost = $bantuanAlat * 1000000;
        $pelatihanCost = $pelatihanBatch * 25 * 350000; // Rp350rb/peserta
        $totalCost = $subsidiCost + $alatCost + $pelatihanCost;

        // Serapan tenaga kerja: 1 pekerja baru per ~Rp15jt omzet tambahan/bulan
        $extraMonthly = $baselineMonthly * $totalUplift;
        $newJobs = (int) floor($extraMonthly / 15000000 * $durasi);

        return [
            'baseline_monthly' => round($baselineMonthly),
            'uplift_pct' => round($totalUplift * 100, 1),
            'monthly' => $monthly,
            'cumulative_extra' => round($cumulativeExtra),
            'estimated_cost' => round($totalCost),
            'roi' => $totalCost > 0 ? round($cumulativeExtra / $totalCost, 2) : null,
            'new_jobs' => $newJobs,
            'components' => [
                'Subsidi Ongkir' => round($subsidiEffect * 100, 1),
                'Bantuan Alat' => round($alatEffect * 100, 1),
                'Pelatihan Digital' => round($pelatihanEffect * 100, 1),
            ],
        ];
    }

    /**
     * Narasi AI atas hasil simulasi (on-demand, tidak di-cache — parameter selalu beda).
     */
    public function narrative(array $params, array $result): ?string
    {
        return $this->gemini->generateText(
            "Hasil simulasi kebijakan UMKM:\n".
            'Parameter: '.json_encode($params, JSON_UNESCAPED_UNICODE)."\n".
            'Hasil: '.json_encode([
                'kenaikan_omzet_pct' => $result['uplift_pct'],
                'omzet_tambahan_kumulatif' => $result['cumulative_extra'],
                'estimasi_biaya_program' => $result['estimated_cost'],
                'roi' => $result['roi'],
                'serapan_tenaga_kerja_baru' => $result['new_jobs'],
            ], JSON_UNESCAPED_UNICODE).
            "\n\nTulis ringkasan eksekutif 3-4 kalimat untuk kepala dinas: apakah kebijakan ini layak, risikonya, dan saran penyesuaian.",
            'Kamu adalah AI Policy Simulator Grownesia. Bahasa Indonesia formal, ringkas, seimbang antara peluang dan risiko.',
        );
    }
}
