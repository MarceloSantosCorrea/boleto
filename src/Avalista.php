<?php

namespace Boleto;

class Avalista
{
    public function __construct(
        private readonly string $nome,
        private readonly string $cpfCnpj
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