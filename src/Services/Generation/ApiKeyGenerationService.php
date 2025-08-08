<?php

namespace Stronger21012\Autotranslator\Services\Generation;

class ApiKeyGenerationService 
{
        public function generate(int $length = 64): string
    {
        return hash('sha256', bin2hex(random_bytes($length)));
    }
}