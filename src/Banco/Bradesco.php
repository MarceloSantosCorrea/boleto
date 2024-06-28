<?php

namespace Boleto\Banco;

use Boleto\Banco;
use Boleto\Boleto;
use Boleto\Util\Modulo;
use Boleto\Util\Numero;

class Bradesco extends Banco
{
    protected function init(): void
    {
        $this->setCarteira("25");
        $this->setEspecie("R$");
        $this->setEspecieDocumento("DS");
        $this->setCodigo("237");
        $this->setNome("Bradesco");
        $this->setLogomarca("logobradesco.jpg");
        $this->setLocalPagamento("Pagável em qualquer Banco até o vencimento");
    }

    public function getNossoNumeroComDigitoVerificador(Boleto $boleto): int|string
    {
        $nnum = Numero::formataNumero($this->getCarteira(), 2, 0) .
            Numero::formataNumero($boleto->getNossoNumero(), 11, 0);

        return $boleto->digitoVerificadorNossoNumero($nnum);
    }

    public function getNossoNumeroSemDigitoVerificador(Boleto $boleto): string
    {
        return Numero::formataNumero($this->getCarteira(), 2, 0) .
            Numero::formataNumero($boleto->getNossoNumero(), 11, 0);
    }

    public function getCarteiraENossoNumeroComDigitoVerificador(Boleto $boleto): string
    {
        $num = Numero::formataNumero($this->getCarteira(), 2, 0) .
            Numero::formataNumero($boleto->getNossoNumero(), 11, 0);

        return substr($num, 0, 2) . '/' . substr($num, 2) . '-' . $boleto->digitoVerificadorNossoNumero($num);
    }

    public function getDigitoVerificadorCodigoBarras(Boleto $boleto): int
    {
        $nnum = Numero::formataNumero($this->getCarteira(), 2, 0) .
            Numero::formataNumero($boleto->getNossoNumero(), 11, 0);

        $numero = $this->getCodigo() .
            $boleto->getNumeroMoeda() .
            $boleto->getFatorVencimento() .
            $boleto->getValorBoletoSemVirgula() .
            $boleto->getCedente()->getAgencia() .
            $nnum .
            Numero::formataNumero($boleto->getCedente()->getConta(), 7, 0) .
            '0';

        $resto2 = Modulo::modulo11($numero, 9, 1);
        if ($resto2 == 0 || $resto2 == 1 || $resto2 == 10) {
            $dv = 1;
        } else {
            $dv = 11 - $resto2;
        }

        return $dv;
    }

    public function digitoVerificadorNossoNumero($numero): int
    {
        $resto2 = Modulo::modulo11($numero, 7, 1);
        $digito = 11 - $resto2;
        if ($digito == 10) {
            $dv = 'P';
        } elseif ($digito == 11) {
            $dv = 0;
        } else {
            $dv = $digito;
        }

        return $dv;
    }

    public function getLinha(Boleto $boleto): string
    {
        return
            $this->getCodigo() .
            $boleto->getNumeroMoeda() .
            $boleto->getDigitoVerificadorCodigoBarras() .
            $boleto->getFatorVencimento() .
            $boleto->getValorBoletoSemVirgula() .
            $boleto->getCedente()->getAgencia() .
            $boleto->getNossoNumeroSemDigitoVerificador() .
            Numero::formataNumero($boleto->getCedente()->getConta(), 7, 0) .
            '0';
    }

    public function getCampoLivre(Boleto $boleto): string
    {

    }

    protected function getDigitoVerificadorNossoNumero(Boleto $boleto): int
    {
        // TODO: Implement getDigitoVerificadorNossoNumero() method.
    }
}