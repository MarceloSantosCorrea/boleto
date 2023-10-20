<?php

namespace Boleto\Banco;

use Boleto\Banco;
use Boleto\Boleto;
use Boleto\Util\Modulo;
use Boleto\Util\Numero;
use Exception;

class Sicoob extends Banco
{
    protected function init(): void
    {
        $this->setCarteira('1');
        $this->setCarteiraModalidade('01');
        $this->setEspecie('R$');
        $this->setEspecieDocumento('DM');
        $this->setCodigo('756');
        $this->setNome('Sicoob');
        $this->setAceite('N');
        $this->setLogomarca('logosicoob.png');
        $this->setLocalPagamento('Pagável em qualquer banco até o vencimento');
    }

    /**
     * @param Boleto $boleto
     * @return int|string
     * @throws Exception
     */
    public function getNossoNumeroComDigitoVerificador(Boleto $boleto): int|string
    {
        return
            $this->getNossoNumeroSemDigitoVerificador($boleto) .
            $this->digitoVerificadorNossonumero(
                $this->getSequenciaCalculoDigitoVerificadorNossoNumero($boleto)
            );
    }

    /**
     * @throws Exception
     */
    public function getSequenciaCalculoDigitoVerificadorNossoNumero(Boleto $boleto): string
    {
        $agenciaFormatada = Numero::formataNumero($boleto->getCedente()->getAgencia(), 4, 0);
        $contaClean = str_replace('-', '', $boleto->getCedente()->getConta()) . $boleto->getCedente()->getDvConta();
        $contaFormatada = Numero::formataNumero($contaClean, 10, 0);

        return $agenciaFormatada . $contaFormatada . $this->getNossoNumeroSemDigitoVerificador($boleto);
    }

    /**
     * @param Boleto $boleto
     * @return string
     * @throws Exception
     */
    public function getNossoNumeroSemDigitoVerificador(Boleto $boleto): string
    {
        return Numero::formataNumero($boleto->getNossoNumero(), 7, 0);
    }

    /**
     * @param Boleto $boleto
     * @return int
     * @throws Exception
     */
    public function getDigitoVerificadorCodigoBarras(Boleto $boleto): int
    {
        return $this->calcularDigitoVerificadorModulo11(
            $this->getCodigo() .
            $boleto->getNumeroMoeda() .
            $boleto->getFatorVencimento() .
            $boleto->getValorBoletoSemVirgula() .
            $this->getCampoLivre($boleto)
        );
    }

    public function calcularDigitoVerificadorModulo11($codigoBarras)
    {
        $codigoBarras = strrev($codigoBarras); // Inverte a string para facilitar o cálculo.
        $soma = 0;
        $peso = 2;

        for ($i = 0; $i < strlen($codigoBarras); $i++) {
            $digito = (int)$codigoBarras[$i];
            $soma += $digito * $peso;

            if ($peso < 9) {
                $peso++;
            } else {
                $peso = 2;
            }
        }

        $resto = $soma % 11;
        $digitoVerificador = 11 - $resto;

        if ($digitoVerificador == 0 || $digitoVerificador == 1) {
            $digitoVerificador = 1; // Se o resultado for 0 ou 1, o dígito verificador é 1.
        }

        return $digitoVerificador;
    }

    /**
     * @param string $numero é um sequência para cálculo em $this->getSequenciaCalculoDigitoVerificadorNossoNumero()
     * @return int
     */
    public function digitoVerificadorNossonumero($numero): int
    {
        $cont = 0;
        $constante = 0;
        $calculoDv = 0;
        for ($num = 0; $num <= strlen($numero); $num++) {
            $cont++;
            if ($cont == 1) {
                $constante = 3;
            }
            if ($cont == 2) {
                $constante = 1;
            }
            if ($cont == 3) {
                $constante = 9;
            }
            if ($cont == 4) {
                $constante = 7;
                $cont = 0;
            }
            $calculoDv = $calculoDv + ((int)substr($numero, $num, 1) * $constante);
        }

        $resto = $calculoDv % 11;
        if ($resto == 0 || $resto == 1) {
            $dv = 0;
        } else {
            $dv = 11 - $resto;
        }

        return $dv;
    }

    /**
     * @param Boleto $boleto
     * @return string
     * @throws Exception
     */
    function getLinha(Boleto $boleto): string
    {
        return
            $this->getCodigo() .
            $boleto->getNumeroMoeda() .
            $this->getDigitoVerificadorCodigoBarras($boleto) .
            $boleto->getFatorVencimento() .
            $boleto->getValorBoletoSemVirgula() .
            $this->getCampoLivre($boleto);
    }

    /**
     * @param Boleto $boleto
     * @return string
     * @throws Exception
     */
    public function getCampoLivre(Boleto $boleto): string
    {
        return
            $boleto->getBanco()->getCarteira() .
            $boleto->getCedente()->getAgencia() .
            Numero::formataNumero($boleto->getBanco()->getCarteiraModalidade(), 2, 0) .
            Numero::formataNumero($boleto->getCedente()->getConta() . $boleto->getCedente()->getDvConta(), 7, 0) .
            $this->getNossoNumeroComDigitoVerificador($boleto) .
            '001';
    }

    /**
     * @param Boleto $boleto
     * @return int
     * @throws Exception
     */
    public function getDvCampoLivre(Boleto $boleto): int
    {
        $campoLivre = $this->getCampoLivre($boleto);

        return $this->tratarRestoDigitoVerificadorNossoNumeroCampoLivre(
            Modulo::modulo11($campoLivre, 9, 1)
        );
    }

    /**
     * @param $resto
     * @return int
     */
    private function tratarRestoDigitoVerificadorGeral($resto): int
    {
        $resultado = 11 - $resto;

        if ($resultado == 0 || 9 < $resultado) {
            return 1;
        }

        return $resultado;
    }

    /**
     * @param int $resto
     * @return int
     */
    private function tratarRestoDigitoVerificadorNossoNumeroCampoLivre(int $resto): int
    {
        $resultado = 11 - $resto;

        return (9 < $resultado) ? 0 : $resultado;
    }

    /**
     * @throws Exception
     */
    public function getCarteiraENossoNumeroComDigitoVerificador(Boleto $boleto): string
    {
        return $this->getNossoNumeroSemDigitoVerificador($boleto) . '-' .
            $this->digitoVerificadorNossonumero(
                $this->getSequenciaCalculoDigitoVerificadorNossoNumero($boleto)
            );
    }
}