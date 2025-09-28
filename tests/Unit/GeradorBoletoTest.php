<?php

use Boleto\Boleto;
use Boleto\Banco\Bradesco;
use Boleto\Banco\Sicoob;
use Boleto\Banco\Caixa;
use Boleto\Cedente;
use Boleto\Sacado;
use Boleto\GeradorBoleto;

function criarBoletoTeste(): Boleto
{
    $cedente = new Cedente();
    $cedente->setNome("Loja Teste Ltda");
    $cedente->setAgencia("1859");
    $cedente->setDvAgencia("7");
    $cedente->setConta("3176");
    $cedente->setDvConta("3");
    $cedente->setEndereco("Rua Teste, 123");
    $cedente->setCidade("São Paulo");
    $cedente->setUf("SP");
    $cedente->setCpfCnpj("12.345.678/0001-90");

    $sacado = new Sacado();
    $sacado->setNome("Cliente Teste");
    $sacado->setTipoLogradouro("Rua");
    $sacado->setEnderecoLogradouro("Endereço Teste");
    $sacado->setNumeroLogradouro("456");
    $sacado->setCidade("São Paulo");
    $sacado->setUf("SP");
    $sacado->setCep("01234-567");
    $sacado->setCpfCnpj("123.456.789-00");

    $banco = new Bradesco();
    $banco->setAceite("N");

    $boleto = new Boleto();
    $boleto->setBanco($banco);
    $boleto->setNumeroMoeda(9);
    $boleto->setDataVencimento(DateTime::createFromFormat('d/m/Y', "01/12/2024"));
    $boleto->setDataDocumento(DateTime::createFromFormat('d/m/Y', "01/11/2024"));
    $boleto->setDataProcessamento(DateTime::createFromFormat('d/m/Y', "01/11/2024"));
    $boleto->addDemostrativo('Pagamento de teste');
    $boleto->addInstrucao("- Não receber após o vencimento");
    $boleto->setValorBoleto("100,00");
    $boleto->setNossoNumero("123456789");
    $boleto->setNumeroDocumento("123456789");
    $boleto->setCedente($cedente);
    $boleto->setSacado($sacado);

    return $boleto;
}

function criarBoletoSicoobTeste(): Boleto
{
    $cedente = new Cedente();
    $cedente->setNome("Loja Teste Ltda");
    $cedente->setAgencia("4342");
    $cedente->setDvAgencia("0");
    $cedente->setConta("24339");
    $cedente->setDvConta("4");
    $cedente->setEndereco("Rua Teste, 123");
    $cedente->setCidade("São Paulo");
    $cedente->setUf("SP");
    $cedente->setCpfCnpj("12.345.678/0001-90");

    $sacado = new Sacado();
    $sacado->setNome("Cliente Teste");
    $sacado->setTipoLogradouro("Rua");
    $sacado->setEnderecoLogradouro("Endereço Teste");
    $sacado->setNumeroLogradouro("456");
    $sacado->setCidade("São Paulo");
    $sacado->setUf("SP");
    $sacado->setCep("01234-567");
    $sacado->setCpfCnpj("123.456.789-00");

    $banco = new Sicoob();
    $banco->setAceite("N");

    $boleto = new Boleto();
    $boleto->setBanco($banco);
    $boleto->setNumeroMoeda(9);
    $boleto->setDataVencimento(DateTime::createFromFormat('d/m/Y', "01/12/2024"));
    $boleto->setDataDocumento(DateTime::createFromFormat('d/m/Y', "01/11/2024"));
    $boleto->setDataProcessamento(DateTime::createFromFormat('d/m/Y', "01/11/2024"));
    $boleto->addDemostrativo('Pagamento de teste');
    $boleto->addInstrucao("- Não receber após o vencimento");
    $boleto->setValorBoleto("100,00");
    $boleto->setNossoNumero("2");
    $boleto->setNumeroDocumento("2");
    $boleto->setCedente($cedente);
    $boleto->setSacado($sacado);

    return $boleto;
}

function criarBoletoCaixaTeste(): Boleto
{
    $cedente = new Cedente();
    $cedente->setNome("EMPRESA TESTE LTDA - ME");
    $cedente->setAgencia("0501");
    $cedente->setDvAgencia("0");
    $cedente->setConta("721933");
    $cedente->setDvConta("4");
    $cedente->setEndereco("Rua Teste, 123");
    $cedente->setCidade("São Paulo");
    $cedente->setUf("SP");
    $cedente->setCpfCnpj("12.345.678/0001-90");

    $sacado = new Sacado();
    $sacado->setNome("Cliente Teste");
    $sacado->setTipoLogradouro("Rua");
    $sacado->setEnderecoLogradouro("Endereço Teste");
    $sacado->setNumeroLogradouro("456");
    $sacado->setCidade("São Paulo");
    $sacado->setUf("SP");
    $sacado->setCep("01234-567");
    $sacado->setCpfCnpj("123.456.789-00");

    $banco = new Caixa();

    $boleto = new Boleto();
    $boleto->setBanco($banco);
    $boleto->setNumeroMoeda(9);
    $boleto->setDataVencimento(DateTime::createFromFormat('d/m/Y', "01/12/2024"));
    $boleto->setDataDocumento(DateTime::createFromFormat('d/m/Y', "01/11/2024"));
    $boleto->setDataProcessamento(DateTime::createFromFormat('d/m/Y', "01/11/2024"));
    $boleto->addDemostrativo('Pagamento de teste');
    $boleto->addInstrucao("- Não receber após o vencimento");
    $boleto->setValorBoleto("100,00");
    $boleto->setNossoNumero("000000000000002");
    $boleto->setNumeroDocumento("233");
    $boleto->setCedente($cedente);
    $boleto->setSacado($sacado);

    return $boleto;
}

test('deve gerar PDF do boleto Bradesco sem erros', function () {
    $boleto = criarBoletoTeste();

    $gerador = new GeradorBoleto();
    $pdf = $gerador->gerar($boleto);

    expect($pdf)->toBeInstanceOf(FPDF::class);

    // Captura o PDF como string
    $pdfContent = $pdf->Output('S');

    expect($pdfContent)->not()->toBeEmpty();
    expect(strlen($pdfContent))->toBeGreaterThan(1000); // PDF deve ter conteúdo significativo
    expect(substr($pdfContent, 0, 4))->toBe('%PDF'); // Verifica se começa com header PDF
});

test('deve gerar PDF do boleto Sicoob sem erros', function () {
    $boleto = criarBoletoSicoobTeste();

    $gerador = new GeradorBoleto();
    $pdf = $gerador->gerar($boleto);

    expect($pdf)->toBeInstanceOf(FPDF::class);

    // Captura o PDF como string
    $pdfContent = $pdf->Output('S');

    expect($pdfContent)->not()->toBeEmpty();
    expect(strlen($pdfContent))->toBeGreaterThan(1000);
    expect(substr($pdfContent, 0, 4))->toBe('%PDF');
});

test('deve gerar PDF do boleto Caixa sem erros', function () {
    $boleto = criarBoletoCaixaTeste();

    $gerador = new GeradorBoleto();
    $pdf = $gerador->gerar($boleto);

    expect($pdf)->toBeInstanceOf(FPDF::class);

    // Captura o PDF como string
    $pdfContent = $pdf->Output('S');

    expect($pdfContent)->not()->toBeEmpty();
    expect(strlen($pdfContent))->toBeGreaterThan(1000);
    expect(substr($pdfContent, 0, 4))->toBe('%PDF');
});

test('deve validar estrutura básica do PDF gerado', function () {
    $boleto = criarBoletoTeste();

    $gerador = new GeradorBoleto();
    $pdf = $gerador->gerar($boleto);

    // Verifica se o PDF tem pelo menos uma página
    expect($pdf->PageNo())->toBeGreaterThan(0);

    // Captura o conteúdo do PDF
    $pdfContent = $pdf->Output('S');

    // Verifica se contém elementos essenciais do PDF
    expect($pdfContent)->toContain('stream');
    expect($pdfContent)->toContain('endstream');
    expect($pdfContent)->toContain('/Type /Page');
});

test('deve funcionar com diferentes valores monetários', function ($valor) {
    $boleto = criarBoletoTeste();
    $boleto->setValorBoleto($valor);

    $gerador = new GeradorBoleto();
    $pdf = $gerador->gerar($boleto);

    expect($pdf)->toBeInstanceOf(FPDF::class);

    $pdfContent = $pdf->Output('S');

    expect($pdfContent)->not()->toBeEmpty();
    expect(substr($pdfContent, 0, 4))->toBe('%PDF');
})->with(['10,50', '100,00', '500,99']);

test('deve funcionar com múltiplas instruções e demonstrativos', function () {
    $boleto = criarBoletoTeste();
    $boleto->addInstrucao("Primeira instrução");
    $boleto->addInstrucao("Segunda instrução");
    $boleto->addInstrucao("Terceira instrução");
    $boleto->addDemostrativo("Primeiro demonstrativo");
    $boleto->addDemostrativo("Segundo demonstrativo");

    $gerador = new GeradorBoleto();
    $pdf = $gerador->gerar($boleto);

    expect($pdf)->toBeInstanceOf(FPDF::class);

    $pdfContent = $pdf->Output('S');

    expect($pdfContent)->not()->toBeEmpty();
    expect(strlen($pdfContent))->toBeGreaterThan(1000);
});