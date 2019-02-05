<?php

require __DIR__ . '/../vendor/autoload.php';

$oBoleto = new \Boleto\Boleto();

$oBoleto->setBanco(new \Boleto\Banco\Itau());

$oBoleto->setNumeroMoeda("9");
$oBoleto->setDataVencimento(DateTime::createFromFormat('d/m/Y', "10/11/2017"));
$oBoleto->setDataDocumento(DateTime::createFromFormat('d/m/Y', "03/02/2019"));
$oBoleto->setDataProcessamento(DateTime::createFromFormat('d/m/Y', "03/02/2019"));
$oBoleto->addDemostrativo('Pagamento de Compra na Móveis Simonetti');
$oBoleto->addInstrucao("- Condominio Edificio Morada do Parque - Bloco A - APT° 101 - ref 10/2017");
$oBoleto->addInstrucao("- Multa de 2.00% após 11/11/2017");
$oBoleto->addInstrucao("- Juros de 1.00% ao mês");
$oBoleto->setValorBoleto("455,85");
$oBoleto->setNossoNumero("11047423");
$oBoleto->setNumeroDocumento("1444");

$oCedente = new \Boleto\Cedente();
$oCedente->setNome("COND. ED. MORADA DO PARQUE");
$oCedente->setAgencia("0330");
$oCedente->setDvAgencia("0");
$oCedente->setConta("53085");
$oCedente->setDvConta("8");
$oCedente->setEndereco("Rua Carlos Castro, N&ordm; 245, Centro");
$oCedente->setCidade("Pinheiros");
$oCedente->setUf("ES");
$oCedente->setCpfCnpj("128.588.555-13");

$oBoleto->setCedente($oCedente);

$oSacado = new \Boleto\Sacado();
$oSacado->setNome("Marli Minuzzi");
$oSacado->setCpfCnpj("303.249.040-53");
$oSacado->setTipoLogradouro("Avenida");
$oSacado->setEnderecoLogradouro("Itaimbe");
$oSacado->setNumeroLogradouro("221");
$oSacado->setCidade("Santa Maria");
$oSacado->setUf("Santa Maria");
$oSacado->setCep("97050-331");

$oBoleto->setSacado($oSacado);

$oGeradorBoleto = new \Boleto\GeradorBoleto();
$gerar          = $oGeradorBoleto->gerar($oBoleto);
echo $gerar->Output();