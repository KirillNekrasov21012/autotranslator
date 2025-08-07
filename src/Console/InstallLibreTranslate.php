<?php

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;

class InstallLibreTranslate extends Command
{
    protected $signature = 'autotranslator:install-libretranslate';
    protected $description = 'Publish docker-compose for LibreTranslate and generate API key';
    protected Application $app;
    protected string $appKey;

    public function __construct(
        Application $app,
        Config $config
    ) {
        parent::__construct();
        $this->app = $app;
        $this->appKey = $config->get('app.key');
    }

    public function handle(): void
    {
        $filesystem = new Filesystem();

        $target = $this->app->basePath('docker-compose.libretranslate.yml');
        $stub = __DIR__ . '/../../resources/stubs/docker-compose.libretranslate.stub.yml';

        if (!$this->appKey) {
            $this->error('APP_KEY is not set. Please generate APP_KEY first.');
            return;
        }

        $apiKey = hash('sha256', $this->appKey . bin2hex(random_bytes(16)));
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
