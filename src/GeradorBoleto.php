<?php

namespace Boleto;

use Boleto\Util\Substr;
use Boleto\Util\UnidadeMedida;
use FPDF;

class GeradorBoleto
{
    public function gerar(Boleto $boleto): FPDF
    {
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetTextColor(0, 0, 51);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 6);

        $pdf->Cell(190, 4, mb_convert_encoding('Instrução de Impressão:', 'ISO-8859-1', 'UTF-8'), '', 1, 'C');
        $pdf->Cell(190, 4, mb_convert_encoding('Imprimir em impressora jato de tinta (ink jet) ou laser em qualidade normal. (Não use modo econômico).', 'ISO-8859-1', 'UTF-8'), '', 1, 'C');
        $pdf->Cell(190, 4, mb_convert_encoding('Utilize folha A4 (210 x 297 mm) ou carta (216 x 279 mm) - Corte na linha indicada:', 'ISO-8859-1', 'UTF-8'), '', 1, 'C');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(190, 2, 'Recibo do Sacado', '', 1, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(190, 2, '--------------------------------------------------------------------------------------------------------------------------------------', '', 0, 'L');

        $pdf->Ln();
        $pdf->Ln(15);

        $pdf->SetFont('Arial', '', 9);

        $pdf->Cell(50, 10, '', 'B', 0, 'L');
        $pdf->Image(Gerador::getDirImages() . $boleto->getBanco()->getLogomarca(), 10, 43, 40, 10);

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(20, 10, $boleto->getBanco()->getCodigoComDigitoVerificador(), 'LBR', 0, 'C');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(120, 10, $boleto->gerarLinhaDigitavel(), 'B', 1, 'R');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(85, 3, 'Cedente', 'LR', 0, 'L');
        $pdf->Cell(30, 3, mb_convert_encoding('Agência/Código do Cedente', 'ISO-8859-1', 'UTF-8'), 'R', 0, 'L');
        $pdf->Cell(15, 3, mb_convert_encoding('Espécie', 'ISO-8859-1', 'UTF-8'), 'R', 0, 'L');
        $pdf->Cell(20, 3, 'Quantidade', 'R', 0, 'L');
        $pdf->Cell(40, 3, mb_convert_encoding('Carteira/Nosso número', 'ISO-8859-1', 'UTF-8'), 'R', 1, 'L');

        $pdf->SetFont('Arial', '', 7);
        $pdf->SetTextColor(0, 0, 51);
        $pdf->Cell(85, 5, mb_convert_encoding($boleto->getSacado()->getNome(), 'ISO-8859-1', 'UTF-8'), 'BLR', 0, 'L');
        $pdf->Cell(30, 5, $boleto->getCedente()->getAgencia() . " / " . $boleto->getCedente()->getContaComDv(), 'BR', 0, 'L');
        $pdf->Cell(15, 5, $boleto->getBanco()->getEspecie(), 'BR', 0, 'L');
        $pdf->Cell(20, 5, "001", 'BR', 0, 'L');
        $pdf->Cell(40, 5, $boleto->getCarteiraENossoNumeroComDigitoVerificador(), 'BR', 1, 'R');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(60, 3, mb_convert_encoding('Número do Documento', 'ISO-8859-1', 'UTF-8'), 'LR', 0, 'L');
        $pdf->Cell(35, 3, 'CPF/CNPJ', 'R', 0, 'L');
        $pdf->Cell(35, 3, 'Vencimento', 'R', 0, 'L');
        $pdf->Cell(60, 3, 'Valor Documento', 'LR', 1, 'L');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(60, 5, $boleto->getNossoNumero(), 'BLR', 0, 'L');
        $pdf->Cell(35, 5, $boleto->getCedente()->getCpfCnpj(), 'BR', 0, 'L');
        $pdf->Cell(35, 5, $boleto->getDataVencimento()->format('d/m/Y'), 'BR', 0, 'L');
        $pdf->Cell(60, 5, $boleto->getValorBoleto(), 'BR', 1, 'R');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(33, 3, '(-)Desconto/Abatimentos', 'LR', 0, 'L');
        $pdf->Cell(32, 3, mb_convert_encoding('(-)Outras deduções', 'ISO-8859-1', 'UTF-8'), 'R', 0, 'L');
        $pdf->Cell(32, 3, '(+)Mora/Multa', 'R', 0, 'L');
        $pdf->Cell(33, 3, mb_convert_encoding('(+)Outros acréscimos', 'ISO-8859-1', 'UTF-8'), '', 0, 'L');
        $pdf->Cell(60, 3, '(*)Valor Cobrado', 'LR', 1, 'L');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(33, 5, '', 'BLR', 0, 'L');
        $pdf->Cell(32, 5, '', 'BR', 0, 'L');
        $pdf->Cell(32, 5, '', 'BR', 0, 'L');
        $pdf->Cell(33, 5, '', 'BR', 0, 'L');
        $pdf->Cell(60, 5, '', 'BR', 1, 'R');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(190, 3, 'Sacado', 'LR', 1, 'L');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(190, 5, mb_convert_encoding($boleto->getSacado()->getNome(), 'ISO-8859-1', 'UTF-8'), 'LR', 1, 'L');
        $pdf->Cell(190, 5, mb_convert_encoding($boleto->getSacado()->getTipoLogradouro() . ' ' . $boleto->getSacado()->getEnderecoLogradouro() . ', ' . $boleto->getSacado()->getNumeroLogradouro(), 'ISO-8859-1', 'UTF-8'), 'LR', 1, 'L');
        $pdf->Cell(190, 5, mb_convert_encoding($boleto->getSacado()->getCidade() . ' - ' . $boleto->getSacado()->getUf() . ' - CEP: ' . $boleto->getSacado()->getCep(), 'ISO-8859-1', 'UTF-8'), 'BLR', 1, 'L');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(170, 3, mb_convert_encoding('Instruções', 'ISO-8859-1', 'UTF-8'), '', 0, 'L');
        $pdf->Cell(20, 3, mb_convert_encoding('Autênticação Mecânica', 'ISO-8859-1', 'UTF-8'), '', 1, 'R');

        $pdf->SetFont('Arial', '', 7);

        foreach ($boleto->getInstrucoes() as $instrucao) {
            $pdf->Cell(190, 5, utf8_decode($instrucao), '', 1, 'L');
        }

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(190, 2, 'Corte na linha pontilhada', '', 1, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(190, 2, '--------------------------------------------------------------------------------------------------------------------------------------', '', 0, 'L');

        $pdf->Ln(10);

        $pdf->Cell(50, 10, '', 'B', 0, 'L');
        $pdf->Image(Gerador::getDirImages() . $boleto->getBanco()->getLogomarca(), 10, 113 + ((count($boleto->getInstrucoes()) * 5) + 1), 40, 10);

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(20, 10, $boleto->getBanco()->getCodigoComDigitoVerificador(), 'LBR', 0, 'C');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(120, 10, $boleto->gerarLinhaDigitavel(), 'B', 1, 'R');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(130, 3, 'Local Pagamento', 'LR', 0, 'L');
        $pdf->Cell(60, 3, 'Vencimento', 'R', 1, 'L');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(130, 5, mb_convert_encoding($boleto->getBanco()->getLocalPagamento(), 'ISO-8859-1', 'UTF-8'), 'BLR', 0, 'L');
        $pdf->Cell(60, 5, $boleto->getDataVencimento()->format('d/m/Y'), 'BR', 1, 'R');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(130, 3, 'Cedente', 'LR', 0, 'L');
        $pdf->Cell(60, 3, mb_convert_encoding('Agência/Código cedente', 'ISO-8859-1', 'UTF-8'), 'R', 1, 'L');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(130, 5, mb_convert_encoding($boleto->getCedente()->getNome(), 'ISO-8859-1', 'UTF-8'), 'BLR', 0, 'L');
        $pdf->Cell(60, 5, $boleto->getCedente()->getAgencia() . ' / ' . $boleto->getCedente()->getContaComDv(), 'BR', 1, 'R');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(28, 3, 'Data Documento', 'LR', 0, 'L');
        $pdf->Cell(40, 3, mb_convert_encoding('Número do Documento', 'ISO-8859-1', 'UTF-8'), 'R', 0, 'L');
        $pdf->Cell(20, 3, mb_convert_encoding('Espécie doc.', 'ISO-8859-1', 'UTF-8'), 'R', 0, 'L');
        $pdf->Cell(20, 3, 'Aceite', 'R', 0, 'L');
        $pdf->Cell(22, 3, 'Data processamento', '', 0, 'L');
        $pdf->Cell(60, 3, mb_convert_encoding('Carteira / Nosso número', 'ISO-8859-1', 'UTF-8'), 'LR', 1, 'L');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(28, 5, $boleto->getDataDocumento()->format('d/m/Y'), 'BLR', 0, 'L');
        $pdf->Cell(40, 5, $boleto->getNumeroDocumento(), 'BR', 0, 'L');
        $pdf->Cell(20, 5, $boleto->getBanco()->getEspecieDocumento(), 'BR', 0, 'L');
        $pdf->Cell(20, 5, $boleto->getBanco()->getAceite(), 'BR', 0, 'L');
        $pdf->Cell(22, 5, $boleto->getDataProcessamento()->format('d/m/Y'), 'BR', 0, 'L');
        $pdf->Cell(60, 5, $boleto->getCarteiraENossoNumeroComDigitoVerificador(), 'BR', 1, 'R');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(28, 3, 'Uso do Banco', 'LR', 0, 'L');
        $pdf->Cell(25, 3, 'Carteira', 'R', 0, 'L');
        $pdf->Cell(15, 3, mb_convert_encoding('Espécie', 'ISO-8859-1', 'UTF-8'), 'R', 0, 'L');
        $pdf->Cell(40, 3, 'Quantidade', 'R', 0, 'L');
        $pdf->Cell(22, 3, '(x)Valor', '', 0, 'L');
        $pdf->Cell(60, 3, '(=)Valor Documento', 'LR', 1, 'L');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(28, 5, '', 'BLR', 0, 'L');
        $pdf->Cell(25, 5, $boleto->getBanco()->getCarteira(), 'BR', 0, 'L');
        $pdf->Cell(15, 5, $boleto->getBanco()->getEspecie(), 'BR', 0, 'L');
        $pdf->Cell(40, 5, "001", 'BR', 0, 'L');
        $pdf->Cell(22, 5, '', 'BR', 0, 'L');
        $pdf->Cell(60, 5, $boleto->getValorBoleto(), 'BR', 1, 'R');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(130, 3, mb_convert_encoding('Instruções', 'ISO-8859-1', 'UTF-8'), 'L', 0, 'L');
        $pdf->Cell(60, 3, '(-)Desconto/Abatimentos', 'LR', 1, 'L');

        $l = 0;
        for ($i = 0; $i < 4; $i++) {
            $instrucao = isset($boleto->getInstrucoes()[$i]) ? $boleto->getInstrucoes()[$i] : null;

            $l++;
            $pdf->Cell(130, 5, utf8_decode($instrucao), 'L', 0, 'L');

            if (1 == $l) {
                $pdf->Cell(60, 5, '', 'LBR', 1, 'R');
            } else if (2 == $l) {
                $pdf->SetFont('Arial', '', 6);
                $pdf->Cell(60, 3, utf8_decode('(-)Outras deduções'), 'LR', 1, 'L');
            } else if (3 == $l) {
                $pdf->Cell(60, 5, '', 'LBR', 1, 'R');
            } else {
                if (4 == $l) {
                    $pdf->SetFont('Arial', '', 6);
                    $pdf->Cell(60, 3, '(+)Mora/Multa', 'LR', 1, 'L');
                }
            }
        }

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(130, 5, '', 'L', 0, 'L');
        $pdf->Cell(60, 5, '', 'LBR', 1, 'R');

        $pdf->Cell(130, 3, '', 'L', 0, 'L');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(60, 3, mb_convert_encoding('(+)Outros acréscimos', 'ISO-8859-1', 'UTF-8'), 'LR', 1, 'L');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(130, 5, '', 'L', 0, 'L');
        $pdf->Cell(60, 5, '', 'LBR', 1, 'R');

        $pdf->Cell(130, 3, '', 'L', 0, 'L');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(60, 3, '(=)Valor cobrado', 'LR', 1, 'L');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(130, 5, '', 'LB', 0, 'L');
        $pdf->Cell(60, 5, '', 'LBR', 1, 'R');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(190, 3, 'Sacado', 'LR', 1, 'L');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(190, 5, mb_convert_encoding("{$boleto->getSacado()->getNome()} - {$boleto->getSacado()->getCpfCnpj()}", 'ISO-8859-1', 'UTF-8'), 'LR', 1, 'L');
        $pdf->Cell(190, 5, mb_convert_encoding($boleto->getSacado()->getTipoLogradouro() . " " . $boleto->getSacado()->getEnderecoLogradouro() . ", " . $boleto->getSacado()->getNumeroLogradouro(), 'ISO-8859-1', 'UTF-8'), 'LR', 1, 'L');
        $pdf->Cell(190, 5, mb_convert_encoding($boleto->getSacado()->getCidade() . " - " . $boleto->getSacado()->getUf() . " - CEP: " . $boleto->getSacado()->getCep(), 'ISO-8859-1', 'UTF-8'), 'BLR', 1, 'L');

        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(170, 3, 'Sacador/Avalista', '', 0, 'L');
        $pdf->Cell(20, 3, mb_convert_encoding('Autênticação Mecânica - Ficha de Compensação', 'ISO-8859-1', 'UTF-8'), '', 1, 'R');

        $this->fbarcode($boleto->getLinha(), $pdf);

        $pdf->Ln(10);
        $pdf->SetY(260);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(190, 2, 'Corte na linha pontilhada', '', 1, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(190, 2, '--------------------------------------------------------------------------------------------------------------------------------------', '', 0, 'L');

        return $pdf;
    }

    public function fbarcode($valor, FPDF $pdf)
    {
        $fino = UnidadeMedida::px2milimetros(1); // valores em px
        $largo = UnidadeMedida::px2milimetros(2.3); // valor em px
        $altura = UnidadeMedida::px2milimetros(40); // valor em px

        $barcodes[0] = '00110';
        $barcodes[1] = '10001';
        $barcodes[2] = '01001';
        $barcodes[3] = '11000';
        $barcodes[4] = '00101';
        $barcodes[5] = '10100';
        $barcodes[6] = '01100';
        $barcodes[7] = '00011';
        $barcodes[8] = '10010';
        $barcodes[9] = '01010';
        for ($f1 = 9; $f1 >= 0; $f1--) {
            for ($f2 = 9; $f2 >= 0; $f2--) {
                $f = ($f1 * 10) + $f2;
                $texto = "";
                for ($i = 1; $i < 6; $i++) {
                    $texto .= substr($barcodes[$f1], ($i - 1), 1) . substr($barcodes[$f2], ($i - 1), 1);
                }
                $barcodes[$f] = $texto;
            }
        }

        $pdf->Image(Gerador::getDirImages() . '/p.png', $pdf->GetX(), $pdf->GetY(), $fino, $altura);
        $pdf->SetX($pdf->GetX() + $fino);
        $pdf->Image(Gerador::getDirImages() . '/b.png', $pdf->GetX(), $pdf->GetY(), $fino, $altura);
        $pdf->SetX($pdf->GetX() + $fino);
        $pdf->Image(Gerador::getDirImages() . '/p.png', $pdf->GetX(), $pdf->GetY(), $fino, $altura);
        $pdf->SetX($pdf->GetX() + $fino);
        $pdf->Image(Gerador::getDirImages() . '/b.png', $pdf->GetX(), $pdf->GetY(), $fino, $altura);
        $pdf->SetX($pdf->GetX() + $fino);

        $texto = $valor;
        if ((strlen($texto) % 2) <> 0) {
            $texto = "0" . $texto;
        }

        while (strlen($texto) > 0) {
            $i = round(Substr::esquerda($texto, 2));
            $texto = Substr::direita($texto, strlen($texto) - 2);
            $f = $barcodes[$i];
            for ($i = 1; $i < 11; $i += 2) {
                if (substr($f, ($i - 1), 1) == "0") {
                    $f1 = $fino;
                } else {
                    $f1 = $largo;
                }

                $pdf->Image(Gerador::getDirImages() . '/p.png', $pdf->GetX(), $pdf->GetY(), $f1, $altura);
                $pdf->SetX($pdf->GetX() + $f1);

                if (substr($f, $i, 1) == "0") {
                    $f2 = $fino;
                } else {
                    $f2 = $largo;
                }

                $pdf->Image(Gerador::getDirImages() . '/b.png', $pdf->GetX(), $pdf->GetY(), $f2, $altura);
                $pdf->SetX($pdf->GetX() + $f2);
            }
        }

        $pdf->Image(Gerador::getDirImages() . '/p.png', $pdf->GetX(), $pdf->GetY(), $largo, $altura);
        $pdf->SetX($pdf->GetX() + $largo);
        $pdf->Image(Gerador::getDirImages() . '/b.png', $pdf->GetX(), $pdf->GetY(), $fino, $altura);
        $pdf->SetX($pdf->GetX() + $fino);
        $pdf->Image(Gerador::getDirImages() . '/p.png', $pdf->GetX(), $pdf->GetY(), $fino, $altura);
        $pdf->SetX($pdf->GetX() + $fino);
        $pdf->Image(
            Gerador::getDirImages() . '/b.png',
            $pdf->GetX(),
            $pdf->GetY(),
            UnidadeMedida::px2milimetros(1),
            $altura
        );
        $pdf->SetX($pdf->GetX() + UnidadeMedida::px2milimetros(1));
    }
} 