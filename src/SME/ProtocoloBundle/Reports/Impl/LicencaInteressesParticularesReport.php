<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class LicencaInteressesParticularesReport extends RequerimentoReport
{
    
    public function prepareDocument() {
        for ($i = 1; $i <= 3; $i++) {
            $this->via = $i;
            $this->rel($i);
        }
    }

    private function rel($via) {
        $this->titulo = 'REQUERIMENTO DE LICENÇA PARA TRATAR DE INTERESSES PARTICULARES (somente para efetivos)';
        $this->subtitulo = 'Lei 2960/95 Art. 94 e 95; Lei 1920/81 Art. 117 a 120';
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
        $this->Cell(75,10,'Data de nomeação', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(75,10,'Matrícula', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(370,10,'Cargo de Origem', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(75, 20, $this->params['data_nomeacao'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(75, 20, $this->params['matricula'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(370, 20, $this->params['cargo_origem'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->Ln(20);
        $this->SetFont('Arial', '', 12);
        $texto = 'O servidor efetivo abaixo assinado, requer a Vsª se digne conceder LICENÇA PARA TRATAR DE INTERESSES PARTICULARES ' .
                "a partir de {$this->params['data_inicio']} até {$this->params['data_encerramento']} .";
        $this->MultiCell(540, 16, $texto, 0, 'J');
        $this->Ln(20);
        $this->MultiCell(540, 16, 'Nestes termos', 0, 'J');
        $this->MultiCell(540, 16, 'Pede Deferimento', 0, 'J');

        $this->Ln(50);
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