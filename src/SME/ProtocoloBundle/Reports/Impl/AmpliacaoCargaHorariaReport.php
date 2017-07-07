<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class AmpliacaoCargaHorariaReport extends RequerimentoReport
{
    
    public function prepareDocument() {
        $this->titulo = 'REQUERIMENTO DE AMPLIAÇÃO DE CARGA HORÁRIA (ACTs)';
        $this->via = 1;
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
        $this->Cell(350,10,'Cargo / disciplina', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'Carga horária atual', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'Classificação', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(350, 20, $this->params['cargo_origem'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['carga_horaria'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['vinculacao_classificacao'], 'LTBR', 1, 'J');
        $this->Ln(20);
         
        //----------------------------------------------------------------
        
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(540, 10, 'CARGA HORÁRIA QUE DESEJA AMPLIAR:', 0, 1, 'J');
        $this->Ln(5);

        $this->SetFont('Arial', '', 10);
        $this->Cell(280, 20, $this->params['carga_horaria_disponivel'] . ' horas', 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(250, 20, $this->params['periodo_disponivel'], 'LTBR', 1, 'J');
        $this->Ln(5);

        //----------------------------------------------------------------
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
        
        $this->Ln(30);
        $this->caixaProtocolo();
        $this->Ln(15);
        $this->MultiCell(540, 16,'* Este requerimento é inteiramente online e não necessita ser entregue na forma impressa.', '', 'J');
        
        $this->Ln(40);
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
