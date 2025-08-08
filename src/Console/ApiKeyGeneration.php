<?php

use Illuminate\Console\Command;
use Stronger21012\Autotranslator\Services\Generation\ApiKeyGenerationService;

class ApiKeyGeneration extends Command
{
    protected $signature = 'autotranslator:generate-api-key-libretranslate';
    protected $description = 'Generate API key';
    protected ApiKeyGenerationService $apiKeyGeneration;

    public function __construct(
        ApiKeyGenerationService $apiKeyGeneration
    ) {
        parent::__construct();
        $this->apiKeyGeneration = $apiKeyGeneration;
    }

    public function handle(): void
    {
        $apiKey = $this->apiKeyGeneration->generate();
        $this->info("\n==========================\n");
        $this->line("LIBRETRANSLATE_API_KEY={$apiKey}");
        $this->info("\n==========================\n");
    }
}
