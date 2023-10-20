<?php

test('deve retornar o nosso número formatado e com dígito verificador', function (int $sequencialNossoNumero, string $result) {
    $oBoleto = new \Boleto\Boleto();
    $banco = new \Boleto\Banco\Sicoob();
    $banco->setCarteira('1');
    $banco->setCarteiraModalidade(1);
    $oBoleto->setBanco($banco);
    $oBoleto->setNumeroMoeda(9);
    $oBoleto->setValorBoleto('3,00');
    $oBoleto->setNossoNumero($sequencialNossoNumero);

    $oCedente = new \Boleto\Cedente();
    $oCedente->setAgencia('4342');
    $oCedente->setDvAgencia('7');
    $oCedente->setConta('243394');
    $oCedente->setDvConta('0');

    $oBoleto->setCedente($oCedente);

    $response = $banco->getNossoNumeroComDigitoVerificador($oBoleto);
    expect($response)->toEqual($result);
})->with([
    [1, '00000010'],
    [2, '00000027'],
    [3, '00000034'],
    [4, '00000041'],
    [5, '00000059'],
    [6, '00000066'],
    [7, '00000073'],
    [8, '00000080'],
    [9, '00000098'],
    [10, '00000106']
]);

test('deve retornar o nosso número formatado sem o dígito verificador', function (int $sequencialNossoNumero, string $result) {
    $oBoleto = new \Boleto\Boleto();
    $banco = new \Boleto\Banco\Sicoob();
    $banco->setCarteira('1');
    $banco->setCarteiraModalidade(1);
    $oBoleto->setBanco($banco);
    $oBoleto->setNumeroMoeda(9);
    $oBoleto->setValorBoleto('3,00');
    $oBoleto->setNossoNumero($sequencialNossoNumero);

    $oCedente = new \Boleto\Cedente();
    $oCedente->setAgencia('4342');
    $oCedente->setDvAgencia('7');
    $oCedente->setConta('243394');
    $oCedente->setDvConta('0');

    $oBoleto->setCedente($oCedente);

    $response = $banco->getNossoNumeroSemDigitoVerificador($oBoleto);
    expect($response)->toEqual($result);
})->with([[1, '0000001'], [2, '0000002'], [3, '0000003'], [4, '0000004'], [5, '0000005'], [6, '0000006'], [7, '0000007'], [8, '0000008'], [9, '0000009'], [10, '0000010']]);

test('deve retornar a regra do campo livre', function (int $sequencialNossoNumero, string $result) {
    $oBoleto = new \Boleto\Boleto();
    $banco = new \Boleto\Banco\Sicoob();
    $banco->setCarteira('1');
    $banco->setCarteiraModalidade(1);
    $oBoleto->setBanco($banco);
    $oBoleto->setNumeroMoeda(9);
    $oBoleto->setValorBoleto('3,00');
    $oBoleto->setNossoNumero($sequencialNossoNumero);

    $oCedente = new \Boleto\Cedente();
    $oCedente->setAgencia('4342');
    $oCedente->setDvAgencia('7');
    $oCedente->setConta('243394');
    $oCedente->setDvConta('0');

    $oBoleto->setCedente($oCedente);

    $response = $banco->getCampoLivre($oBoleto);
    expect($response)->toEqual($result);
})->with([
    [1, '1434201243394000000010001'],
    [2, '1434201243394000000027001'],
    [3, '1434201243394000000034001'],
    [4, '1434201243394000000041001'],
    [5, '1434201243394000000059001'],
    [6, '1434201243394000000066001'],
    [7, '1434201243394000000073001'],
    [8, '1434201243394000000080001'],
    [9, '1434201243394000000098001'],
    [10, '1434201243394000000106001']
]);

test('deve retornar a sequência o cálculo do dígito verificador do nosso número', function (int $sequencialNossoNumero, string $result) {
    $oBoleto = new \Boleto\Boleto();
    $banco = new \Boleto\Banco\Sicoob();
    $banco->setCarteira('1');
    $banco->setCarteiraModalidade(1);
    $oBoleto->setBanco($banco);
    $oBoleto->setNumeroMoeda(9);
    $oBoleto->setValorBoleto('3,00');
    $oBoleto->setNossoNumero($sequencialNossoNumero);

    $oCedente = new \Boleto\Cedente();
    $oCedente->setAgencia('4342');
    $oCedente->setDvAgencia('7');
    $oCedente->setConta('243394');
    $oCedente->setDvConta('0');

    $oBoleto->setCedente($oCedente);

    $response = $banco->getSequenciaCalculoDigitoVerificadorNossoNumero($oBoleto);
    expect($response)->toEqual($result);
})->with([
    [1, '434200024339400000001'],
    [2, '434200024339400000002'],
    [3, '434200024339400000003'],
    [4, '434200024339400000004'],
    [5, '434200024339400000005'],
    [6, '434200024339400000006'],
    [7, '434200024339400000007'],
    [8, '434200024339400000008'],
    [9, '434200024339400000009'],
    [10, '434200024339400000010']
]);

test('deve calcular e retornar o dígito verificador do nosso número', function (int $sequencialNossoNumero, int $result) {
    $oBoleto = new \Boleto\Boleto();
    $banco = new \Boleto\Banco\Sicoob();
    $banco->setCarteira('1');
    $banco->setCarteiraModalidade(1);
    $oBoleto->setBanco($banco);
    $oBoleto->setNumeroMoeda(9);
    $oBoleto->setValorBoleto('3,00');
    $oBoleto->setNossoNumero($sequencialNossoNumero);

    $oCedente = new \Boleto\Cedente();
    $oCedente->setAgencia('4342');
    $oCedente->setDvAgencia('7');
    $oCedente->setConta('243394');
    $oCedente->setDvConta('0');

    $oBoleto->setCedente($oCedente);

    $response = $banco->digitoVerificadorNossonumero(
        $banco->getSequenciaCalculoDigitoVerificadorNossoNumero($oBoleto)
    );
    expect($response)->toEqual($result);
})->with([
    [1, 0],
    [2, 7],
    [3, 4],
    [4, 1],
    [5, 9],
    [6, 6],
    [7, 3],
    [8, 0],
    [9, 8],
    [10, 6],
]);

test('deve retornar o nosso número com dígito verificador formatado para o boleto', function (int $sequencialNossoNumero, string $result) {
    $oBoleto = new \Boleto\Boleto();
    $banco = new \Boleto\Banco\Sicoob();
    $banco->setCarteira('1');
    $banco->setCarteiraModalidade(1);
    $oBoleto->setBanco($banco);
    $oBoleto->setNumeroMoeda(9);
    $oBoleto->setValorBoleto('3,00');
    $oBoleto->setNossoNumero($sequencialNossoNumero);

    $oCedente = new \Boleto\Cedente();
    $oCedente->setAgencia('4342');
    $oCedente->setDvAgencia('7');
    $oCedente->setConta('243394');
    $oCedente->setDvConta('0');

    $oBoleto->setCedente($oCedente);

    $response = $banco->getCarteiraENossoNumeroComDigitoVerificador($oBoleto);
    expect($response)->toEqual($result);
})->with([
    [1, '0000001-0'],
    [2, '0000002-7'],
    [3, '0000003-4'],
    [4, '0000004-1'],
    [5, '0000005-9'],
    [6, '0000006-6'],
    [7, '0000007-3'],
    [8, '0000008-0'],
    [9, '0000009-8'],
    [10, '0000010-6']
]);

test('deve retornar a linha com os dados para a geração dos dados da linha digitável para o boleto', function (int $sequencialNossoNumero, string $result) {
    $oBoleto = new \Boleto\Boleto();
    $banco = new \Boleto\Banco\Sicoob();
    $banco->setCarteira('1');
    $banco->setCarteiraModalidade(1);
    $oBoleto->setBanco($banco);
    $oBoleto->setNumeroMoeda(9);
    $oBoleto->setDataVencimento(DateTime::createFromFormat('d/m/Y', '10/10/2023'));
    $oBoleto->setValorBoleto('3,00');
    $oBoleto->setNossoNumero($sequencialNossoNumero);

    $oCedente = new \Boleto\Cedente();
    $oCedente->setAgencia('4342');
    $oCedente->setDvAgencia('7');
    $oCedente->setConta('243394');
    $oCedente->setDvConta('0');

    $oBoleto->setCedente($oCedente);

    $response = $banco->getLinha($oBoleto);
    expect($response)->toEqual($result);
})->with([
    [2, '75693949900000003001434201243394000000027001'],
]);

test('deve retornar o dígito verificador geral', function (int $sequencialNossoNumero, int $result) {
    $banco = new \Boleto\Banco\Sicoob();
    $class = new \ReflectionClass(get_class($banco));
    $method = $class->getMethod('tratarRestoDigitoVerificadorGeral');
    $response = $method->invokeArgs($banco, [$sequencialNossoNumero]);

    expect($response)->toEqual($result);
})->with([
    [0, 1],
    [1, 1],
    [2, 9],
    [3, 8],
    [4, 7],
    [5, 6],
    [6, 5],
    [7, 4],
    [8, 3],
    [9, 2],
    [10, 1],
    [11, 1],
]);

