<?php

namespace Boleto\Banco;

use Boleto\Boleto;
use Boleto\Banco;
use Boleto\Util\Modulo;

class Itau extends Banco
{
    protected function init()
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

    /**
     * @param Boleto $boleto
     * @return int|string
     */
    public function getNossoNumeroComDigitoVerificador(Boleto $boleto)
    {
        return $boleto->digitoVerificadorNossonumero($this->getNossoNumeroSemDigitoVerificador($boleto));
    }

    /**
     * @param Boleto $boleto
     * @return string
     */
    public function getNossoNumeroSemDigitoVerificador(Boleto $boleto)
    {
        return $this->getCarteiraModalidade() . $this->getTipoImpressao() . $boleto->getNossoNumero();
    }

    /**
     * @param Boleto $boleto
     * @return string
     */
    public function getCarteiraENossoNumeroComDigitoVerificador(Boleto $boleto)
    {
        $nossoNumero = $this->getCarteira() . '/' . $boleto->getNossoNumero();

        return $nossoNumero . '-' . Modulo::modulo10($boleto->getCedente()->getAgencia() . $boleto->getCedente()->getConta() . $this->getCarteira() . $boleto->getNossoNumero());
    }

    /**
     * @param Boleto $boleto
     * @return int
     */
    public function getDigitoVerificadorCodigoBarras(Boleto $boleto)
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
            "000";

        return $this->tratarRestoDigitoVerificadorGeral(Modulo::modulo11($numero, 9, 1));
    }

    /**
     * @param $numero
     * @return int
     */
    public function digitoVerificadorNossonumero($numero)
    {
        return $this->tratarRestoDigitoVerificadorNossoNumeroCampoLivre(Modulo::modulo11($numero, 9, 1));
    }

    /**
     * @param Boleto $boleto
     * @return string
     */
    function getLinha(Boleto $boleto)
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

    /**
     * @param Boleto $boleto
     * @return string
     */
    public function getCampoLivre(Boleto $boleto)
    {
        return $boleto->getCedente()->getConta() .
            $boleto->getCedente()->getDvConta() .
            substr($this->getNossoNumeroSemDigitoVerificador($boleto), 2, 3) .
            $this->getCarteiraModalidade() .
            substr($this->getNossoNumeroSemDigitoVerificador($boleto), 5, 3) .
            $this->getTipoImpressao() .
            substr($this->getNossoNumeroSemDigitoVerificador($boleto), 8, 9);

    }

    /**
     * @param Boleto $boleto
     * @return int
     */
    public function getDvCampoLivre(Boleto $boleto)
    {
        $campoLivre = $this->getCampoLivre($boleto);

        return $this->tratarRestoDigitoVerificadorNossoNumeroCampoLivre(Modulo::modulo11($campoLivre, 9, 1));
    }

    /**
     * @param $resto
     * @return int
     */
    private function tratarRestoDigitoVerificadorGeral($resto)
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
    private function tratarRestoDigitoVerificadorNossoNumeroCampoLivre($resto)
    {
        $resultado = 11 - $resto;

        return (9 < $resultado) ? 0 : $resultado;
    }
}