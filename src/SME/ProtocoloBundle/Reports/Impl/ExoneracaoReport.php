<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class ExoneracaoReport extends RequerimentoReport
{
    
    public function prepareDocument() {
        for ($i = 1; $i <= 3; $i++) {
            $this->via = $i;
            $this->rel($i);
        }
    }

    private function rel($via) {
        $this->titulo = 'REQUERIMENTO DE EXONERAÇÃO (efetivos e comissionados)';
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
        $this->Cell(140,10,'Matrícula', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(290,10,'Cargo de origem', '', 0, 'J');
        
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(80,10,'C.H.', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(140, 20, $this->params['matricula'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(290, 20, $this->params['cargo_origem'], 'LTBR', 0, 'J');
        
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(80, 20, $this->params['carga_horaria'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(100,10,'Vículo', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(100,10,'Data de Nomeação', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(100, 20, $this->params['vinculo'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(100, 20, $this->params['data_nomeacao'], 'LTBR', 1, 'J');

        $this->Ln(5);
        
        //----------------------------------------------------------------
        
        $this->Ln(20);
        $this->SetFont('Arial', '', 12);
       
        $texto = "O servidor abaixo assinado requer a Vsª se digne conceder EXONERAÇÃO do cargo {$this->params['vinculo_exoneracao']} de " .
                "{$this->params['cargo_exoneracao']} - {$this->params['ch_exoneracao']}, vinculado à matrícula {$this->params['matricula']}, a partir da data {$this->params['data_desligamento']}.";
        $this->MultiCell(540, 16, $texto, 0, 'J');

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
