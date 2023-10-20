<?php

use Boleto\Util\Numero;

test('deve retornar o número formatado de acordo com o parâmetro tipo passado', function (
    float  $valorBoleto,
    int    $loop,
    int    $insert,
    string $tipo,
    string $valorFormatado
) {
    $result = Numero::formataNumero($valorBoleto, $loop, $insert, $tipo);

    expect($result)->toEqual($valorFormatado);
})->with([
    [123.4, 10, 0, 'valor', '00000123.4'],
    [1, 1, 0, 'geral', '1'],
    [1, 11, 0, 'geral', '00000000001'],
    [1, 5, 0, 'convenio', '10000'],
]);
