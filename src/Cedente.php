<?php

namespace Boleto;

use Boleto\Util\Numero;

class Cedente
{
    private string $nome;
    private string $cpfCnpj;
    private string $endereco;
    private string $cidade;
    private string $uf;
    private string $agencia;
    private string $dvAgencia;
    private string $conta;
    private string $dvConta;
    private ?string $codigoCedente = null;

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): Cedente
    {
        $this->nome = $nome;

        return $this;
    }

    public function getCpfCnpj(): string
    {
        return $this->cpfCnpj;
    }

    public function setCpfCnpj(string $cpfCnpj): Cedente
    {
        $this->cpfCnpj = $cpfCnpj;

        return $this;
    }

    public function getEndereco(): string
    {
        return $this->endereco;
    }

    public function setEndereco(string $endereco): Cedente
    {
        $this->endereco = $endereco;

        return $this;
    }

    public function getCidade(): string
    {
        return $this->cidade;
    }

    public function setCidade(string $cidade): Cedente
    {
        $this->cidade = $cidade;

        return $this;
    }

    public function getUf(): string
    {
        return $this->uf;
    }

    public function setUf(string $uf): Cedente
    {
        $this->uf = $uf;

        return $this;
    }

    public function getAgencia(): string
    {
        return $this->agencia;
    }

    public function setAgencia(string $agencia): Cedente
    {
        $this->agencia = $agencia;

        return $this;
    }

    public function getDvAgencia(): string
    {
        return $this->dvAgencia;
    }

    public function setDvAgencia(string $dvAgencia): Cedente
    {
        $this->dvAgencia = $dvAgencia;

        return $this;
    }

    public function getConta(): string
    {
        return $this->conta;
    }

    public function setConta(string $conta): Cedente
    {
        $this->conta = $conta;

        return $this;
    }

    public function getDvConta(): string
    {
        return $this->dvConta;
    }

    public function setDvConta(string $dvConta): Cedente
    {
        $this->dvConta = $dvConta;

        return $this;
    }

    public function getCodigoCedente(): ?string
    {
        return $this->codigoCedente;
    }

    public function setCodigoCedente(?string $codigoCedente): Cedente
    {
        $this->codigoCedente = $codigoCedente;

        return $this;
    }

    public function getContaComDv(): string
    {
        $conta = $this->getConta();
        $dv = Numero::formataNumero($this->getDvConta(), 1, 0);

        return "{$conta}-{$dv}";
    }
}