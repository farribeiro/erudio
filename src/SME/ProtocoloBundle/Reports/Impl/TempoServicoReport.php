<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class TempoServicoReport extends RequerimentoReport
{
    
    public function prepareDocument() {
        $this->titulo = 'REQUERIMENTO DE TEMPO DE SERVIÇO NO MAGISTÉRIO PÚBLICO MUNICIPAL';
        $this->via = 1;
        $this->AddPage();
        $this->AliasNbPages();

        $this->SetFont('Arial', '', 12);

        $this->Cell(540, 10, 'Ilustríssimo Senhor Secretário Municipal de Educação', 0, 1, 'J');
        $this->Cell(540, 10, 'NESTA', 0, 1, 'J');
        $this->Ln(15);

        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(320,10,'Nome', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(100,10,'CPF', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(100,10,'RG', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(320, 20, $this->params['nome'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(100, 20, $this->params['cpf'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(100, 20, $this->params['rg'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(320,10,'E-mail', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(100,10,'Telefone', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(100,10,'Celular', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(320, 20, $this->params['email'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(100, 20, $this->params['telefone'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(100, 20, $this->params['celular'], 'LTBR', 1, 'J');
        $this->Ln(5);
        

        
        //----------------------------------------------------------------
        
        $this->Ln(20);
        $this->SetFont('Arial', '', 12);
       
        $this->MultiCell(540, 16, "Eu, servidor acima identificado, venho requerer a Vsª se digne conceder DECLARAÇÃO DE TEMPO DE SERVIÇO para fins de {$this->params['objetivo']}.", 0, 'J');

        $this->Ln(20);
        $this->MultiCell(540, 16, 'Nestes termos', 0, 'J');
        $this->MultiCell(540, 16, 'Pede Deferimento', 0, 'J');

        $this->Ln(30);
        $this->MultiCell(540, 16, '____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(540, 16, 'Assinatura do(a) Requerente', 0, 'C');

        $this->Ln(50);
        $this->dataCadastro();

        $this->Ln(20);
        $this->caixaProtocolo();
    }

}

?>
