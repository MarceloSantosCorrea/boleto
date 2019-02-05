<?php

require __DIR__ . '/../vendor/autoload.php';

$oBoleto = new \Boleto\Boleto();

$oBoleto->setBanco(new \Boleto\Banco\Caixa());

$oBoleto->setNumeroMoeda("9");
$oBoleto->setDataVencimento(DateTime::createFromFormat('d/m/Y', "08/09/2017"));
$oBoleto->setDataDocumento(DateTime::createFromFormat('d/m/Y', "08/09/2017"));
$oBoleto->setDataProcessamento(DateTime::createFromFormat('d/m/Y', "08/09/2017"));
$oBoleto->addDemostrativo('Pagamento de Compra na Móveis Simonetti');
$oBoleto->addInstrucao("- Condomínio Edifício Arenal - Bloco A - Apt. 102 - ref 08/2017");
$oBoleto->addInstrucao("- Multa de 2.00% após 09/09/2017");
$oBoleto->addInstrucao("- Juros de 1.00% ao mês");
//$oBoleto->addInstrucao("- Não receber após 30 dias do vencimento");
$oBoleto->setValorBoleto("215,76");
$oBoleto->setNossoNumero("000000000000002");
$oBoleto->setNumeroDocumento("233");

$oCedente = new \Boleto\Cedente();
$oCedente->setNome("MARLENE BURGEL & CIA LTDA - ME");
$oCedente->setAgencia("0501");
$oCedente->setDvAgencia("0");
$oCedente->setConta("721933");
$oCedente->setDvConta("4");
$oCedente->setEndereco("Rua Carlos Castro, N&ordm; 245, Centro");
$oCedente->setCidade("Pinheiros");
$oCedente->setUf("ES");
$oCedente->setCpfCnpj("128.588.555-13");

$oBoleto->setCedente($oCedente);

$oSacado = new \Boleto\Sacado();
$oSacado->setNome("Leandro Alberto Moreira Bohrer");
$oSacado->setCpfCnpj("414.406.170-15");
$oSacado->setTipoLogradouro("Rua");
$oSacado->setEnderecoLogradouro("Felipe de Oliveira");
$oSacado->setNumeroLogradouro("500");
$oSacado->setCidade("Santa Maria");
$oSacado->setUf("Santa Maria");
$oSacado->setCep("97015-250");

$oBoleto->setSacado($oSacado);

$oGeradorBoleto = new \Boleto\GeradorBoleto();
$gerar          = $oGeradorBoleto->gerar($oBoleto);
echo $gerar->Output();