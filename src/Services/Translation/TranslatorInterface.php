<?php

namespace Stronger21012\Autotranslator\Services\Translation;

interface TranslatorInterface
{
    public function translate(string $text, string $from, string $to): string;
}