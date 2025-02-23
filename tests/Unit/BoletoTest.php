<?php

test('deve testar o fator de vencimento', function ($date, $fatorVencimento) {
    $boleto  = new \Boleto\Boleto();
    $boleto->setDataVencimento(new DateTime($date));

    expect($boleto->getFatorVencimento())->toEqual($fatorVencimento);
})->with([
    ['2025-02-22', 1000],
    ['2025-02-23', 1001],
    ['2025-02-24', 1002],
    ['2025-03-10', 1016],
]);
