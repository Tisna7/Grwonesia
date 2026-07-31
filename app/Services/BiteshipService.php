<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BiteshipService
{
    protected string $apiKey;
    protected string $baseUrl;

    // Origin warehouse location (UMKM seller hub)
    protected const ORIGIN_POSTAL_CODE = 12440; // Kebayoran Lama, Jakarta Selatan
    protected const ORIGIN_AREA_NAME = 'Jakarta Selatan';

    public function __construct()
    {
        $this->apiKey = config('services.biteship.api_key') ?: env('BITESHIP_API_KEY', '');
        $this->baseUrl = config('services.biteship.base_url') ?: 'https://api.biteship.com/v1';
    }

    /**
     * Search areas from user input (city/district/postal code) via Biteship Maps API.
     * Used for address autocomplete in checkout.
     */
    public function searchArea(string $query): array
    {
        if (empty(trim($query)) || strlen(trim($query)) < 3) {
            return ['success' => false, 'areas' => []];
        }

        try {
            $url = "{$this->baseUrl}/maps/areas?countries=ID&input=" . urlencode($query) . "&type=single";
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->timeout(5)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $areas = [];
                foreach (($data['areas'] ?? []) as $area) {
                    $areas[] = [
                        'id' => $area['id'] ?? '',
                        'name' => $area['name'] ?? '',
                        'postal_code' => $area['postal_code'] ?? null,
                        'province' => $area['administrative_division_level_1_name'] ?? '',
                        'city' => $area['administrative_division_level_2_name'] ?? '',
                        'district' => $area['administrative_division_level_3_name'] ?? '',
                    ];
                }
                return ['success' => true, 'areas' => $areas];
            } else {
                Log::warning("Biteship Search Area Warning [{$response->status()}]: " . $response->body());
            }
        } catch (\Throwable $e) {
            Log::error("Biteship Search Area Exception: " . $e->getMessage());
        }

        return ['success' => false, 'areas' => []];
    }

    /**
     * Get shipping rates from Biteship API using postal codes.
     * Falls back to smart distance-based pricing if API balance is insufficient.
     */
    public function getShippingRates(array $params): array
    {
        $destPostalCode = $params['destination_postal_code'] ?? null;
        $destAreaId = $params['destination_area_id'] ?? null;
        $destCity = $params['destination_city'] ?? '';
        $items = $params['items'] ?? [['name' => 'Produk UMKM', 'value' => 50000, 'weight' => 500, 'quantity' => 1]];
        $couriers = $params['couriers'] ?? 'jne,sicepat,jnt,pos';

        // Build Biteship payload
        $payload = [
            'couriers' => $couriers,
            'items' => $items,
        ];

        // Use postal codes (preferred) or area IDs
        if ($destPostalCode) {
            $payload['origin_postal_code'] = self::ORIGIN_POSTAL_CODE;
            $payload['destination_postal_code'] = (int) $destPostalCode;
        } elseif ($destAreaId) {
            $payload['origin_area_id'] = $params['origin_area_id'] ?? 'IDNP6IDNC148IDND838IDZ12440';
            $payload['destination_area_id'] = $destAreaId;
        } else {
            // No destination info — use fallback
            return $this->getFallbackRates($destCity);
        }

        try {
            $url = "{$this->baseUrl}/rates/couriers";
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $pricing = [];

                if (!empty($data['pricing']) && is_array($data['pricing'])) {
                    foreach ($data['pricing'] as $rate) {
                        $pricing[] = [
                            'courier_code' => $rate['courier_code'] ?? 'jne',
                            'courier_name' => $rate['courier_name'] ?? 'JNE Express',
                            'service_name' => $rate['courier_service_name'] ?? 'Reguler',
                            'price' => (int) ($rate['price'] ?? 15000),
                            'etd' => $rate['duration'] ?? '1-3 Hari',
                        ];
                    }
                }

                if (!empty($pricing)) {
                    Log::info("Biteship Rates API returned " . count($pricing) . " rates for postal code {$destPostalCode}");
                    return ['success' => true, 'rates' => $pricing, 'source' => 'biteship_api'];
                }
            } else {
                $errorBody = $response->json();
                $errorMsg = $errorBody['error'] ?? $response->body();
                Log::warning("Biteship Rates API [{$response->status()}]: {$errorMsg}");
            }
        } catch (\Throwable $e) {
            Log::error("Biteship Rates Exception: " . $e->getMessage());
        }

        // Fallback: smart distance-based pricing
        return $this->getFallbackRates($destCity, $destPostalCode);
    }

    /**
     * Smart fallback pricing based on destination city/postal code distance from Jakarta.
     * Groups Indonesian cities into pricing zones.
     */
    protected function getFallbackRates(string $destCity = '', ?string $postalCode = null): array
    {
        $zone = $this->detectZone($destCity, $postalCode);

        $zoneConfig = [
            'jakarta' => ['base' => 9000, 'etd_fast' => '1 Hari', 'etd_reg' => '1-2 Hari', 'label' => 'Jabodetabek'],
            'jawa' => ['base' => 12000, 'etd_fast' => '1-2 Hari', 'etd_reg' => '2-3 Hari', 'label' => 'Pulau Jawa'],
            'sumatera' => ['base' => 18000, 'etd_fast' => '2-3 Hari', 'etd_reg' => '3-5 Hari', 'label' => 'Sumatera'],
            'kalimantan' => ['base' => 22000, 'etd_fast' => '2-4 Hari', 'etd_reg' => '3-6 Hari', 'label' => 'Kalimantan'],
            'sulawesi' => ['base' => 25000, 'etd_fast' => '3-5 Hari', 'etd_reg' => '4-7 Hari', 'label' => 'Sulawesi'],
            'bali_ntt' => ['base' => 20000, 'etd_fast' => '2-3 Hari', 'etd_reg' => '3-5 Hari', 'label' => 'Bali / NTB / NTT'],
            'papua' => ['base' => 35000, 'etd_fast' => '5-7 Hari', 'etd_reg' => '7-14 Hari', 'label' => 'Papua / Maluku'],
            'default' => ['base' => 15000, 'etd_fast' => '2-3 Hari', 'etd_reg' => '3-5 Hari', 'label' => 'Indonesia'],
        ];

        $config = $zoneConfig[$zone] ?? $zoneConfig['default'];
        $base = $config['base'];

        return [
            'success' => true,
            'source' => 'fallback_zone',
            'zone' => $config['label'],
            'rates' => [
                ['courier_code' => 'jne', 'courier_name' => 'JNE', 'service_name' => 'REG (Reguler)', 'price' => $base, 'etd' => $config['etd_reg']],
                ['courier_code' => 'sicepat', 'courier_name' => 'SiCepat', 'service_name' => 'REG (Reguler)', 'price' => $base - 1000, 'etd' => $config['etd_reg']],
                ['courier_code' => 'jnt', 'courier_name' => 'J&T Express', 'service_name' => 'EZ (Reguler)', 'price' => $base + 1000, 'etd' => $config['etd_reg']],
                ['courier_code' => 'jne', 'courier_name' => 'JNE', 'service_name' => 'YES (Same Day)', 'price' => $base + 8000, 'etd' => $config['etd_fast']],
            ],
        ];
    }

    /**
     * Detect shipping zone from city name or postal code.
     */
    protected function detectZone(string $city, ?string $postalCode = null): string
    {
        $city = strtolower(trim($city));

        // Postal code ranges (first 2 digits)
        if ($postalCode && strlen($postalCode) >= 2) {
            $prefix = (int) substr($postalCode, 0, 2);
            if ($prefix >= 10 && $prefix <= 16)
                return 'jakarta'; // DKI Jakarta & Bodetabek
            if ($prefix >= 17 && $prefix <= 17)
                return 'jakarta'; // Tangerang
            if (($prefix >= 40 && $prefix <= 46) || ($prefix >= 50 && $prefix <= 59))
                return 'jawa'; // Jawa Barat & Jawa Tengah
            if ($prefix >= 60 && $prefix <= 69)
                return 'jawa'; // Jawa Timur
            if ($prefix >= 55 && $prefix <= 56)
                return 'jawa'; // Yogyakarta
            if ($prefix >= 20 && $prefix <= 39)
                return 'sumatera';
            if ($prefix >= 70 && $prefix <= 77)
                return 'kalimantan';
            if ($prefix >= 80 && $prefix <= 87)
                return 'bali_ntt';
            if ($prefix >= 90 && $prefix <= 96)
                return 'sulawesi';
            if ($prefix >= 97 && $prefix <= 99)
                return 'papua';
        }

        // City name matching
        $jakartaKeys = ['jakarta', 'tangerang', 'bekasi', 'depok', 'bogor', 'cikarang'];
        foreach ($jakartaKeys as $key) {
            if (str_contains($city, $key))
                return 'jakarta';
        }

        $jawaKeys = ['bandung', 'semarang', 'surabaya', 'yogyakarta', 'jogja', 'solo', 'malang', 'cirebon', 'karawang', 'purwokerto', 'tegal', 'pekalongan', 'kediri', 'madiun', 'jember', 'tasikmalaya', 'sukabumi', 'garut'];
        foreach ($jawaKeys as $key) {
            if (str_contains($city, $key))
                return 'jawa';
        }

        $sumateraKeys = ['medan', 'palembang', 'padang', 'pekanbaru', 'jambi', 'lampung', 'bengkulu', 'aceh', 'batam', 'pangkal'];
        foreach ($sumateraKeys as $key) {
            if (str_contains($city, $key))
                return 'sumatera';
        }

        $kalimantanKeys = ['pontianak', 'banjarmasin', 'balikpapan', 'samarinda', 'palangkaraya', 'tarakan'];
        foreach ($kalimantanKeys as $key) {
            if (str_contains($city, $key))
                return 'kalimantan';
        }

        $sulawesiKeys = ['makassar', 'manado', 'palu', 'kendari', 'gorontalo'];
        foreach ($sulawesiKeys as $key) {
            if (str_contains($city, $key))
                return 'sulawesi';
        }

        $baliKeys = ['bali', 'denpasar', 'mataram', 'lombok', 'kupang', 'labuan bajo'];
        foreach ($baliKeys as $key) {
            if (str_contains($city, $key))
                return 'bali_ntt';
        }

        $papuaKeys = ['papua', 'jayapura', 'sorong', 'ambon', 'ternate', 'maluku', 'manokwari'];
        foreach ($papuaKeys as $key) {
            if (str_contains($city, $key))
                return 'papua';
        }

        return 'default';
    }

    /**
     * Track shipment via Biteship API. 
     * Biteship tracking works with tracking_id from orders created via their API.
     * For orders not created via Biteship, returns smart mock timeline.
     */
    public function trackWaybill(string $trackingIdOrWaybill, string $courierCode = 'jne'): array
    {
        if (empty($trackingIdOrWaybill)) {
            return ['success' => false, 'message' => 'Tracking ID is required'];
        }

        $cleanCourier = strtolower(explode(' ', trim($courierCode))[0]);

        // Attempt real Biteship tracking if it looks like a Biteship tracking ID (UUID format)
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-/i', $trackingIdOrWaybill)) {
            try {
                $url = "{$this->baseUrl}/trackings/{$trackingIdOrWaybill}";
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])->timeout(8)->get($url);

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info("Biteship tracking fetched for {$trackingIdOrWaybill}");

                    $history = [];
                    foreach (($data['history'] ?? []) as $item) {
                        $history[] = [
                            'time' => !empty($item['updated_at']) ? date('d M, H:i', strtotime($item['updated_at'])) : date('d M, H:i'),
                            'location' => $item['location'] ?? 'Sentral Logistik',
                            'desc' => $item['note'] ?? ($item['status'] ?? 'Status diperbarui'),
                            'status' => $item['status'] ?? 'shipped',
                            'icon' => ($item['status'] ?? '') === 'delivered' ? 'check-circle' : 'truck',
                            'done' => true,
                        ];
                    }

                    return [
                        'success' => true,
                        'source' => 'biteship_api',
                        'waybill_id' => $trackingIdOrWaybill,
                        'courier' => strtoupper($data['courier']['company'] ?? $cleanCourier),
                        'status' => $data['status'] ?? 'in_transit',
                        'current_location' => $data['courier']['history'][0]['location'] ?? 'Transit Hub',
                        'history' => $history,
                    ];
                }
            } catch (\Throwable $e) {
                Log::error("Biteship Tracking Exception: " . $e->getMessage());
            }
        }

        // Return rich mock tracking timeline for demo/test resi
        return $this->getMockTracking($trackingIdOrWaybill, $cleanCourier);
    }

    /**
     * Generate realistic mock tracking timeline for demo purposes.
     */
    protected function getMockTracking(string $waybillNumber, string $courierCode): array
    {
        $courierName = strtoupper($courierCode ?: 'JNE');

        return [
            'success' => true,
            'source' => 'mock',
            'waybill_id' => $waybillNumber,
            'courier' => $courierName,
            'status' => 'in_transit',
            'current_location' => 'Hub Transit Sortir ' . $courierName . ' Gateway',
            'history' => [
                [
                    'time' => date('d M, H:i', strtotime('-1 day')),
                    'location' => 'Gudang Penjual UMKM, Jakarta',
                    'desc' => 'Pesanan telah diterima oleh ekspedisi ' . $courierName,
                    'status' => 'allocated',
                    'icon' => 'package',
                    'done' => true,
                ],
                [
                    'time' => date('d M, H:i', strtotime('-18 hours')),
                    'location' => 'Sorting Center ' . $courierName . ' Jakarta',
                    'desc' => 'Paket sedang disortir di gudang pengiriman utama',
                    'status' => 'picking_up',
                    'icon' => 'truck',
                    'done' => true,
                ],
                [
                    'time' => date('d M, H:i', strtotime('-6 hours')),
                    'location' => 'Hub Transit Sortir ' . $courierName . ' Gateway',
                    'desc' => 'Paket dalam perjalanan menuju alamat tujuan pembeli',
                    'status' => 'in_transit',
                    'icon' => 'truck',
                    'done' => true,
                ],
            ],
        ];
    }
}
