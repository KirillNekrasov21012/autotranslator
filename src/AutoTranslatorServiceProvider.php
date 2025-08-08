<?php

namespace Stronger21012\Autotranslator;

use ApiKeyGeneration;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository as Config;
use InstallLibreTranslate;
use Stronger21012\Autotranslator\Services\Translation\TranslatorInterface;
use Stronger21012\Autotranslator\Services\Translation\GoogleTranslationService;
use Stronger21012\Autotranslator\Services\Translation\LibreTranslationService;
use Stronger21012\Autotranslator\Services\Translation\YandexTranslationService;

class AutoTranslatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/autotranslator.php', 'autotranslator');

        $this->app->bind(TranslatorInterface::class, function ($app) {
            $config = $app->make(Config::class);
            $driver = $config->get('autotranslator.driver', 'libretranslate');

            return match ($driver) {
                'google' => new GoogleTranslationService($config),
                'yandex' => new YandexTranslationService($config),
                'libretranslate' => new LibreTranslationService($config),
                default => new YandexTranslationService($config),
            };
        });
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->publishes([
            __DIR__.'/../config/autotranslator.php' => $this->app->configPath('autotranslator.php'),
        ], 'config');
        $this->commands([
            InstallLibreTranslate::class,
            ApiKeyGeneration::class,
        ]);
    }
}