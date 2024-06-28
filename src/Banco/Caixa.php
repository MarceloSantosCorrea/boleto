<?php

namespace Boleto\Banco;

use Boleto\Banco;
use Boleto\Boleto;
use Boleto\Util\Modulo;

class Caixa extends Banco
{
    protected function init(): void
    {
        $this->setCarteira(1);
        $this->setCarteiraModalidade(1);
        $this->setEspecie('R$');
        $this->setEspecieDocumento('DM');
        $this->setCodigo(104);
        $this->setNome('Caixa');
        $this->setAceite('N');
        $this->setLogomarca('logocaixa.jpg');
        $this->setLocalPagamento('PREFERENCIALMENTE NAS CASAS LOTÉRICAS ATÉ O VALOR LIMITE');
        $this->setLayoutCarne('caixa/carne.html.twig');
    }

    public function getNossoNumeroComDigitoVerificador(Boleto $boleto): int|string
    {
        return $boleto->digitoVerificadorNossoNumero($this->getNossoNumeroSemDigitoVerificador($boleto));
    }

    public function getNossoNumeroSemDigitoVerificador(Boleto $boleto): string
    {
        return $this->getCarteiraModalidade() . $this->getTipoImpressao() . $boleto->getNossoNumero();
    }

    public function getCarteiraENossoNumeroComDigitoVerificador(Boleto $boleto): string
    {
        $nossoNumero = $this->getCarteiraModalidade() . $this->getTipoImpressao() . '/' . $boleto->getNossoNumero();
        $digitoVerificador = $this->tratarRestoDigitoVerificadorNossoNumeroCampoLivre(
            Modulo::modulo11($boleto->getNossoNumeroSemDigitoVerificador(), 9, 1)
        );

        return "$nossoNumero-$digitoVerificador";
    }

    public function getDigitoVerificadorCodigoBarras(Boleto $boleto): int
    {
        $numero =
            $this->getCodigo() .
            $boleto->getNumeroMoeda() .
            $boleto->getFatorVencimento() .
            $boleto->getValorBoletoSemVirgula() .
            $this->getCampoLivre($boleto) .
            $this->getDvCampoLivre($boleto);

        return $this->tratarRestoDigitoVerificadorGeral(Modulo::modulo11($numero, 9, 1));
    }

    public function digitoVerificadorNossoNumero($numero): int
    {
        return $this->tratarRestoDigitoVerificadorNossoNumeroCampoLivre(Modulo::modulo11($numero, 9, 1));
    }

    public function getLinha(Boleto $boleto): string
    {
        return
            $this->getCodigo() .
            $boleto->getNumeroMoeda() .
            $this->getDigitoVerificadorCodigoBarras($boleto) .
            $boleto->getFatorVencimento() .
            $boleto->getValorBoletoSemVirgula() .
            $this->getCampoLivre($boleto) .
            $this->getDvCampoLivre($boleto);
    }

    public function getCampoLivre(Boleto $boleto): string
    {
        return $boleto->getCedente()->getConta() .
            $boleto->getCedente()->getDvConta() .
            substr($this->getNossoNumeroSemDigitoVerificador($boleto), 2, 3) .
            $this->getCarteiraModalidade() .
            substr($this->getNossoNumeroSemDigitoVerificador($boleto), 5, 3) .
            $this->getTipoImpressao() .
            substr($this->getNossoNumeroSemDigitoVerificador($boleto), 8, 9);
    }

    public function getDvCampoLivre(Boleto $boleto): int
    {
        $campoLivre = $this->getCampoLivre($boleto);

        return $this->tratarRestoDigitoVerificadorNossoNumeroCampoLivre(
            Modulo::modulo11($campoLivre, 9, 1)
        );
    }

    private function tratarRestoDigitoVerificadorGeral($resto): int
    {
        $resultado = 11 - $resto;

        if ($resultado == 0 || 9 < $resultado) {
            return 1;
        }

        return $resultado;
    }

    private function tratarRestoDigitoVerificadorNossoNumeroCampoLivre($resto): int
    {
        $resultado = 11 - $resto;

        return (9 < $resultado) ? 0 : $resultado;
    }

    protected function getDigitoVerificadorNossoNumero(Boleto $boleto): int
    {
        // TODO: Implement getDigitoVerificadorNossoNumero() method.
    }
}