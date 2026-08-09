<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AnthropicService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl = 'https://api.anthropic.com/v1/messages';

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.key', '');
        $this->model  = config('services.anthropic.model_vision', 'claude-sonnet-4-5');
    }

    public function extractMenuFromImages(array $base64Images): array
    {
        if (empty($this->apiKey)) {
            throw new \RuntimeException('ANTHROPIC_API_KEY no está configurada.');
        }

        $content = [];

        foreach ($base64Images as [$b64, $mimeType]) {
            $content[] = [
                'type'   => 'image',
                'source' => [
                    'type'       => 'base64',
                    'media_type' => $mimeType ?: 'image/jpeg',
                    'data'       => $b64,
                ],
            ];
        }

        $content[] = [
            'type' => 'text',
            'text' => 'Esta es una foto de una carta de restaurante chileno. Extrae TODOS los platos y categorías que veas. Responde SOLO con JSON válido en este formato exacto, sin texto adicional ni bloques de código:
{"categories":[{"name":"Nombre Categoría","items":[{"name":"Nombre Plato","description":"descripción breve o vacío","price":9900}]}]}
Los precios son enteros en pesos chilenos (CLP). Si no ves precio claro, usa 0. No incluyas $ ni puntos en los precios, solo el número entero.',
        ];

        $response = Http::withHeaders([
            'x-api-key'         => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->timeout(60)->post($this->baseUrl, [
            'model'      => $this->model,
            'max_tokens' => 4096,
            'messages'   => [
                [
                    'role'    => 'user',
                    'content' => $content,
                ],
            ],
        ]);

        // Error HTTP de la API
        if ($response->failed()) {
            $status = $response->status();
            $error  = $response->json('error.message', 'Sin detalle');

            if ($status === 401) {
                throw new \RuntimeException('ANTHROPIC_API_KEY inválida o sin permisos.');
            }

            if ($status === 429) {
                throw new \RuntimeException('Límite de requests de Anthropic alcanzado. Intenta en unos minutos.');
            }

            throw new \RuntimeException("Error Anthropic ({$status}): {$error}");
        }

        $text = $response->json('content.0.text', '');

        if (empty($text)) {
            $stopReason = $response->json('stop_reason', '');
            throw new \RuntimeException("Anthropic no devolvió texto. Stop reason: {$stopReason}. Respuesta: " . $response->body());
        }

        // Limpiar posibles bloques ```json ... ```
        $text = preg_replace('/^```json\s*/m', '', $text);
        $text = preg_replace('/^```\s*/m', '', $text);
        $text = trim($text);

        $data = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('JSON inválido de Anthropic: ' . mb_substr($text, 0, 200));
        }

        return $data['categories'] ?? [];
    }

    /**
     * Analyze the restaurant's menu and return an array of improvement suggestions.
     *
     * @param  array  $menuData  Array of categories with their items (name, price, description, is_available)
     * @return array{score: int, suggestions: list<array{priority: string, type: string, title: string, body: string}>}
     */
    public function analyzeMenu(array $menuData): array
    {
        if (empty($this->apiKey)) {
            throw new \RuntimeException('ANTHROPIC_API_KEY no está configurada.');
        }

        $menuText = '';
        foreach ($menuData as $category) {
            $menuText .= "\n## {$category['name']}\n";
            foreach ($category['items'] as $item) {
                $price  = $item['price'] > 0 ? '$' . number_format($item['price'], 0, ',', '.') : 'sin precio';
                $avail  = $item['is_available'] ? '' : ' [NO DISPONIBLE]';
                $desc   = $item['description'] ? ' — ' . $item['description'] : '';
                $menuText .= "- {$item['name']}{$avail}: {$price}{$desc}\n";
            }
        }

        $prompt = 'Eres un experto en restaurantes chilenos y marketing gastronómico. Analiza esta carta digital y entrega sugerencias concretas para mejorarla.

CARTA:
' . $menuText . '

Responde SOLO con JSON válido (sin bloques de código), en este formato exacto:
{
  "score": <número entre 0 y 100 que refleja la calidad general de la carta>,
  "suggestions": [
    {
      "priority": "alta|media|baja",
      "type": "precios|descripciones|disponibilidad|variedad|categorias|marketing",
      "title": "Título corto de la sugerencia (máximo 60 caracteres)",
      "body": "Explicación concreta de qué mejorar y cómo hacerlo (2-4 oraciones)"
    }
  ]
}

Entrega entre 4 y 8 sugerencias. Sé específico y accionable. Considera precios del mercado chileno, popularidad de platos, coherencia del menú y oportunidades de venta.';

        $response = Http::withHeaders([
            'x-api-key'         => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->timeout(45)->post($this->baseUrl, [
            'model'      => config('services.anthropic.model_text', 'claude-haiku-4-5-20251001'),
            'max_tokens' => 2048,
            'messages'   => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        if ($response->failed()) {
            $status = $response->status();
            $error  = $response->json('error.message', 'Sin detalle');

            if ($status === 401) {
                throw new \RuntimeException('ANTHROPIC_API_KEY inválida o sin permisos.');
            }
            if ($status === 429) {
                throw new \RuntimeException('Límite de requests de Anthropic alcanzado. Intenta en unos minutos.');
            }

            throw new \RuntimeException("Error Anthropic ({$status}): {$error}");
        }

        $text = $response->json('content.0.text', '');

        if (empty($text)) {
            throw new \RuntimeException('Anthropic no devolvió respuesta para el análisis de carta.');
        }

        $text = preg_replace('/^```json\s*/m', '', $text);
        $text = preg_replace('/^```\s*/m', '', $text);
        $text = trim($text);

        $data = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('JSON inválido de Anthropic: ' . mb_substr($text, 0, 200));
        }

        return [
            'score'       => (int) ($data['score'] ?? 0),
            'suggestions' => $data['suggestions'] ?? [],
        ];
    }
}
