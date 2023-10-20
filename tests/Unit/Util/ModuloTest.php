<?php

test('deve calcular e retornar o dígito verificador com as regras do módulo 11', function (string $value, int $result) {
    $dv = \Boleto\Util\Modulo::modulo10($value);
    expect($dv)->toEqual($result);
})->with([
    ['1', 8],
]);

test('deve calcular e retornar o dígito verificador com as regras do módulo 10', function (string $value, int $result) {
    $dv = \Boleto\Util\Modulo::modulo10($value);
    expect($dv)->toEqual($result);
})->with([
    ['001905009', 5],
    ['4014481606', 9],
    ['0680935031', 4],
]);


