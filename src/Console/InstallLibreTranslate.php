<?php

namespace Stronger21012\Autotranslator\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Stronger21012\Autotranslator\Services\Generation\ApiKeyGenerationService;

class InstallLibreTranslate extends Command
{
    protected $signature = 'autotranslator:install-libretranslate';
    protected $description = 'Publish docker-compose for LibreTranslate and generate API key';
    protected Application $app;
    protected ApiKeyGenerationService $apiKeyGeneration;

    public function __construct(
        Application $app,
        ApiKeyGenerationService $apiKeyGeneration
    ) {
        parent::__construct();
        $this->app = $app;
        $this->apiKeyGeneration = $apiKeyGeneration;
    }

    public function handle(): void
    {
        $filesystem = new Filesystem();

        $target = $this->app->basePath('docker-compose.libretranslate.yml');
        $stub = __DIR__ . '/../../resources/stubs/docker-compose.libretranslate.stub.yml';

        $apiKey = $this->apiKeyGeneration->generate();
        if (! $filesystem->exists($target)) {
            $content = str_replace('__API_KEY__', $apiKey, file_get_contents($stub));
            $filesystem->put($target, $content);
            $this->info('docker-compose.libretranslate.yml published.');
        } else {
            $this->warn('File already exists: ' . $target);
        }

        $this->info("\n=== Setup Instructions ===\n");
        $this->info("1. Add the following lines to your .env file:");
        $this->line("   LIBRETRANSLATE_API_KEY={$apiKey}");
        $this->line("   AUTO_TRANSLATOR_DRIVER=libretranslate");
        $this->info("\n2. Start the LibreTranslate container:");
        $this->line("   docker-compose -f docker-compose.libretranslate.yml up -d");
        $this->info("\n3. Make sure your app uses the 'libretranslate' driver in autotranslator config.");
        $this->info("\n==========================\n");
    }
}
