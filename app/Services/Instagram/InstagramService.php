<?php

namespace App\Services\Instagram;

use App\Models\Business;
use App\Models\IgPost;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Instagram Content Publishing via Meta Graph API.
 *
 * Alur resmi Meta (2 langkah):
 *   1. POST /{ig-user-id}/media          → membuat media container (image_url + caption)
 *   2. POST /{ig-user-id}/media_publish  → mempublikasikan container
 *
 * Catatan: image_url HARUS bisa diakses publik — server Meta yang mengunduh fotonya.
 */
class InstagramService
{
    private const GRAPH_URL = 'https://graph.facebook.com/v21.0';

    public function isConfigured(): bool
    {
        return filled(config('services.instagram.business_id'))
            && filled(config('services.instagram.token'));
    }

    /**
     * Info akun IG untuk kartu "status koneksi". Null jika token/ID salah.
     */
    public function accountInfo(): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::timeout(15)->get(
                self::GRAPH_URL.'/'.config('services.instagram.business_id'),
                [
                    'fields' => 'username,name,profile_picture_url,followers_count,media_count',
                    'access_token' => config('services.instagram.token'),
                ],
            );

            return $response->successful() ? $response->json() : null;
        } catch (Throwable $e) {
            Log::warning('InstagramService: accountInfo gagal', ['message' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Publikasikan foto + caption. Selalu mencatat hasilnya ke ig_posts.
     */
    public function publishPhoto(Business $business, string $imageUrl, string $caption, ?Product $product = null): IgPost
    {
        $post = new IgPost([
            'business_id' => $business->id,
            'product_id' => $product?->id,
            'image_url' => $imageUrl,
            'caption' => $caption,
            'status' => 'failed',
        ]);

        if (! $this->isConfigured()) {
            $post->error = 'Instagram belum dikonfigurasi (IG_BUSINESS_ID / IG_ACCESS_TOKEN kosong).';
            $post->save();

            return $post;
        }

        $igUserId = config('services.instagram.business_id');
        $token = config('services.instagram.token');

        try {
            // Langkah 1: buat media container
            $container = Http::timeout(60)->post(self::GRAPH_URL."/{$igUserId}/media", [
                'image_url' => $imageUrl,
                'caption' => $caption,
                'access_token' => $token,
            ]);

            if ($container->failed()) {
                $post->error = $this->extractGraphError($container->json(), 'Gagal membuat media container');
                $post->save();

                return $post;
            }

            $creationId = $container->json('id');

            // Langkah 2: publish container
            $publish = Http::timeout(60)->post(self::GRAPH_URL."/{$igUserId}/media_publish", [
                'creation_id' => $creationId,
                'access_token' => $token,
            ]);

            if ($publish->failed()) {
                $post->error = $this->extractGraphError($publish->json(), 'Gagal mempublikasikan media');
                $post->save();

                return $post;
            }

            $post->status = 'published';
            $post->ig_media_id = $publish->json('id');
            $post->error = null;
        } catch (Throwable $e) {
            Log::warning('InstagramService: publish exception', ['message' => $e->getMessage()]);
            $post->error = mb_substr($e->getMessage(), 0, 490);
        }

        $post->save();

        return $post;
    }

    private function extractGraphError(?array $body, string $fallback): string
    {
        $message = $body['error']['message'] ?? $fallback;
        $userMessage = $body['error']['error_user_msg'] ?? null;

        return mb_substr($userMessage ? "{$message} — {$userMessage}" : $message, 0, 490);
    }
}
