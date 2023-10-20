<?php

test('deve testar getters e setters', function () {
    $cedente = new \Boleto\Cedente();
    $cedente->setNome('Marcelo Corrêa');
    $cedente->setCpfCnpj('279.915.550-20');
    $cedente->setEndereco('Rua Apóstolo Lucas, 295');
    $cedente->setCidade('Samta Maria');
    $cedente->setUf('RS');
    $cedente->setAgencia('0501');
    $cedente->setDvAgencia('0');
    $cedente->setConta('1234567');
    $cedente->setDvConta('1');

    expect($cedente->getNome())->toEqual('Marcelo Corrêa')
        ->and($cedente->getCpfCnpj())->toEqual('279.915.550-20')
        ->and($cedente->getEndereco())->toEqual('Rua Apóstolo Lucas, 295')
        ->and($cedente->getCidade())->toEqual('Samta Maria')
        ->and($cedente->getUf())->toEqual('RS')
        ->and($cedente->getAgencia())->toEqual('0501')
        ->and($cedente->getDvAgencia())->toEqual('0')
        ->and($cedente->getConta())->toEqual('1234567')
        ->and($cedente->getDvConta())->toEqual('1')
        ->and($cedente->getContaComDv())->toEqual('1234567-1');
});
