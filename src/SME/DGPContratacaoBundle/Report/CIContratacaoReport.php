<?php

namespace SME\DGPContratacaoBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\DGPContratacaoBundle\Entity\CIGeral;
use SME\DGPContratacaoBundle\Entity\TipoProcesso;
use SME\DGPBundle\Util\AssinaturasUtil;

class CIContratacaoReport extends PDFDocument {
    
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
        
        $tipoContrato = $this->ciGeral->getProrrogacao() ? 'PRORROGAÇÃO dos contratos' : 'contratação';
        if($this->getCiGeral()->getProcesso()->getTipoProcesso()->getId() === TipoProcesso::PROCESSO_SELETIVO) {
            $this->MultiCell($this->ws, 5, '    Cumprimentando-o cordialmente, solicitamos a ' . $tipoContrato . ' em caráter temporário das pessoas relacionadas abaixo, classificadas no(a) '
                . $this->ciGeral->getProcesso()->getTipoProcesso()->getNome() . ' disciplinado pelo Edital nº ' . $this->ciGeral->getProcesso()->getEdital() .
                ', com resultado final publicado no Edital nº 017/2016, homologado pelo Edital nº 018/2016 na Edição nº 1592 do Jornal do Município de 18 de julho de 2016.', '', 'J');
        } else {
            $this->MultiCell($this->ws, 5, '    Cumprimentando-o cordialmente, solicitamos a ' . $tipoContrato . ' em caráter temporário das pessoas relacionadas abaixo, classificadas no(a) '
                . $this->ciGeral->getProcesso()->getTipoProcesso()->getNome() . ', Edital nº ' . $this->ciGeral->getProcesso()->getEdital() .
                ', homologado na Edição Nº 1694 do Jornal do Município de 22 de fevereiro de 2017.', '', 'J');
        }
        $this->Ln(5);
        
        $this->SetWidths(array(50,50,90));
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
        $this->Row(array('Nome', 'Cargo', 'Justificativa'));
        $this->SetFont('Arial', '', 10);
        $this->SetAligns(array('L', 'L', 'L'));
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        foreach($this->ciGeral->getVinculos() as $vinculo) {
            $this->Row(array(
                $vinculo->getServidor()->getNome(),
                $vinculo->getCargo()->getNome(),
                $vinculo->getObservacao()
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
        $this->Cell(100, 5, 'Diretora de Gestão de Pessoas', '', 0, 'C');
        $this->Cell(100, 3, 'Secretária de Educação', '', 1, 'C');
    }

    public function getCiGeral() {
        return $this->ciGeral;
    }

    public function setCiGeral(CIGeral $ciGeral) {
        $this->ciGeral = $ciGeral;
    }
    
}
