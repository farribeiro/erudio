<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class RecursoReport extends RequerimentoReport
{
    
    public function prepareDocument() {
        for ($i = 1; $i <= 3; $i++) {
            $this->via = $i;
            $this->rel($i);
        }
    }

    private function rel($via) {
        $this->titulo = 'RECURSO CONTRA DECISÃO ADMINISTRATIVA';
        $this->AddPage();
        $this->AliasNbPages();

        $this->SetFont('Arial', '', 12);

        $this->Cell(540, 10, 'Ilustríssimo Senhor Secretário Municipal de Educação', 0, 1, 'J');
        $this->Cell(540, 10, 'NESTA', 0, 1, 'J');
        $this->Ln(15);

        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(350,10,'Nome', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'CPF', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'RG', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(350, 20, $this->params['nome'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['cpf'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['rg'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(350,10,'E-mail', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'Telefone', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'Celular', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(350, 20, $this->params['email'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['telefone'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['celular'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(350,10,'Matrículas', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(350, 20, isset($this->params['matriculas']) ? $this->params['matriculas'] : '', 'LTBR', 1, 'J');
        $this->Ln(5);
        
        
        //----------------------------------------------------------------
        $this->Ln(20);
        $this->SetFont('Arial', '', 12);

        if( isset($this->params['tipo_requerimento']) ) {
            $this->params['tipo_requerimento'] = "que trata sobre {$this->params['tipo_requerimento']}, ";
        }
        else {
            $this->params['tipo_requerimento'] = '';
        }
        $texto = 'Eu, servidor acima identificado, venho requerer  a Vsª se digne conceder A REVISÂO da decisão administrativa ' .
                "(obrigatoriamente anexada) relativa ao protocolo / inscrição nº {$this->params['protocolo']}, {$this->params['tipo_requerimento']}pelos motivos expostos a seguir:";
        $this->MultiCell(540, 16, $texto, 0, 'J');
        $this->Ln(10);
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(540, 16,"{$this->params['motivo']}", 'LRBT', 'J');

        $this->Ln(20);
        $this->MultiCell(540, 16, 'Nestes termos', 0, 'J');
        $this->MultiCell(540, 16, 'Pede Deferimento', 0, 'J');

        $this->Ln(30);
        $this->MultiCell(540, 16, '____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(540, 16, 'Assinatura do(a) Requerente', 0, 'C');

        $this->Ln(50);
        $dia = date("d");
        $mes = self::mesNumericoParaExtenso( date("m") );
        $ano = date("Y");
        $this->MultiCell(540, 14, "Itajaí, $dia de $mes de $ano", 0, 'R');
        
        if ($via == 1 || $via == 3) {
            $this->Ln(30);
            $this->caixaProtocolo();
        }
        
        $this->Ln(20);
        $this->MultiCell(540, 16,'Anexar Documentos comprobatórios que justifiquem o pedido de recurso.', 'LRBT', 'J');
    }

}
