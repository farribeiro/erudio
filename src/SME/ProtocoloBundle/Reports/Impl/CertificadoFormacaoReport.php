<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class CertificadoFormacaoReport extends RequerimentoReport
{
    
    public function prepareDocument() {
        for ($i = 1; $i <= 3; $i++) {
            $this->via = $i;
            if($i!=2) {
                $this->rel($i);
            }
        }
    }

    private function rel($via) {
        $this->titulo = 'REQUERIMENTO DE CERTIFICADO DE FORMAÇÃO CONTINUADA';
        $this->AddPage();
        $this->AliasNbPages();

        $this->SetFont('Arial', '', 12);

        $this->Cell(540, 10, 'Ilustríssimo Senhor Secretário Municipal de Educação', 0, 1, 'J');
        $this->Cell(540, 10, 'NESTA', 0, 1, 'J');
        $this->Ln(15);

        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(440,10,'Nome', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(90,10,'CPF', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(440, 20, $this->params['nome'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(90, 20, $this->params['cpf'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(440,10,'Local de Trabalho', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(90,10,'Matrícula', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(440, 20, $this->params['localTrabalho'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(90, 20, $this->params['matricula'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        
        //----------------------------------------------------------------
        $this->Ln(20);
        $this->SetFont('Arial', '', 12);
        $texto = 'O servidor público abaixo assinado requer a Vsª se digne conceder a EMISSÃO DO CERTIFICADO DE FORMAÇÃO CONTINUADA referente ao curso ' .
                "{$this->params['curso']}, realizado entre as datas de {$this->params['dataInicio']} e {$this->params['dataTermino']}, com carga horária de {$this->params['cargaHoraria']} horas.";
        $this->MultiCell(540, 16, $texto, 0, 'J');
        $this->Ln(30);
        $this->MultiCell(540, 16, 'Outras informações úteis para facilitar a busca: ' . $this->params['complemento'], 0, 'J');
        $this->Ln(30);
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