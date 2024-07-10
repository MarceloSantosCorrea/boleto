<?php

use Boleto\Util\Substr;

test('deve retornar números pela esquerda', function ($entra, $comp, $esperado) {
    expect(Substr::esquerda($entra, $comp))->toEqual($esperado);
})->with([
    ['12345', 2, '12'],
    ['12345', 5, '12345'],
    ['12345', 7, '12345'],
    ['12345', 0, ''],
    ['12345', 10, '12345'],
]);

test('deve retornar números pela direita', function ($entra, $comp, $esperado) {
    expect(Substr::direita($entra, $comp))->toEqual($esperado);
})->with([
    ['12345', 2, '45'],
    ['12345', 5, '12345'],
    ['123456789', 7, '3456789'],
    ['12345', 0, ''],
]);
