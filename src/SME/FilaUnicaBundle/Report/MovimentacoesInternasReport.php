<?php

namespace SME\FilaUnicaBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\FilaUnicaBundle\Entity\MovimentacaoInterna;

class MovimentacoesInternasReport extends PDFDocument {
    
    private $dataInicio;
    private $dataTermino;
    private $movimentacoes;
    
    function header($title = 'SECRETARIA DE EDUCAÇÃO', $subtitle = '') {
        $subtitle = 'Movimentações internas - Sistema Fila Única';
        parent::header($title, $subtitle);
    }
    
    function footer() {
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);
        $data = new \DateTime();
        $this->MultiCell($this->ws, 6, 'Documento impresso em ' . $data->format('d/m/Y') , '', 'R');
    }
    
    public function build() {
        $this->AddPage('L');
        $this->AliasNbPages();
    	$this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 11);
        $this->SetAligns(array('L'));
        $this->SetWidths(array(280));
        $this->Row(array('Período: ' . $this->dataInicio->format('d/m/Y') . ' à ' . $this->dataTermino->format('d/m/Y')));
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->SetWidths(array(50, 50, 50, 25, 25, 80));
        $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
        $this->Row(array('Inscrição', 'Unidade/Turma anterior', 'Unidade/Turma corrigida', 'Renda P/C atual (R$)', 'Data', 'Justificativa'));
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 11);
        $this->SetAligns(array('L', 'L', 'L', 'C', 'C', 'J'));
        foreach($this->movimentacoes as $m) {
            $this->Row(array(
                $m->getInscricao()->getProtocolo() . ' - ' . $m->getInscricao()->getCrianca()->getNome(),
                $m->getUnidadeEscolarOriginal()->getNome() . ' - ' . $m->getAnoEscolarOriginal()->getNome(),
                $m->getUnidadeEscolarAlterada()->getNome() . ' - ' . $m->getAnoEscolarAlterado()->getNome(),
                $m->getInscricao()->getRendaPerCapita(),
                $m->getDataCadastro()->format('d/m/Y'),
                $m->getJustificativa()
            ));
        }
    }
    
    public function getDataInicio() {
        return $this->dataInicio;
    }

    public function getDataTermino() {
        return $this->dataTermino;
    }

    public function getMovimentacoes() {
        return $this->movimentacoes;
    }

    public function setDataInicio(\DateTime $dataInicio) {
        $this->dataInicio = $dataInicio;
    }

    public function setDataTermino(\DateTime $dataTermino) {
        $this->dataTermino = $dataTermino;
    }

    public function setMovimentacoes(array $movimentacoes) {
        $this->movimentacoes = $movimentacoes;
    }


    
}
