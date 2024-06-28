<?php

namespace Boleto;

readonly class Avalista
{
    public function __construct(
        private string $nome,
        private string $cpfCnpj
    ) {}

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getCpfCnpj(): string
    {
        return $this->cpfCnpj;
    }
}