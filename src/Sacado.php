<?php

namespace Boleto;

class Sacado
{
    private string $nome;
    private string $cpfCnpj;
    private string $cep;
    private string $tipoLogradouro;
    private string $enderecoLogradouro;
    private string $numeroLogradouro;
    private string $bairro;
    private string $cidade;
    private string $uf;

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): Sacado
    {
        $this->nome = $nome;

        return $this;
    }

    public function getCpfCnpj(): string
    {
        return $this->cpfCnpj;
    }

    public function setCpfCnpj(string $cpfCnpj): Sacado
    {
        $this->cpfCnpj = $cpfCnpj;

        return $this;
    }

    public function getCep(): string
    {
        return $this->cep;
    }

    public function setCep(string $cep): Sacado
    {
        $this->cep = $cep;

        return $this;
    }

    public function getTipoLogradouro(): string
    {
        return $this->tipoLogradouro;
    }

    public function setTipoLogradouro(string $tipoLogradouro): Sacado
    {
        $this->tipoLogradouro = $tipoLogradouro;

        return $this;
    }

    public function getEnderecoLogradouro(): string
    {
        return $this->enderecoLogradouro;
    }

    public function setEnderecoLogradouro(string $enderecoLogradouro): Sacado
    {
        $this->enderecoLogradouro = $enderecoLogradouro;

        return $this;
    }

    public function getNumeroLogradouro(): string
    {
        return $this->numeroLogradouro;
    }

    public function setNumeroLogradouro(string $numeroLogradouro): Sacado
    {
        $this->numeroLogradouro = $numeroLogradouro;

        return $this;
    }

    public function getBairro(): string
    {
        return $this->bairro;
    }

    public function setBairro(string $bairro): Sacado
    {
        $this->bairro = $bairro;

        return $this;
    }

    public function getCidade(): string
    {
        return $this->cidade;
    }

    public function setCidade(string $cidade): Sacado
    {
        $this->cidade = $cidade;

        return $this;
    }

    public function getUf(): string
    {
        return $this->uf;
    }

    public function setUf(string $uf): Sacado
    {
        $this->uf = $uf;

        return $this;
    }
}