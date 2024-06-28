<?php

use Boleto\Banco\Sicredi;
use Boleto\Cedente;
use Boleto\Sacado;

require __DIR__ . '/../vendor/autoload.php';

$oBoleto = new \Boleto\Boleto();

//Configurações do banco
$oBanco = new Sicredi();
$oBanco->setCarteira('A');
$oBanco->setPosto('25');
$oBanco->setByte('2');

//Dados do Boleto
$oBoleto->setBanco($oBanco);
$oBoleto->setNumeroMoeda(9);
$oBoleto->setDataVencimento(DateTime::createFromFormat('d/m/Y', "10/07/2024"));
$oBoleto->setDataDocumento(DateTime::createFromFormat('d/m/Y', "01/07/2024"));
$oBoleto->setDataProcessamento(DateTime::createFromFormat('d/m/Y', "01/07/2024"));
$oBoleto->addInstrucao('- Sr. Caixa, não receber após o vencimento');
$oBoleto->addInstrucao('- Após o vencimento cobrar mora diária de 0,33%');
//$oBoleto->addDemonstrativo("Orçamento realizado em 22/05/2017");
//$oBoleto->addDemonstrativo("Execução de serviços diversos");
$oBoleto->setValorBoleto('90,00');
$oBoleto->setNossoNumero('01085');
$oBoleto->setNumeroDocumento('233');

//Dados do Cendente
$Cedente = new Cedente();
$Cedente->setNome("Global Components");
$Cedente->setAgencia("0434");
$Cedente->setDvAgencia("0");
$Cedente->setConta("11448");
$Cedente->setDvConta("9");
$Cedente->setEndereco("Rua Carlos Castro, N&ordm; 245, Centro");
$Cedente->setCidade("Pinheiros");
$Cedente->setUf("SC");
$Cedente->setCpfCnpj("51.246.337/0001-14");
$Cedente->setCodigoCedente("91107");
$oBoleto->setCedente($Cedente);

//Dados do Sacado
$Sacado = new Sacado();
$Sacado->setNome("Marcos da Silva");
$Sacado->setCpfCnpj('414.406.170-15');
$Sacado->setTipoLogradouro("Rua");
$Sacado->setEnderecoLogradouro("Av Prefeiro Jose Da Silva");
$Sacado->setNumeroLogradouro("100");
$Sacado->setCidade("São Vicente");
$Sacado->setUf("SP");
$Sacado->setCep("11380-000");
$oBoleto->setSacado($Sacado);

//Gera nosso número padrão sicredi
$oBanco->setNossoNumeroFormatado($oBoleto);

//Gera boleto em PDF
$oGeradorBoleto = new \Boleto\GeradorBoletoSicredi();
$gerar = $oGeradorBoleto->gerar($oBoleto);
echo $gerar->Output();
