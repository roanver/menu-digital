<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    private string $apiKey;
    private string $model = 'gemini-1.5-flash';
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
    }

    public function extractMenuFromImages(array $base64Images): array
    {
        $parts = [];
        foreach ($base64Images as [$b64, $mimeType]) {
            $parts[] = [
                'inline_data' => [
                    'mime_type' => $mimeType,
                    'data'      => $b64,
                ],
            ];
        }
        $parts[] = [
            'text' => 'Esta es una foto de una carta de restaurante chileno. Extrae TODOS los platos y categorías que veas. Responde SOLO con JSON válido en este formato exacto, sin texto adicional ni bloques de código:
{"categories":[{"name":"Nombre Categoría","items":[{"name":"Nombre Plato","description":"descripción breve o vacío","price":9900}]}]}
Los precios son enteros en pesos chilenos (CLP). Si no ves precio claro, usa 0. No incluyas $ ni puntos en los precios, solo el número entero.',
        ];

        if (empty($this->apiKey)) {
            throw new \RuntimeException('GEMINI_API_KEY no está configurada.');
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(60)->post("{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}", [
            'contents' => [
                ['parts' => $parts],
            ],
            'generationConfig' => [
                'maxOutputTokens' => 4096,
                'temperature'     => 0.1,
            ],
        ]);

        // Error HTTP de la API
        if ($response->failed()) {
            $error = $response->json('error.message', 'Sin detalle');
            throw new \RuntimeException("Error Gemini ({$response->status()}): {$error}");
        }

        $text = $response->json('candidates.0.content.parts.0.text', '');

        if (empty($text)) {
            // Puede que Gemini bloqueó la respuesta (safety filters)
            $blockReason = $response->json('promptFeedback.blockReason', '');
            if ($blockReason) {
                throw new \RuntimeException("Gemini bloqueó la imagen: {$blockReason}");
            }
            throw new \RuntimeException('Gemini no devolvió texto. Respuesta: ' . $response->body());
        }

        // Limpiar posibles bloques ```json ... ```
        $text = preg_replace('/^```json\s*/m', '', $text);
        $text = preg_replace('/^```\s*/m', '', $text);
        $text = trim($text);

        $data = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('JSON inválido de Gemini: ' . mb_substr($text, 0, 200));
        }

        return $data['categories'] ?? [];
    }

    public function generatePostCopy(string $itemName, string $itemDescription, int $price): string
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(30)->post("{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}", [
            'contents' => [[
                'parts' => [[
                    'text' => "Genera un copy corto y apetitoso para redes sociales (Instagram/WhatsApp) de este plato de un restaurante chileno. Máximo 3 oraciones. Sin hashtags. Usa un tono cálido y cercano. Plato: {$itemName}. Descripción: {$itemDescription}. Precio: $" . number_format($price, 0, ',', '.') . ' CLP.',
                ]],
            ]],
            'generationConfig' => [
                'maxOutputTokens' => 256,
                'temperature'     => 0.8,
            ],
        ]);

        return $response->json('candidates.0.content.parts.0.text', '');
    }
}
