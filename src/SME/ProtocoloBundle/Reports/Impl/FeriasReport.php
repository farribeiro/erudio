<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class FeriasReport extends RequerimentoReport
{
    
    public function prepareDocument() {
        for ($i = 1; $i <= 3; $i++) {
            $this->via = $i;
            $this->rel($i);
        }
    }

    private function rel($via) {
        $this->titulo = 'REQUERIMENTO DE FÉRIAS';
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
        $this->Cell(145,10,'Matrícula', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(190,10,'Cargo de origem', '', 0, 'J');
        
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(145,10,'Vínculo', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(30,10,'C.H.', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(145, 20, $this->params['matricula'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(190, 20, $this->params['cargo_origem'], 'LTBR', 0, 'J');
        
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(145, 20, $this->params['vinculo'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(30, 20, $this->params['carga_horaria'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        
        //----------------------------------------------------------------
        $this->Ln(20);
        $this->SetFont('Arial', '', 12);
        $texto = 'Eu, servidor acima identificado, venho requerer a Vsª se digne conceder FÉRIAS, ' .
            "referentes ao Período Aquisitivo {$this->params['periodo_aquisitivo']}, para serem usufruídas a contar de {$this->params['data_inicio']}.";
        $this->MultiCell(540, 16, $texto, 0, 'J');
        $this->Ln(5);
        $this->MultiCell(540, 16, "Já usufruiu? {$this->params['usurfruiu']}.", 0, 'J');
        $this->Ln(5);
        $this->MultiCell(540, 16, "Conversão de 10 dias em Abono Pecuniário? {$this->params['conversao_abono']}.", 0, 'J');

        $this->Ln(10);
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