<?php

namespace Boleto\Banco;

use Boleto\Banco;
use Boleto\Boleto;
use Boleto\Util\Modulo;
use Boleto\Util\Numero;

class Sicredi extends Banco
{
    protected function init(): void
    {
        $this->setEspecie('R$');
        $this->setEspecieDocumento('OS');
        $this->setCodigo('748');
        $this->setDigitoVerificador('X');
        $this->setNome('Sicredi');
        $this->setAceite('N');
        $this->setLogomarca('logosicredi.png');
        $this->setLocalPagamento('PAGÁVEL PREFERENCIALMENTE NAS COOPERATIVAS DE CRÉDITO DO Sicredi');
    }

    public function getCodigoComDigitoVerificador(): string
    {
        return "{$this->getCodigo()}-{$this->getDigitoVerificador()}";
    }

    public function getDigitoVerificadorNossoNumero(Boleto $boleto): int
    {
        // |Agência | Posto | Cedente | Ano | Byte | Sequencial(Nosso número) |
        $nnum = $boleto->getCedente()->getAgencia() . $this->getPosto() .
            $boleto->getCedente()->getCodigoCedente() . $boleto->getDataDocumento()->format('y')
            . $this->getByte() . $boleto->getNossoNumero();

        //dv do nosso número
        return $this->digitoVerificadorNossoNumero($nnum);
    }

    public function getNossoNumeroSemDigitoVerificador(Boleto $boleto): string
    {
        return $boleto->getDataDocumento()->format('y') . $this->getByte() . $boleto->getNossoNumero();
    }

    public function getNossoNumeroComDigitoVerificador(Boleto $boleto): int|string
    {
        return $boleto->getDataDocumento()->format('y') . $this->getByte() . $boleto->getNossoNumero();
    }

    public function getCarteiraENossoNumeroComDigitoVerificador(Boleto $boleto): string
    {
        return $boleto->getDataDocumento()->format('y') .
            '/' .
            $this->getByte() .
            $boleto->getNossoNumero() .
            '-' .
            $this->getNossoNumeroComDigitoVerificador($boleto);
    }

    public function getDigitoVerificadorCodigoBarras(Boleto $boleto): int
    {
        $cl = ($this->getCarteira() == 'C' ? '3' : '1') .
            '1' .
            Numero::formataNumero($boleto->getNossoNumeroSemDigitoVerificador() . $boleto->getDigitoVerificadorNossoNumero(), 9, 0) .
            Numero::formataNumero($boleto->getCedente()->getAgencia(), 4, 0) .
            Numero::formataNumero($this->getPosto(), 2, 0) .
            Numero::formataNumero($boleto->getCedente()->getConta(), 5, 0) .
            '10';

        $campoLivre = $cl . $this->digitoVerificadorCampoLivre($cl);

        $numero = $this->getCodigo() .
            $boleto->getNumeroMoeda() .
            $boleto->getFatorVencimento() .
            Numero::formataNumero($boleto->getValorBoleto(), 10, 0) .
            $campoLivre;

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
        $resto2 = Modulo::modulo11($numero, 9, 1);
        $digito = 11 - $resto2;
        if ($digito > 9) {
            $dv = 0;
        } else {
            $dv = $digito;
        }

        return $dv;
    }

    public function digitoVerificadorCampoLivre($numero): int|string
    {
        $resto2 = Modulo::modulo11($numero, 9, 1);
        if ($resto2 <= 1) {
            $dv = 0;
        } else {
            $dv = 11 - $resto2;
        }

        return $dv;
    }

    public function getLinha(Boleto $boleto): string
    {
        $cv = ($this->getCarteira() == 'C' ? '3' : '1') .
            '1' .
            Numero::formataNumero($boleto->getNossoNumeroSemDigitoVerificador() . $boleto->getDigitoVerificadorNossoNumero(), 9, 0) .
            Numero::formataNumero($boleto->getCedente()->getAgencia(), 4, 0) .
            Numero::formataNumero($this->getPosto(), 2, 0) .
            Numero::formataNumero($boleto->getCedente()->getConta(), 5, 0) .
            '10';
        $campoLivre = $cv . $this->digitoVerificadorCampoLivre($cv);

        return
            $this->getCodigo() .
            $boleto->getNumeroMoeda() .
            $boleto->getDigitoVerificadorCodigoBarras() .
            $boleto->getFatorVencimento() .
            $boleto->getValorBoletoSemVirgula() .
            $campoLivre;
    }

    public function setNossoNumeroFormatado(Boleto $boleto): Sicredi
    {
        return $this->setNossoNumero(
            \sprintf(
                "%s/%s%s-%s",
                $boleto->getDataDocumento()->format('y'),
                $this->getByte(),
                $boleto->getNossoNumero(),
                $boleto->getDigitoVerificadorNossoNumero()
            )
        );
    }

    public function getCampoLivre(Boleto $boleto): string
    {
        // TODO: Implement getCampoLivre() method.
    }
}