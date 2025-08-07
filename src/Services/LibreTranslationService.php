<?php

namespace Stronger21012\Autotranslator\Services;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Support\Facades\Http;

class LibreTranslationService implements TranslatorInterface
{
    protected string $apiKey;
    protected string $url;

    public function __construct(Config $config)
    {
        $this->apiKey = $config->get('autotranslator.libretranslate.api_key');
        $this->url = $config->get('autotranslator.libretranslate.url');
    }

    public function translate(string $text, string $from, string $to): string
    {
        $response = Http::post($this->url . '/translate', [
            'q' => $text,
            'source' => $from,
            'target' => $to,
            'format' => 'text',
            'api_key' => $this->apiKey,
        ]);

        return $response->json('translatedText') ?? '';
    }
}
