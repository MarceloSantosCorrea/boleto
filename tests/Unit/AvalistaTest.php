<?php

test('deve testar getters e setters', function () {
    $avalista = new \Boleto\Avalista(
        'Marcelo Corrêa',
        '279.915.550-20'
    );
    expect($avalista->getNome())->toEqual('Marcelo Corrêa')
        ->and($avalista->getCpfCnpj())->toEqual('279.915.550-20');
});
