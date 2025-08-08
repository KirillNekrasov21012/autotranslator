<?php

namespace Stronger21012\Autotranslator\Services\Translation;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Support\Facades\Http;

class GoogleTranslationService implements TranslatorInterface
{
    protected string $apiKey;
    protected string $url;

    public function __construct(Config $config)
    {
        $this->apiKey = $config->get('autotranslator.google.api_key');
        $this->url = $config->get('autotranslator.google.url');
    }

    public function translate(?string $text, ?string $from, ?string $to): string
    {
        $response = Http::get($this->url, [
            'q' => $text,
            'source' => $from,
            'target' => $to,
            'format' => 'text',
            'key' => $this->apiKey,
        ]);

        return $response->json('data.translations.0.translatedText') ?? $text;
    }
}
