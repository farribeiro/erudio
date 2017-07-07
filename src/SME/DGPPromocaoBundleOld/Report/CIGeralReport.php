<?php

namespace SME\DGPPromocaoBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\DGPPromocaoBundle\Entity\CIGeral;
use SME\DGPPromocaoBundle\Entity\Promocao;
use SME\DGPBundle\Util\AssinaturasUtil;

class CIGeralReport extends PDFDocument {
    
    private $ciGeral;
    
    public function build() {
        $this->AddPage();
        $this->AliasNbPages();
        $this->rel();
    }
    
    private function rel() {
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Ln(2);
        $hoje = new \DateTime();
        $this->MultiCell($this->ws, 5, 'Itajaí, ' . $hoje->format('d') . ' de ' . $this->printMonth($hoje->format('m')) . ' de ' . $hoje->format('Y'), '', 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->MultiCell($this->ws, 5, 'C.I. nº ' . $this->getCiGeral()->getNumeroAno(), '', 'J');
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->MultiCell($this->ws, 5, 'DA: Secretaria de Educação / DGP', '', 'J');
        $this->MultiCell($this->ws, 5, 'PARA: Secretaria Municipal de Administração', '', 'J');
        $this->Ln(5);
        $this->MultiCell($this->ws, 5, '       Senhor(a) Secretário(a):', '', 'J');
        $this->Ln(3);
        $texto = $this->ciGeral->getTipoPromocao() === Promocao::TIPO_PROMOCAO_HORIZONTAL
            ? '    Com base nos incisos I, II e III do art. 24 da Lei Complementar 132, de 2 de abril de 2008, solicitamos a PROMOÇÃO HORIZONTAL dos servidores abaixo elencados:'
            : '    Com base nos incisos I, II e III do art. 27 da Lei Complementar nº132, de 2 de abril de 2008, solicitamos a Promoção Vertical dos servidores abaixo elencados:';
        $this->MultiCell($this->ws, 5, $texto, '', 'J');
        $this->Ln(5);
        
        $this->SetWidths(array(25, 50, 50, 20, 20, 25));
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
        $this->Row(array('Matrícula', 'Nome do servidor', 'Cargo', 'De', 'Para', 'A contar de'));
        $this->SetFont('Arial', '', 10);
        $this->SetAligns(array('C', 'L', 'L', 'C', 'C', 'C'));
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        foreach($this->ciGeral->getPromocoes() as $promocao) {
            $this->Row(array(
                $promocao->getVinculo()->getMatricula(),
                $promocao->getVinculo()->getServidor()->getNome(),
                $promocao->getVinculo()->getCargo()->getNome(),
                $promocao->getNivelAnterior(),
                $promocao->getNivelAtual(),
                $promocao->getDataInicio() ? $promocao->getDataInicio()->format('d/m/Y') : ''
            ));
        }
        $this->Ln(5);
        $this->MultiCell($this->ws, 5, '       Sendo o que tínhamos para o momento, reiteramos votos de consideração e apreço.', '', 'J');
        $this->Ln(3);
        $this->MultiCell($this->ws, 5, '       Atenciosamente,', '', 'J');
        $this->Ln(10);
        $this->Cell(100, 5, '___________________________________', '', 0, 'C');
        $this->Cell(100, 5, '___________________________________', '', 1, 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell(100, 5, AssinaturasUtil::DIRETOR_DGP, '', 0, 'C');
        $this->Cell(100, 5, AssinaturasUtil::SECRETARIO_EDUCACAO, '', 1, 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 9);
        $this->Cell(100, 5, 'Diretor(a) de Gestão de Pessoas', '', 0, 'C');
        $this->Cell(100, 3, 'Secretário(a) de Educação', '', 1, 'C');
    }
 
    public function getCiGeral() {
        return $this->ciGeral;
    }

    public function setCiGeral(CIGeral $ciGeral) {
        $this->ciGeral = $ciGeral;
    }
    
}

