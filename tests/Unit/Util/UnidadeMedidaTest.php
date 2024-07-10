<?php

use Boleto\Util\UnidadeMedida;

test('deve converter px em mílimetros', function ($px, $esperado) {
    expect(UnidadeMedida::px2milimetros($px))->toEqual($esperado);
})->with([
    [1, 0.26458333333333334],
    [2.3, 0.6085416666666666],
    [40, 10.583333333333334],
]);
