<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class AcessoDocumentalReport extends RequerimentoReport
{
    
    public function prepareDocument() {  
        $this->titulo = 'REQUERIMENTO DE ACESSO AO ACERVO TÉCNICO - ARQUIVO';
        $this->via = 1;
        $this->AddPage();
        $this->AliasNbPages();

        $this->SetFont('Arial', '', 12);
        $this->Ln(10);

        //----------------------------------------------------------------
        $this->SetFont('Arial','', 10);
        $this->Cell(320,10,'FUNCIONÁRIO', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(210,10,'MATRÍCULAS', '', 1, 'J');

        $this->SetFont('Arial', '', 12);
        $this->Cell(320, 20, $this->params['nome'], 'LTBR', 0, 'J');
        $this->Cell(10, 20, '', '', 0, 'J');
        $this->Cell(210, 20, isset($this->params['matriculas']) ? $this->params['matriculas'] : '', 'LTBR', 1, 'J');
        $this->Ln(15);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 10);
        $this->Cell(320,10,'SETOR SOLICITANTE', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(210,10,'RESPONSÁVEL', '', 1, 'J');

        $this->SetFont('Arial', '', 12);
        $this->Cell(320, 20, $this->params['setor'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(210, 20, $this->params['responsavel'], 'LTBR', 1, 'J');
        $this->Ln(15);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial', '', 10);
        $this->Cell(540,10,'TIPO DOCUMENTAL / DEPARTAMENTO / MÊS / ANO ', '', 1, 'J');
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(540, 20,"{$this->params['tipo_documental']}", 'LTBR', 'J');
        $this->Ln(15);

        //----------------------------------------------------------------
        $this->SetFont('Arial', '', 10);
        $this->Cell(320,10,'FORMA DE RESPOSTA', '', 1, 'J');
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(320, 20,"{$this->params['resposta']}", 'LTBR', 'J');
        $this->Ln(15);
        
        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(540, 16, 'RESULTADO', 'TRL', 'C');
        $this->MultiCell(540, 10, ' ', 'RL', 'J');
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(540, 16, '(   ) Disponível a partir de ___/___/______', 'RL', 'J');
        $this->MultiCell(540, 16, '(   ) Atendido', 'RL', 'J');
        $this->MultiCell(540, 16, '(   ) Não atendido', 'RL', 'J');
        $this->MultiCell(540, 16, '(   ) Emprestado', 'RL', 'J');
        $this->MultiCell(540, 16, '(   ) Desaparecido', 'BRL', 'J');
        $this->SetFont('Arial', '', 12);
       
        $this->Ln(50);
        $this->caixaProtocolo();
    }

}

