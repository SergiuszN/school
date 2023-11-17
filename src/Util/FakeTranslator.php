<?php

namespace App\Util;

use Symfony\Contracts\Translation\TranslatorInterface;

class FakeTranslator implements TranslatorInterface
{
    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        return $id;
    }

    public function getLocale(): string
    {
        return 'en';
    }
}