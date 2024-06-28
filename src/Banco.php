<?php

namespace Boleto;

use Boleto\Util\Modulo;

abstract class Banco
{
    private int $codigo;
    private string $digitoVerificador;
    private string $especie;
    private string $especieDocumento;
    private string $nome;
    private string $logomarca;
    private ?string $carteira = null;
    private int $carteiraModalidade;
    private string $localPagamento;
    private string $aceite;
    private int $tipoImpressao = 4;
    private string $layoutCarne;
    private string $posto;
    private string $byte;
    private string $nossoNumero;

    public function __construct()
    {
        $this->init();
    }

    abstract protected function init(): void;

    abstract protected function digitoVerificadorNossoNumero($numero): int;

    abstract protected function getDigitoVerificadorNossoNumero(Boleto $boleto): int;

    abstract public function getNossoNumeroComDigitoVerificador(Boleto $boleto): int|string;

    abstract public function getNossoNumeroSemDigitoVerificador(Boleto $boleto): string;

    abstract public function getCarteiraENossoNumeroComDigitoVerificador(Boleto $boleto): string;

    abstract public function getDigitoVerificadorCodigoBarras(Boleto $boleto): int;

    abstract public function getLinha(Boleto $boleto): string;

    abstract public function getCampoLivre(Boleto $boleto): string;

    public function getCodigoComDigitoVerificador(): string
    {
        return $this->geraCodigoBanco();
    }

    public function geraCodigoBanco(): string
    {
        $parte1 = substr($this->codigo, 0, 3);
        $parte2 = Modulo::modulo11($parte1);

        return "$parte1-$parte2";
    }

    public function getCarteiraFormatada(): string
    {
        return 2 == $this->getCarteiraModalidade() ? 'SR' : 'RG';
    }

    public function getCodigo(): int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): Banco
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getDigitoVerificador(): string
    {
        return $this->digitoVerificador;
    }

    public function setDigitoVerificador(string $digitoVerificador): Banco
    {
        $this->digitoVerificador = $digitoVerificador;

        return $this;
    }

    public function getEspecie(): string
    {
        return $this->especie;
    }

    public function setEspecie(string $especie): Banco
    {
        $this->especie = $especie;

        return $this;
    }

    public function getEspecieDocumento(): string
    {
        return $this->especieDocumento;
    }

    public function setEspecieDocumento(string $especieDocumento): Banco
    {
        $this->especieDocumento = $especieDocumento;

        return $this;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): Banco
    {
        $this->nome = $nome;

        return $this;
    }

    public function getLogomarca(): string
    {
        return $this->logomarca;
    }

    public function setLogomarca(string $logomarca): Banco
    {
        $this->logomarca = $logomarca;

        return $this;
    }

    public function getCarteira(): string
    {
        return $this->carteira;
    }

    public function setCarteira(string $carteira): Banco
    {
        $this->carteira = $carteira;

        return $this;
    }

    public function getCarteiraModalidade(): int
    {
        return $this->carteiraModalidade;
    }

    public function setCarteiraModalidade(int $carteiraModalidade): Banco
    {
        $this->carteiraModalidade = $carteiraModalidade;

        return $this;
    }

    public function getLocalPagamento(): string
    {
        return $this->localPagamento;
    }

    public function setLocalPagamento(string $localPagamento): Banco
    {
        $this->localPagamento = $localPagamento;

        return $this;
    }

    public function getAceite(): string
    {
        return $this->aceite;
    }

    public function setAceite(string $aceite): Banco
    {
        $this->aceite = $aceite;

        return $this;
    }

    public function getTipoImpressao(): int
    {
        return $this->tipoImpressao;
    }

    public function setTipoImpressao(int $tipoImpressao): Banco
    {
        $this->tipoImpressao = $tipoImpressao;

        return $this;
    }

    public function getLayoutCarne(): string
    {
        return $this->layoutCarne;
    }

    public function setLayoutCarne(string $layoutCarne): Banco
    {
        $this->layoutCarne = $layoutCarne;

        return $this;
    }

    public function getPosto(): string
    {
        return $this->posto;
    }

    public function setPosto(string $posto): Banco
    {
        $this->posto = $posto;

        return $this;
    }

    public function getByte(): string
    {
        return $this->byte;
    }

    public function setByte(string $byte): Banco
    {
        $this->byte = $byte;

        return $this;
    }

    public function getNossoNumero(): string
    {
        return $this->nossoNumero;
    }

    public function setNossoNumero(string $nossoNumero): Banco
    {
        $this->nossoNumero = $nossoNumero;

        return $this;
    }
}