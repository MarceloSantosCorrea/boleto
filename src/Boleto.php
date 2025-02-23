<?php

namespace Boleto;

use Boleto\Util\Data;
use Boleto\Util\Modulo;
use Boleto\Util\Numero;
use DateTime;

class Boleto
{
    private Banco $banco;
    private Cedente $cedente;
    private Sacado $sacado;
    private Avalista $avalista;
    private string $nossoNumero;
    private string $numeroDocumento;
    private DateTime $dataVencimento;
    private DateTime $dataDocumento;
    private DateTime $dataProcessamento;
    private string $valorBoleto;
    private int $numeroMoeda;
    private array $demonstrativos = [];
    private array $instrucoes = [];

    public function setBanco(Banco $banco): void
    {
        $this->banco = $banco;
    }

    public function getBanco(): Banco
    {
        return $this->banco;
    }

    public function setNumeroMoeda(int $numeroMoeda): void
    {
        $this->numeroMoeda = $numeroMoeda;
    }

    public function getNumeroMoeda(): int
    {
        return $this->numeroMoeda;
    }

    public function setDataDocumento(DateTime $dataDocumento): void
    {
        $this->dataDocumento = $dataDocumento;
    }

    public function getDataDocumento(): DateTime
    {
        return $this->dataDocumento;
    }

    public function setDataProcessamento(DateTime $dataProcessamento): void
    {
        $this->dataProcessamento = $dataProcessamento;
    }

    public function getDataProcessamento(): DateTime
    {
        return $this->dataProcessamento;
    }

    public function setDataVencimento(DateTime $dataVencimento): void
    {
        $this->dataVencimento = $dataVencimento;
    }

    public function getDataVencimento(): DateTime
    {
        return $this->dataVencimento;
    }

    public function setDemonstrativos($demonstrativos): void
    {
        $this->demonstrativos = $demonstrativos;
    }

    public function addDemostrativo(mixed $demonstrativo): void
    {
        $this->demonstrativos[] = $demonstrativo;
    }

    public function getDemonstrativos(): array
    {
        return $this->demonstrativos;
    }

    public function setCedente(Cedente $cedente): void
    {
        $this->cedente = $cedente;
    }

    public function getCedente(): Cedente
    {
        return $this->cedente;
    }

    public function setInstrucoes(array $instrucoes): void
    {
        $this->instrucoes = $instrucoes;
    }

    public function addInstrucao(string $instrucao): void
    {
        $this->instrucoes[] = $instrucao;
    }

    public function getInstrucoes(): array
    {
        return $this->instrucoes;
    }

    public function setNossoNumero(string $nossoNumero): void
    {
        $this->nossoNumero = $nossoNumero;
    }

    public function getNossoNumero(): string
    {
        return $this->nossoNumero;
    }

    public function setNumeroDocumento(string $numeroDocumento): void
    {
        $this->numeroDocumento = $numeroDocumento;
    }

    public function getNumeroDocumento(): string
    {
        return $this->numeroDocumento;
    }

    public function setSacado(Sacado $sacado): void
    {
        $this->sacado = $sacado;
    }

    public function getSacado(): Sacado
    {
        return $this->sacado;
    }

    public function setAvalista(Avalista $avalista): void
    {
        $this->avalista = $avalista;
    }

    public function getAvalista(): Avalista
    {
        return $this->avalista;
    }

    public function setValorBoleto(string $valorBoleto): void
    {
        $this->valorBoleto = $valorBoleto;
    }

    public function getValorBoleto(): string
    {
        return $this->valorBoleto;
    }

    public function getValorBoletoSemVirgula(): string
    {
        //valor tem 10 digitos, sem virgula
        return Numero::formataNumero($this->valorBoleto, 10, 0, 'valor');
    }

    public function getFatorVencimento(): float|int
    {
        $data = explode('/', $this->getDataVencimento()->format('d/m/Y'));
        $ano = $data[2];
        $mes = $data[1];
        $dia = $data[0];

        $fator = (abs((Data::_dateToDays('1997', '10', '07')) - (Data::_dateToDays($ano, $mes, $dia))));

        if ($fator > 9999) {
            $fator = (abs((Data::_dateToDays('2022', '05', '29')) - (Data::_dateToDays($ano, $mes, $dia))));
        }

        return $fator;
    }

    public function getDigitoVerificadorCodigoBarras(): int
    {
        return $this->getBanco()->getDigitoVerificadorCodigoBarras($this);
    }

    public function digitoVerificadorNossoNumero($numero): int
    {
        return $this->getBanco()->digitoVerificadorNossoNumero($numero);
    }

    public function getLinha(): string
    {
        return $this->getBanco()->getLinha($this);
    }

    public function getNumeroFebraban(): string
    {
        return
            Numero::formataNumero($this->getBanco()->getCodigo(), 3, 0) .
            $this->getNumeroMoeda() .
            $this->getDigitoVerificadorCodigoBarras() .
            $this->getFatorVencimento() .
            $this->getValorBoletoSemVirgula() .
            $this->getCampoLivre();
    }

    public function gerarLinhaDigitavel(): string
    {
        $codigo = $this->getLinha();

        // Posição 	Conteúdo
        // 1 a 3    Número do banco
        // 4        Código da Moeda - 9 para Real
        // 5        Digito verificador do Código de Barras
        // 6 a 9   Fator de Vencimento
        // 10 a 19 Valor (8 inteiros e 2 decimais)
        // 20 a 44 Campo Livre definido por cada banco (25 caracteres)

        // 1. Campo - composto pelo código do banco, código da moéda, as cinco primeiras posições
        // do campo livre e DV (modulo10) deste campo
        $p1 = substr($codigo, 0, 4);
        $p2 = substr($codigo, 19, 5);
        $p3 = Modulo::modulo10("$p1$p2");
        $p4 = "$p1$p2$p3";
        $p5 = substr($p4, 0, 5);
        $p6 = substr($p4, 5);
        $campo1 = "$p5.$p6";


        // 2. Campo - composto pelas posiçoes 6 a 15 do campo livre
        // e livre e DV (modulo10) deste campo
        $p1 = substr($codigo, 24, 10);
        $p2 = Modulo::modulo10($p1);
        $p3 = "$p1$p2";
        $p4 = substr($p3, 0, 5);
        $p5 = substr($p3, 5);
        $campo2 = "$p4.$p5";

        // 3. Campo composto pelas posicoes 16 a 25 do campo livre
        // e livre e DV (modulo10) deste campo
        $p1 = substr($codigo, 34, 10);
        $p2 = Modulo::modulo10($p1);
        $p3 = "$p1$p2";
        $p4 = substr($p3, 0, 5);
        $p5 = substr($p3, 5);
        $campo3 = "$p4.$p5";

        // 4. Campo - digito verificador do codigo de barras
        $campo4 = substr($codigo, 4, 1);

        // 5. Campo composto pelo fator vencimento e valor nominal do documento, sem
        // indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
        // tratar de valor zerado, a representacao deve ser 000 (tres zeros).
        $p1 = substr($codigo, 5, 4);
        $p2 = substr($codigo, 9, 10);
        $campo5 = "$p1$p2";

        return "$campo1 $campo2 $campo3 $campo4 $campo5";
    }

    public function getNossoNumeroSemDigitoVerificador(): string
    {
        return $this->getBanco()->getNossoNumeroSemDigitoVerificador($this);
    }

    public function getNossoNumeroComDigitoVerificador(): int|string
    {
        return $this->getBanco()->getNossoNumeroComDigitoVerificador($this);
    }

    public function getCarteiraENossoNumeroComDigitoVerificador(): string
    {
        return $this->getBanco()->getCarteiraENossoNumeroComDigitoVerificador($this);
    }

    public function getCampoLivre(): string
    {
        return $this->getBanco()->getCampoLivre($this);
    }

    public function getDigitoVerificadorNossoNumero(): int|string
    {
        return $this->getBanco()->getDigitoVerificadorNossoNumero($this);
    }
}