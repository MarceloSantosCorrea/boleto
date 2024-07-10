<?php

namespace Boleto\Util;

class Substr
{
    public static function esquerda($entra, $comp): string
    {
        return substr($entra, 0, $comp);
    }

    public static function direita($entra, $comp): string
    {
        return substr($entra, strlen($entra) - $comp, $comp);
    }
}