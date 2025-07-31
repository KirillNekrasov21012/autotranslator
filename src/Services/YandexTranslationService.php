<?php

namespace Stronger21012\Autotranslator\Services;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Support\Facades\Http;

class YandexTranslationService implements TranslatorInterface
{
    protected string $apiKey;
    protected string $url;
    protected string $folderId;

    public function __construct(Config $config)
    {
        $this->apiKey = $config->get('autotranslator.yandex.api_key');
        $this->url = $config->get('autotranslator.yandex.url');
        $this->folderId = $config->get('autotranslator.yandex.folder_id');
    }

    public function translate(string $text, string $from, string $to): string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Api-Key ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->url, [
            'folder_id' => $this->folderId,
            'texts' => [$text],
            'sourceLanguageCode' => $from,
            'targetLanguageCode' => $to,
        ]);

        if (! $response->successful()) {
            throw new \Exception('Yandex API error: ' . $response->body());
        }

        return $response->json('translations.0.text') ?? $text;
    }
}
