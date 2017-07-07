<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class EnquadramentoReport extends RequerimentoReport
{

    public $comissao;
    
    public function prepareDocument() {
        for ($i = 1; $i <= 3; $i++) {
            $this->via = $i;
            $this->rel_requerimento_enquadramento($i);
            if ($i <= 2) {
                $this->rel_manifestacao_comissao($i);
            }
        }
    }

    private function rel_requerimento_enquadramento($via) {
        $this->titulo = 'REQUERIMENTO PARA ENQUADRAMENTO POR CLASSIFICAÇÃO';
        $this->AddPage();
        $this->AliasNbPages();

        $this->SetFont('Arial', '', 12);

        $this->Cell(540, 10, 'Ilustríssimo Senhor Secretário Municipal de Educação', 0, 1, 'J');
        $this->Cell(540, 10, 'NESTA', 0, 1, 'J');
        $this->Ln(15);

        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(390,10,'Nome', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(140,10,'Matrícula', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(390, 20, $this->params['nome'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(140, 20, number_format($this->params['matricula'], 0, ',', '.'), 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);

        $this->Cell(540,10,'Cargo de origem', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(540, 20, $this->params['cargo_origem'], 'LTTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);

        $this->Cell(390,10,'Local de Trabalho', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(140,10,'Data de Nomeação', '', 1, 'J');

        $this->SetFont('Arial','', 10);
        $this->Cell(390, 20, $this->params['local_trabalho'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(140, 20, $this->params['data_nomeacao'], 'LTBR', 1, 'J');
        
        //----------------------------------------------------------------
        $this->Ln(20);
        $this->SetFont('Arial', '', 12);
        $texto = 'O servidor efetivo abaixo assinado, do Quadro do Magistério Público Municipal, ' .
                'atuando na função de ' . $this->params['cargo_atual'] . ', requer a Vsª se digne conceder, ENQUADRAMENTO POR CLASSIFICAÇÃO, ' .
                'do Quadro Especial para o Quadro Permanente de Pessoal do Magistério, ' .
                'em conformidade com o art. 23 da L.C 132/08, de 02 de abril de 2008.';
        $this->MultiCell(540, 16, $texto, 0, 'J');

        $this->Ln(20);
        $this->MultiCell(540, 16, 'Nestes termos', 0, 'J');
        $this->MultiCell(540, 16, 'Pede Deferimento', 0, 'J');

        $this->Ln(50);
        $this->MultiCell(540, 16, '____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(540, 16, 'Assinatura do(a) Requerente', 0, 'C');

        $this->Ln(50);
        $dia = date("d");
        $mes = self::mesNumericoParaExtenso( date("m") );
        $ano = date("Y");
        $this->MultiCell(540, 14, "Itajaí, $dia de $mes de $ano", 0, 'R');

        if ($via == 1 || $via == 3) {
            $this->Ln(50);
            $this->caixaProtocolo();
        }
    }

    private function rel_manifestacao_comissao($via) {
        $this->titulo = 'MANIFESTAÇÃO DA COMISSÃO';
        $this->AddPage();
        $this->AliasNbPages();

        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(540, 14, '(Portarias 001/2017 - SME)', 0, 'C');

        $this->Ln();

        $this->SetFont('Arial', 'B', 10);
        $this->MultiCell(540, 14, 'REQUERENTE: ' . $this->params['nome'], 0, 'J');
        $this->Cell(400, 14, 'CARGO DE ORIGEM: ' . $this->params['cargo_origem'], 0, 0, 'J');
        $this->Cell(140, 14, 'MATRÍCULA: ' . $this->params['matricula'], 0, 1, 'R');
        $this->MultiCell(540, 14, 'DATA DA NOMEAÇÃO: ' . $this->params['data_nomeacao'], 0, 'J');

        $this->Ln();
        $this->Ln();

        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(540, 14, 'CUMPRIMENTO DOS REQUISITOS PARA PERCEPÇÃO DO ENQUADRAMENTO', 0, 'C');
        $this->MultiCell(540, 14, 'POR CLASSIFICAÇÃO (ART.23 DA LEI 132/08)', 0, 'C');

        $this->Ln();

        $this->SetFont('Arial', '', 10);

        $this->MultiCell(540, 14, '[  ] II - a classificação para o Quadro Permanente de Pessoal, mediante comprovação da formação necessária, prevista no Manual de Ocupações do Quadro Permanente de Pessoal, que constitui o Anexo I-B;', 0, 'J');

        $this->Ln();
        $this->SetFont('Arial', 'B', 12);

        $this->MultiCell(540, 14, 'VOTOS DA COMISSÃO', 0, 'J');

        $this->SetFont('Arial', '', 10);

        // Definição dos Membros da Comissão
        $this->comissao = array();
        $this->comissao[] = 'Patrícia A.A. Obelar Coelho';
        $this->comissao[] = 'Viviane F. Dittrich de Souza';
        $this->comissao[] = 'Marcelo Bomfim Caetano';
        $this->comissao[] = 'Clara S. Ignacio de Mendonça';
        $this->comissao[] = 'Claudio da Silva';

        foreach ($this->comissao as $C) {
            $this->Cell(150, 16, 'Voto do membro da Comissão:', 'LTB', 0, 'J');
            $this->Cell(200, 16, $C, 'TB', 0, 'J');
            $this->Cell(190, 16, 'Voto:', 'TBR', 1, 'J');
        }

        $this->Ln();

        $this->MultiCell(540, 14, 'POR MAIORIA, com base nas portarias 001/2017, e na Lei Complementar 132/2008 (alterada pelas LC 194 e 195/2011),', 0, 'J');

        $this->Ln();

        $this->MultiCell(540, 14, '[  ] Enquadramos o(a) servidor(a) para o Quadro Permanente de Pessoal a contar de __/__/__', 0, 'J');
        $this->MultiCell(540, 14, '[  ] Indeferimos o pedido do(a) requerente pelo motivo:', 0, 'J');
        $this->MultiCell(540, 14, '', 'B', 'J');
        $this->MultiCell(540, 14, '', 'B', 'J');
        $this->MultiCell(540, 14, '(do indeferimento dos títulos, caberá pedido de reconsideração, no prazo de cinco dias úteis)', 0, 'J');

        $this->Ln();

        $this->SetFont('Arial', 'B', 10);
        $this->MultiCell(540, 14, '*Art. 23 O acréscimo pecuniário decorrente do enquadramento por classificação será pago (LC/132/2008)', 0, 'J');

        $this->SetFont('Arial', '', 10);
        $this->MultiCell(540, 14, 'Todos os efeitos funcionais e financeiros decorrentes da classificação [...] se produzirão a partir da data da homologação do respectivo enquadramento, por comissão verificadora constituída para este fim. (Redação dada pela Lei Complementar nº 194/2011).', 0, 'J');

        $this->Ln();

        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(540, 14, 'COMUNICAÇÃO DESTA DECISÃO', 0, 'J');
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(540, 14, 'Comunique-se esta decisão a(o) requerente, pessoalmente, por resposta ao requerimento ou portaria publicada no Jornal do Município.', 0, 'J');

        $this->Ln();
        $this->Ln();

        $this->SetFont('Arial', '', 12);
        $this->MultiCell(540, 14, 'Itajaí, ________________de ________ de ___________', 0, 'R');
    }

}