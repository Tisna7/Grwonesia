<?php

namespace App\Services\Ai;

use App\Models\AiRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class GeminiClient
{
    private const BASE_URL = 'https://generativelanguage.googleapis.com/v1beta/models';

    private ?string $modelOverride = null;

    public function isConfigured(): bool
    {
        return filled(config('services.gemini.key'));
    }

    /**
     * Varian hemat: pakai model lite untuk tugas ringan (copywriting pendek, judul, deskripsi).
     */
    public function lite(): self
    {
        $clone = clone $this;
        $clone->modelOverride = (string) config('services.gemini.model_lite');

        return $clone;
    }

    private function model(): string
    {
        return $this->modelOverride ?? (string) config('services.gemini.model');
    }

    public function generateText(string $prompt, ?string $system = null): ?string
    {
        $response = $this->request([
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $prompt]]],
            ],
        ], $system, 'text');

        return $this->extractText($response);
    }

    /**
     * Structured output — memaksa Gemini merespons JSON valid sesuai schema.
     */
    public function generateJson(string $prompt, array $responseSchema, ?string $system = null): ?array
    {
        $response = $this->request([
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $prompt]]],
            ],
            'generationConfig' => [
                'response_mime_type' => 'application/json',
                'response_schema' => $responseSchema,
            ],
        ], $system, 'json');

        $text = $this->extractText($response);

        if ($text === null) {
            return null;
        }

        $decoded = json_decode($text, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Percakapan multi-turn.
     *
     * @param  array<array{role: string, content: string}>  $messages  role: user|model
     */
    public function chat(array $messages, ?string $system = null): ?string
    {
        $contents = array_map(fn (array $m) => [
            'role' => $m['role'] === 'model' ? 'model' : 'user',
            'parts' => [['text' => $m['content']]],
        ], $messages);

        $response = $this->request(['contents' => $contents], $system, 'chat');

        return $this->extractText($response);
    }

    /**
     * Analisis gambar (vision). Jika $responseSchema diberikan, hasil berupa JSON terstruktur.
     */
    public function analyzeImage(string $imagePath, string $prompt, ?array $responseSchema = null): array|string|null
    {
        if (! is_readable($imagePath)) {
            Log::warning('GeminiClient: file gambar tidak terbaca', ['path' => $imagePath]);

            return null;
        }

        $payload = [
            'contents' => [[
                'role' => 'user',
                'parts' => [
                    ['text' => $prompt],
                    [
                        'inline_data' => [
                            'mime_type' => mime_content_type($imagePath) ?: 'image/jpeg',
                            'data' => base64_encode((string) file_get_contents($imagePath)),
                        ],
                    ],
                ],
            ]],
        ];

        if ($responseSchema !== null) {
            $payload['generationConfig'] = [
                'response_mime_type' => 'application/json',
                'response_schema' => $responseSchema,
            ];
        }

        $text = $this->extractText($this->request($payload, null, 'vision'));

        if ($text === null) {
            return null;
        }

        if ($responseSchema !== null) {
            $decoded = json_decode($text, true);

            return is_array($decoded) ? $decoded : null;
        }

        return $text;
    }

    private function request(array $payload, ?string $system = null, string $kind = 'text'): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        if ($system !== null) {
            $payload['systemInstruction'] = ['parts' => [['text' => $system]]];
        }

        $url = sprintf(
            '%s/%s:generateContent',
            self::BASE_URL,
            $this->model(),
        );

        $startedAt = microtime(true);

        try {
            $response = Http::timeout(30)
                ->retry(1, 500, throw: false)
                ->withHeaders(['x-goog-api-key' => config('services.gemini.key')])
                ->post($url, $payload);

            if ($response->failed()) {
                Log::warning('GeminiClient: request gagal', [
                    'status' => $response->status(),
                    'body' => mb_substr($response->body(), 0, 500),
                ]);
                $this->logRequest($kind, false, $startedAt, null, 'HTTP '.$response->status());

                return null;
            }

            $json = $response->json();
            $this->logRequest($kind, true, $startedAt, $json['usageMetadata'] ?? null);

            return $json;
        } catch (Throwable $e) {
            Log::warning('GeminiClient: exception', ['message' => $e->getMessage()]);
            $this->logRequest($kind, false, $startedAt, null, mb_substr($e->getMessage(), 0, 120));

            return null;
        }
    }

    /**
     * Catat panggilan untuk AI Management Center — jangan pernah menggagalkan request utama.
     */
    private function logRequest(string $kind, bool $success, float $startedAt, ?array $usage = null, ?string $error = null): void
    {
        try {
            AiRequest::create([
                'model' => $this->model(),
                'kind' => $kind,
                'success' => $success,
                'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
                'prompt_tokens' => $usage['promptTokenCount'] ?? null,
                'output_tokens' => $usage['candidatesTokenCount'] ?? null,
                'error' => $error,
            ]);
        } catch (Throwable) {
            // Logging tidak boleh mematahkan alur AI
        }
    }

    private function extractText(?array $response): ?string
    {
        $text = $response['candidates'][0]['content']['parts'][0]['text'] ?? null;

        return is_string($text) && $text !== '' ? $text : null;
    }
}
