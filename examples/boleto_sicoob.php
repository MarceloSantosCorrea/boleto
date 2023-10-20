<?php

require __DIR__ . '/../vendor/autoload.php';

$oBoleto = new \Boleto\Boleto();

$banco = new \Boleto\Banco\Sicoob();
$oBoleto->setBanco($banco);

$oBoleto->setNumeroMoeda(9);
$oBoleto->setDataVencimento(DateTime::createFromFormat('d/m/Y', '30/10/2023'));
$oBoleto->setDataDocumento(DateTime::createFromFormat('d/m/Y', '19/10/2023'));
$oBoleto->setDataProcessamento(DateTime::createFromFormat('d/m/Y', '19/10/2023'));
$oBoleto->addDemostrativo('Administradora Marsane');
$oBoleto->addInstrucao("- Condomínio Edifício Arenal - Bloco A - Apt. 102");
$oBoleto->addInstrucao("- Multa de 2.00% após 10/11/2023");
$oBoleto->addInstrucao("- Juros de 1.00% ao mês");
//$oBoleto->addInstrucao("- Não receber após 30 dias do vencimento");
$oBoleto->setValorBoleto('20,00');
$oBoleto->setNossoNumero('1');
$oBoleto->setNumeroDocumento("1");

$oCedente = new \Boleto\Cedente();
$oCedente->setNome('CONDOMINIO EDIFICIO LUCILA - 91.095.620/0001-07');
$oCedente->setAgencia('4342');
$oCedente->setDvAgencia('7');
$oCedente->setConta('243394');
$oCedente->setDvConta('0');
$oCedente->setEndereco('Rua Carlos Castro, N&ordm; 245, Centro');
$oCedente->setCidade('Pinheiros');
$oCedente->setUf('ES');
$oCedente->setCpfCnpj('91095620000107');

$oBoleto->setCedente($oCedente);

$oSacado = new \Boleto\Sacado();
$oSacado->setNome('Leandro Alberto Moreira Bohrer');
$oSacado->setCpfCnpj('414.406.170-15');
$oSacado->setTipoLogradouro('Rua');
$oSacado->setEnderecoLogradouro('Felipe de Oliveira');
$oSacado->setNumeroLogradouro('500');
$oSacado->setCidade('Santa Maria');
$oSacado->setUf('Santa Maria');
$oSacado->setCep('97015-250');

$oBoleto->setSacado($oSacado);

$oGeradorBoleto = new \Boleto\GeradorBoletoSicoob();
$gerar = $oGeradorBoleto->gerar($oBoleto);
echo $gerar->Output();