<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class DesincompatibilizacaoReport extends RequerimentoReport
{
    
    public function prepareDocument() {
        for ($i = 1; $i <= 3; $i++) {
            $this->via = $i;
            $this->rel($i);
        }
    }

    private function rel($via) {
        $this->titulo = 'REQUERIMENTO DE AFASTAMENTO A TÍTULO DE DESINCOMPATIBILIZAÇÃO';
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
        
        
        /*
        foreach( $this->controle->getMatriculas(0,$this->solicitante->pessoaId) as $i=>$atuacao )
        {
            $n = $i + 1;
            $this->SetFont('Arial','', 9);
            $this->Cell(80,10,"Matrícula $n", '', 0, 'J');
            $this->Cell(10,10,' ', '', 0, 'J');
            $this->Cell(320,10,'Cargo de origem', '', 0, 'J');
            $this->Cell(10,10,' ', '', 0, 'J');
            $this->Cell(80,10,'Vínculo', '', 0, 'J');
            $this->Cell(10,10,' ', '', 0, 'J');
            $this->Cell(30,10,'C.H.', '', 1, 'J');

            $this->SetFont('Arial', '', 10);
            $this->Cell(80, 16, $atuacao['numero'], 'LTBR', 0, 'J');
            $this->Cell(10, 16,'','', 0, 'J');
            $this->Cell(320, 16, $atuacao['cargo'], 'LTBR', 0, 'J');
            $this->Cell(10, 16,'','', 0, 'J');
            $this->Cell(80, 16, $atuacao['vinculo'], 'LTBR', 0, 'J');
            $this->Cell(10, 16,'','', 0, 'J');
            $this->Cell(30, 16, $atuacao['carga_horaria'], 'LTBR', 1, 'J');
            $this->Ln(5);
        }*/
        
        //----------------------------------------------------------------
        $this->Ln(20);
        $this->SetFont('Arial', '', 12);
        $texto = 'Eu, servidor acima identificado, venho requerer a Vsª se digne conceder o AFASTAMENTO A TÍTULO DE DESINCOMPATIBILIZAÇÃO, ' .
                "por estar concorrendo ao cargo eletivo de {$this->params['cargo_eletivo']}, nos termos da Lei Complementar nº64 de 18 de maio de 1990, " .
                "no pleito de {$this->params['pleito']}, a partir de {$this->params['data_inicio']}.";
        $this->MultiCell(540, 16, $texto, 0, 'J');
        $this->Ln(20);
        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(540, 16, 'TERMO DE COMPROMISSO', 'TRL', 'C');
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(540, 16, 'EU, servidor acima identificado, me comprometo a entregar o Registro de Candidatura expedido pelo Tribunal Regional Eleitoral, a Ata da Convenção e Lista de Aprovados  conforme calendário eleitoral, na Diretoria de Gestão de Pessoas da Secretaria Municipal de Educação, no prazo previsto, bem como informar eventual impugnação de minha candidatura.','BRL', 'J');
        $this->Ln(20);
        $this->MultiCell(540, 16, 'Nestes termos', 0, 'J');
        $this->MultiCell(540, 16, 'Pede Deferimento', 0, 'J');

        $this->Ln(20);
        $this->MultiCell(540, 16, '____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(540, 16, 'Assinatura do(a) Requerente', 0, 'C');

        $this->Ln(30);
        $this->dataCadastro();

        $this->Ln(30);
        $this->caixaProtocolo();
    }

}