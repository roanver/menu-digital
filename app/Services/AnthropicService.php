<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AnthropicService
{
    private string $apiKey;
    private string $visionModel = 'claude-sonnet-4-6';
    private string $copyModel   = 'claude-haiku-4-5-20251001';

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.key');
    }

    public function extractMenuFromImages(array $base64Images): array
    {
        $content = [];
        foreach ($base64Images as [$b64, $mimeType]) {
            $content[] = [
                'type'   => 'image',
                'source' => [
                    'type'       => 'base64',
                    'media_type' => $mimeType,
                    'data'       => $b64,
                ],
            ];
        }
        $content[] = [
            'type' => 'text',
            'text' => 'Esta es una foto de una carta de restaurante chileno. Extrae TODOS los platos y categorías que veas. Responde SOLO con JSON válido en este formato exacto, sin texto adicional:
{"categories":[{"name":"Nombre Categoría","items":[{"name":"Nombre Plato","description":"descripción breve o vacío","price":9900}]}]}
Los precios son enteros en pesos chilenos (CLP). Si no ves precio claro, usa 0. No incluyas $ ni puntos en los precios, solo el número entero.',
        ];

        $response = Http::withHeaders([
            'x-api-key'         => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
            'model'      => $this->visionModel,
            'max_tokens' => 4096,
            'messages'   => [
                ['role' => 'user', 'content' => $content],
            ],
        ]);

        $text = $response->json('content.0.text', '{}');
        $data = json_decode($text, true);

        return $data['categories'] ?? [];
    }

    public function generatePostCopy(string $itemName, string $itemDescription, int $price): string
    {
        $response = Http::withHeaders([
            'x-api-key'         => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
            'model'      => $this->copyModel,
            'max_tokens' => 256,
            'messages'   => [[
                'role'    => 'user',
                'content' => "Genera un copy corto y apetitoso para redes sociales (Instagram/WhatsApp) de este plato de un restaurante chileno. Máximo 3 oraciones. Sin hashtags. Usa un tono cálido y cercano. Plato: {$itemName}. Descripción: {$itemDescription}. Precio: $" . number_format($price, 0, ',', '.') . ' CLP.',
            ]],
        ]);

        return $response->json('content.0.text', '');
    }
}
