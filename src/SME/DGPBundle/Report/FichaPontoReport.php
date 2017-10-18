<?php

namespace SME\DGPBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\DGPBundle\Entity\Alocacao;

class FichaPontoReport extends PDFDocument {
    
    private $alocacoes;
    
    private $mes;
    private $ano;
    
    public function build() {
    	$extra_options = array();
    	$allocation = null;
    	
        foreach($this->alocacoes as $x => $alocacao) {
            $this->AddPage();
            $this->AliasNbPages();
            $this->rel($alocacao);
        }
    }
    
    function header($title = 'SECRETARIA DE EDUCAÇÃO', $subtitle = 'Ficha Individual de Registro de Comparecimento') {
        parent::header($title, $subtitle);
    }

    private function rel(Alocacao $alocacao) {
        $data = \DateTime::createFromFormat('d/m/Y', '01/' . $this->mes . '/' . $this->ano);
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->SetAligns(array('L', 'L'));
        $this->SetWidths(array(190));
        $this->Row(array('Unidade: ' . $alocacao->getLocalTrabalho()->getPessoaJuridica()->getNome()));
        $this->SetWidths(array(60, 130));
        $this->Row(array('Período: ' . $data->format('m/Y'), 'Servidor: ' . $alocacao->getVinculoServidor()->getServidor()->getNome()));
        $this->Row(array('Vínculo: ' . $alocacao->getVinculoServidor()->getTipoVinculo()->getNome(), 'Cargo Atual: ' . $alocacao->getVinculoServidor()->getCargo()->getNome()));
        $this->Row(array('Carga Horária: ' . $alocacao->getCargaHoraria(), 'Cargo de Origem: ' . $alocacao->getVinculoServidor()->getCargoOrigem()->getNome()));
        $this->SetWidths(array(190));
        $this->Row(array('Função Exercida: ' . $alocacao->getFuncaoAtual()));
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->SetWidths(array(10, 15, 40, 40, 40, 45));
        $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $this->Row(array('', '', 'Primeiro Turno', 'Segundo Turno', 'Terceiro Turno', ''));
        $this->SetWidths(array(10, 15, 20, 20, 20, 20, 20, 20, 45));
        $this->Row(array('Dia', 'Sem.', 'Início', 'Término', 'Início', 'Término', 'Início', 'Término', 'Assinatura'));
        $this->SetWidths(array(10, 15, 15, 10, 15, 15, 10, 15, 15, 10, 15, 45));
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $intervalo = new \DateInterval('P1D');
        while($data->format('m') == $this->mes) {
            $this->Row(array($data->format('d'), $this->diaSemana($data->format('l')), ':', 'às', ':', ':', 'às', ':', ':', 'às', ':', ''));
            $data = $data->add($intervalo);
        }
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->SetWidths(array(190));
        $this->Row(array('RESUMO GERAL'));
        $y = $this->y;
        $this->SetWidths(array(25, 25, 25, 25));
        $this->Row(array('Faltas Justificadas', 'Faltas Injustificadas', 'Desconto DSR', 'Desconto 1/3 do Dia Trabalhado'));
        $this->Row(array('', '', '', ''));
        $this->SetY($y);
        $this->SetX(110);
        $this->Cell(90, 5, 'Carimbo e Ass. - Chefia Imediata', 'TR', 1);
        $this->SetX(110);
        $this->Cell(90, 15, '', 'RB', 1);
        $this->MultiCell(190, 5, 'Observações Gerais:', 'LR');
        $this->MultiCell(190, 20, '', 'BLR');
    }

    private function diaSemana($dia) {
        switch($dia) {
            case 'Sunday': return 'dom';
            case 'Saturday': return 'sáb';
            case 'Monday': return 'seg';
            case 'Tuesday': return 'ter';
            case 'Wednesday': return 'qua';
            case 'Thursday': return 'qui';
            case 'Friday': return 'sex';
        }
    }
    
    public function getAlocacoes() {
        return $this->alocacoes;
    }

    public function getMes() {
        return $this->mes;
    }

    public function setAlocacoes($alocacoes) {
        $this->alocacoes = $alocacoes;
    }

    public function setMes($mes) {
        $this->mes = $mes;
    }
    
    public function getAno() {
        return $this->ano;
    }

    public function setAno($ano) {
        $this->ano = $ano;
    }
    
}
