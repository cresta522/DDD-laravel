<?php

namespace App\Core;

use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Str;

class Hash extends FacadesHash
{
    public static function makeToUrl(string $value): string
    {
        return str_replace('/', '', parent::make($value));
    }

    public static function makeRandom(): string
    {
        return str_replace('/', '', parent::make(Str::random()));
    }
}
