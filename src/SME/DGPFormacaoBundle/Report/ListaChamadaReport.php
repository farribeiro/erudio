<?php

namespace SME\DGPFormacaoBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\DGPFormacaoBundle\Entity\Formacao;
use SME\CommonsBundle\Util\DocumentosUtil;

class ListaChamadaReport extends PDFDocument {
    
    private $formacao;
    
    public function build() {
        $this->AddPage();
        $this->AliasNbPages();
    	$this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 11);
        $this->SetAligns(array('L', 'L'));
        $this->SetWidths(array(190));
        $this->Row(array('Formação: ' . $this->formacao->getNome()));
        $this->SetWidths(array(50, 140));
        $this->Row(array('Data:', 'Local: '));
        $this->Row(array('Horário:            até', 'Período: '));
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->SetWidths(array(100, 35, 55));
        $this->SetAligns(array('C', 'C', 'C'));
        $this->Row(array('Nome', 'CPF', 'Assinatura'));
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 11);
        $this->SetAligns(array('L', 'C', 'C'));
        foreach($this->formacao->getMatriculas() as $m) {
            $this->Row(array($m->getPessoa()->getNome(), DocumentosUtil::formatarCpf($m->getPessoa()->getCpfCnpj()), ''));
        }
    }
    
    function header($title = 'SECRETARIA DE EDUCAÇÃO', $subtitle = 'Formação Continuada - Lista de Comparecimento') {
        parent::header($title, $subtitle);
    }
    
    public function getFormacao() {
        return $this->formacao;
    }

    public function setFormacao(Formacao $formacao) {
        $this->formacao = $formacao;
    }

}
