<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;
use SME\ProtocoloBundle\Entity\Protocolo;

class LicencaPremioReport extends RequerimentoReport
{
    
    public function prepareDocument() {
        for ($i = 1; $i <= 3; $i++) {
            $this->via = $i;
            $this->rel($i);
        }
    }

    private function rel($via) {
        $this->titulo = 'REQUERIMENTO DE LICENÇA-PRÊMIO (Lei 2791/93 e Decreto 9717/12)';
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
        $this->Cell(80,10,'Matrícula', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(400,10,'Cargo de origem', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(40,10,'C.H.', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(80, 20, $this->params['matricula'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(400, 20, $this->params['cargo_origem'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(40, 20, $this->params['carga_horaria'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(100, 10,'Vículo', '', 0, 'J');
        $this->Cell(10, 10,' ', '', 0, 'J');
        $this->Cell(100, 10,'Data de Nomeação', '', 0, 'J');
        $this->Cell(10, 10,' ', '', 0, 'J');
        $this->Cell(320, 10,'Local de Trabalho', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(100, 20, $this->params['vinculo'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(100, 20, $this->params['data_nomeacao'], 'LTBR', 0, 'J');
        $this->Cell(10, 10,' ', '', 0, 'J');
        $this->Cell(320, 20, isset($this->params['local_trabalho']) ? $this->params['local_trabalho'] : '[Não informado]', 'LBTR', 1, 'J');

        $this->Ln(5);
        
        //----------------------------------------------------------------
        
        $this->Ln(20);
        $this->SetFont('Arial', '', 12);
       
        $periodo = $this->params['conversao_abono'] == 'Sim'
                ? '02 (dois) meses, sendo 1/3 (um terço) convertido em abono pecuniário e o restante a ser usufruído'
                : '03 (três) meses,';
        
        $this->MultiCell(540, 16, 'O servidor abaixo assinado, do Quadro do Magistério Público Municipal, requer a Vsª se digne conceder ' .
        "LICENÇA-PRÊMIO, referente ao quinquênio de {$this->params['quinquenio']}, pelo período de {$periodo} a contar de {$this->params['data_inicio']}.", 0, 'J');
        $this->Ln(10);
        $this->SetFont('Arial', '', 12);
        $this->Ln(20);
        $this->MultiCell(540, 16, 'Nestes termos', 0, 'J');
        $this->MultiCell(540, 16, 'Pede Deferimento', 0, 'J');

        $this->Ln(40);
        $this->MultiCell(540, 16, '____________________________________          ____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(540, 16, '                Assinatura do(a) Requerente                    Ass. e carimbo do Gestor da Unidade Escolar', 0, 'C');

        $this->Ln(50);
        $dia = date("d");
        $mes = self::mesNumericoParaExtenso( date("m") );
        $ano = date("Y");
        $this->MultiCell(540, 14, "Itajaí, $dia de $mes de $ano", 0, 'R');

        if ($via == 1 || $via == 3) {
            $this->Ln(30);
            $this->caixaProtocolo();
        }
    }

}

?>

