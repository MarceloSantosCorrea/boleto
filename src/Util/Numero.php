<?php

namespace Boleto\Util;

class Numero
{
    /** @return string Número formatado */
    public static function formataNumero($numero, $loop, $insert, $tipo = 'geral'): string
    {
        switch ($tipo) {
            case 'valor':
            case 'geral':
                $numero = str_replace(",", "", $numero);
                while (strlen($numero) < $loop) {
                    $numero = $insert . $numero;
                }
                break;
            case 'convenio':
                while (strlen($numero) < $loop) {
                    $numero = $numero . $insert;
                }
                break;
        }

        return $numero;
    }
} 