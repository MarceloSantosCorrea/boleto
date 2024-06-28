<?php

namespace Boleto\Banco;

use Boleto\Banco;
use Boleto\Boleto;
use Boleto\Util\Modulo;

class Itau extends Banco
{
    protected function init(): void
    {
        $this->setCarteira("109");
        $this->setEspecie("R$");
        $this->setEspecieDocumento("DM");
        $this->setCodigo("341");
        $this->setNome("Itau");
        $this->setAceite("N");
        $this->setLogomarca("logoitau.jpg");
        $this->setLocalPagamento("Até o vencimento, pague preferencialmente no Itaú. Após o vencimento pague somente no Itaú");
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
        $nossoNumero = $this->getCarteira() . '/' . $boleto->getNossoNumero();

        return $nossoNumero . '-' . Modulo::modulo10($boleto->getCedente()->getAgencia() . $boleto->getCedente()->getConta() . $this->getCarteira() . $boleto->getNossoNumero());
    }

    public function getDigitoVerificadorCodigoBarras(Boleto $boleto): int
    {
        $numero =
            $this->getCodigo() .
            $boleto->getNumeroMoeda() .
            $boleto->getFatorVencimento() .
            $boleto->getValorBoletoSemVirgula() .
            $this->getCarteira() .
            $boleto->getNossoNumero() .
            Modulo::modulo10($boleto->getCedente()->getAgencia() . $boleto->getCedente()->getConta() . $this->getCarteira() . $boleto->getNossoNumero()) .
            $boleto->getCedente()->getAgencia() .
            $boleto->getCedente()->getConta() .
            Modulo::modulo10($boleto->getCedente()->getAgencia() . $boleto->getCedente()->getConta()) .
            '000';

        return $this->tratarRestoDigitoVerificadorGeral(Modulo::modulo11($numero, 9, 1));
    }

    public function digitoVerificadorNossoNumero($numero): int
    {
        return $this->tratarRestoDigitoVerificadorNossoNumeroCampoLivre(
            Modulo::modulo11($numero, 9, 1)
        );
    }

    public function getLinha(Boleto $boleto): string
    {
        return
            $this->getCodigo() .
            $boleto->getNumeroMoeda() .
            $this->getDigitoVerificadorCodigoBarras($boleto) .
            $boleto->getFatorVencimento() .
            $boleto->getValorBoletoSemVirgula() .
            $this->getCarteira() .
            $boleto->getNossoNumero() .
            Modulo::modulo10($boleto->getCedente()->getAgencia() . $boleto->getCedente()->getConta() . $this->getCarteira() . $boleto->getNossoNumero()) .
            $boleto->getCedente()->getAgencia() .
            $boleto->getCedente()->getConta() .
            Modulo::modulo10($boleto->getCedente()->getAgencia() . $boleto->getCedente()->getConta()) .
            "000";
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

    private function tratarRestoDigitoVerificadorGeral($resto)
    {
        $resultado = 11 - $resto;

        if ($resultado == 0 || 9 < $resultado) {
            return 1;
        }

        return $resultado;
    }

    private function tratarRestoDigitoVerificadorNossoNumeroCampoLivre($resto)
    {
        $resultado = 11 - $resto;

        return (9 < $resultado) ? 0 : $resultado;
    }

    protected function getDigitoVerificadorNossoNumero(Boleto $boleto): int
    {
        // TODO: Implement getDigitoVerificadorNossoNumero() method.
    }
}