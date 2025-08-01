<?php

namespace Stronger21012\Autotranslator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository as Config;
use Stronger21012\Autotranslator\Services\TranslatorInterface;
use Stronger21012\Autotranslator\Services\GoogleTranslationService;
use Stronger21012\Autotranslator\Services\YandexTranslationService;

class AutoTranslatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/autotranslator.php', 'autotranslator');

        $this->app->bind(TranslatorInterface::class, function ($app) {
            $config = $app->make(Config::class);
            $driver = $config->get('autotranslator.driver', 'yandex');

            return match ($driver) {
                'google' => new GoogleTranslationService($config),
                'yandex' => new YandexTranslationService($config),
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
    }
}