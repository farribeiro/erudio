<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class PermutaReport extends RequerimentoReport
{
    
    public function prepareDocument() {
        for ($i = 1; $i <= 3; $i++) {
            $this->via = $i;
            $this->rel($i);
        }
    }

    private function rel($via) {
        $this->titulo = 'REQUERIMENTO DE SOLICITAÇÃO DE PERMUTA';
        $this->AddPage();
        $this->AliasNbPages();

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(540, 10, 'REQUERENTE', 0, 1, 'J');
        $this->Ln(5);

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
        $this->Cell(350,10,'Local de trabalho atual', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'Telefone', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'Celular', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(350, 20, $this->params['local_trabalho'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['telefone'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['celular'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(350,10,'Cargo de origem', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(180,10,'Matrícula', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(350, 20, $this->params['cargo_origem'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(180, 20, $this->params['matricula'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(350,10,'Carga horária', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(180,10,'Período', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(350, 20, $this->params['carga_horaria'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(180, 20, $this->params['periodo'], 'LTBR', 1, 'J');
        $this->Ln(15);
        
        
        //----------------------------------------------------------------
        
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(540, 10, 'VAGA PARA PERMUTA:', 0, 1, 'J');
        $this->Ln(5);

        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(280,10,'Local', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(250,10,'Servidor com o qual irá permutar', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(280, 20, $this->params['permuta_local'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(250, 20, $this->params['permuta_servidor'], 'LTBR', 1, 'J');
        $this->Ln(5);

        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(350,10,'Cargo de origem', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(180,10,'Matrícula', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(350, 20, $this->params['permuta_cargo'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(180, 20, isset($this->params['permuta_matricula']) ? $this->params['permuta_matricula'] : '', 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(250,10,'Disciplina', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(90,10,'Carga horária', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(180,10,'Período', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(250, 20, $this->params['permuta_disciplina'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(90, 20, $this->params['permuta_ch'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(180, 20, $this->params['permuta_periodo'], 'LTBR', 1, 'J');
        $this->Ln(30);
        
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(540, 16, 'Nestes termos', 0, 'J');
        $this->MultiCell(540, 16, 'Pede Deferimento', 0, 'J');

        $this->Ln(15);
        $this->MultiCell(540, 16, '____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(540, 16, 'Assinatura do(a) Requerente', 0, 'C');

        $this->Ln(15);
        $dia = date("d");
        $mes = self::mesNumericoParaExtenso( date("m") );
        $ano = date("Y");
        $this->MultiCell(540, 14, "Itajaí, $dia de $mes de $ano", 0, 'R');
        
        if ($via == 1 || $via == 3) {
            $this->Ln(30);
            $this->caixaProtocolo();
        }
        
        $this->Ln(30);
        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(540, 16,'Dados da Secretaria Municipal de Educação', 'B', 'J');
        $this->SetFont('Arial', '', 12);
        $this->Ln(10);
        $this->MultiCell(540, 16,'(  ) Deferido', '', 'J');
        $this->MultiCell(540, 16,'(  ) Indeferido', '', 'J');
        $this->Ln(15);
        $this->MultiCell(540, 16, '____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(540, 12, $this->assinaturas['diretoriaDGP'], 0, 'C');
        $this->SetFont('Arial', 'B', 10);
        $this->MultiCell(540, 12, 'Diretor(a) de Gestão de Pessoas', 0, 'C');
    }
    
}