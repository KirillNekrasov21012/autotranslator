<?php

namespace Stronger21012\Autotranslator\Helper;

use Stronger21012\Autotranslator\Services\TranslatorInterface;

class AutoTranslate
{
    public function __construct(
        protected TranslatorInterface $translator
    ) {
        //
    }

    public function translateIfMissing(array &$data, string $fieldFrom, string $fieldTo, string $fromLang, string $toLang): void
    {
        if (!empty($data[$fieldFrom]) && empty($data[$fieldTo])) {
            $data[$fieldTo] = $this->translator->translate($data[$fieldFrom], $fromLang, $toLang);
        }
    }
}