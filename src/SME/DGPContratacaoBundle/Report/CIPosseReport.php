<?php

namespace SME\DGPContratacaoBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\DGPContratacaoBundle\Entity\CIGeral;
use SME\DGPBundle\Util\AssinaturasUtil;

class CIPosseReport extends PDFDocument {
    
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
        $this->MultiCell($this->ws, 5, '    Senhor(a) Secretário(a):', '', 'J');
        $this->Ln(3);
        $this->MultiCell($this->ws, 5, '    Estamos encaminhando quadro abaixo para TOMAR POSSE os candidatos nomeados através do Concurso Público, conforme Edital '
                . $this->ciGeral->getProcesso()->getEdital() . ' Em anexo documentação.', '', 'J');
        $this->Ln(5);
        
        $this->SetWidths(array(50, 25, 25, 20, 45, 25));
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
        $this->Row(array('Nome do Candidato', 'Portaria', 'Data da Nomeação', 'Carga Horária', 'Cargo', 'Data da Posse'));
        $this->SetFont('Arial', '', 10);
        $this->SetAligns(array('L', 'C', 'C', 'C', 'L', 'C'));
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        foreach($this->ciGeral->getVinculos() as $vinculo) {
            $this->Row(array(
                $vinculo->getServidor()->getNome(),
                $vinculo->getPortaria(),
                $vinculo->getDataNomeacao()->format('d/m/Y'),
                $vinculo->getCargaHoraria(),
                $vinculo->getCargo()->getNome(),
                $vinculo->getDataPosse() ? $vinculo->getDataPosse()->format('d/m/Y') : ''
            ));
        }
        $this->Ln(5);
        $this->MultiCell($this->ws, 5, '    Sendo o que tínhamos para o momento, reiteramos votos de consideração e apreço.', '', 'J');
        $this->Ln(3);
        $this->MultiCell($this->ws, 5, '    Atenciosamente,', '', 'J');
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
