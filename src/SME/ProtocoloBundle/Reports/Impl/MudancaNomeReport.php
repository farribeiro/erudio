<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class MudancaEnderecoReport extends RequerimentoReport
{
    
    public function prepareDocument() {
        for ($i = 1; $i <= 3; $i++) {
            $this->via = $i;
            $this->rel($i);
        }
    }

    private function rel($via) {
        $this->titulo = 'REQUERIMENTO DE MUDANÇA DE ENDEREÇO';
        $this->AddPage();
        $this->AliasNbPages();

        $this->SetFont('Arial', '', 12);

        $this->Cell(540, 10, 'Ilustríssimo Senhor Secretário Municipal de Educação', 0, 1, 'J');
        $this->Cell(540, 10, 'NESTA', 0, 1, 'J');
        $this->Ln(15);

                //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(300,10,'Nome', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'CPF', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(135,10,'RG', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(300, 20, $this->params['nome'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['cpf'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(135, 20, $this->params['rg'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(300,10,'Endereço', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'Número', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(135,10,'Complemento', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(300, 20, $this->params['logradouro'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['numero'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(135, 20, $this->params['complemento'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(300,10,'Bairro', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'CEP', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(135,10,'Município', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(300, 20, $this->params['bairro'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['cep'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(135, 20, $this->params['cidade'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(300,10,'Matrículas', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(300, 20, isset($this->params['matriculas']) ? $this->params['matriculas'] : '' , 'LTBR', 1, 'J');
        $this->Ln(5);

        
        //----------------------------------------------------------------
        
        $this->Ln(20);
        $this->SetFont('Arial', '', 12);
       
        $this->MultiCell(540, 16, 'Eu, servidor acima identificado, venho requerer a Vsª se digne conceder a MUDANÇA DE ENDEREÇO para o endereço acima mencionado.', 0, 'J');

        $this->Ln(20);
        $this->MultiCell(540, 16, 'Nestes termos', 0, 'J');
        $this->MultiCell(540, 16, 'Pede Deferimento', 0, 'J');

        $this->Ln(40);
        $this->MultiCell(540, 16, '____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(540, 16, 'Assinatura do(a) Requerente', 0, 'C');

        $this->Ln(50);
        $this->dataCadastro();

        $this->Ln(30);
        $this->caixaProtocolo();
        
    }

}

?>


