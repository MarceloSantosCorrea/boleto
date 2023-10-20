<?php

test('deve testar os getters e setters', function () {
    $sacado = new \Boleto\Sacado();
    $sacado->setNome('Marcelo Corrêa');
    $sacado->setCpfCnpj('279.915.550-20');
    $sacado->setCep('97037-172');
    $sacado->setTipoLogradouro('Rua');
    $sacado->setEnderecoLogradouro('Apóstolo Lucas');
    $sacado->setNumeroLogradouro('295');
    $sacado->setBairro('Nova Santa Marta');
    $sacado->setCidade('Santa Maria');
    $sacado->setUf('RS');

    expect($sacado->getNome())->toEqual('Marcelo Corrêa')
        ->and($sacado->getCpfCnpj())->toEqual('279.915.550-20')
        ->and($sacado->getCep())->toEqual('97037-172')
        ->and($sacado->getTipoLogradouro())->toEqual('Rua')
        ->and($sacado->getEnderecoLogradouro())->toEqual('Apóstolo Lucas')
        ->and($sacado->getNumeroLogradouro())->toEqual('295')
        ->and($sacado->getBairro())->toEqual('Nova Santa Marta')
        ->and($sacado->getCidade())->toEqual('Santa Maria')
        ->and($sacado->getUf())->toEqual('RS');
});
