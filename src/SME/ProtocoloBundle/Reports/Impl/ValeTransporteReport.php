<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class ValeTransporteReport extends RequerimentoReport
{

    public function prepareDocument() {
        for ($i = 1; $i <= 3; $i++) {
            $this->via = $i;
            //if($i!=2) {
                $this->primeiraFolha($i);
                $this->segundaFolha($i);
            //}
        }
    }

    private function primeiraFolha($via) {
        $this->titulo = 'REQUERIMENTO DE VALE TRANSPORTE';
        $this->AddPage('L');
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
        $this->Cell(100,10,'RG', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(275,10,'Nome da mãe', '', 1, 'J');

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(300, 16, $this->params['nome'], 'LTBR', 0, 'J');
        $this->Cell(10, 16,'','', 0, 'J');
        $this->Cell(85, 16, $this->params['cpf'], 'LTBR', 0, 'J');
        $this->Cell(10, 16,'','', 0, 'J');
        $this->Cell(100, 16, $this->params['rg'], 'LTBR', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(275,16,$this->params['nome_mae'], 'LBRT', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(120,10,'Data de nascimento', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(260,10,'Endereço', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(50,10,'Número', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(130,10,'Bairro', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(60,10,'CEP', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(120,10,'Município', '', 1, 'J');

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(120, 16, $this->params['dataNascimento'], 'LTBR', 0, 'J');
        $this->Cell(10,16,' ', '', 0, 'J');
        $this->Cell(260, 16, $this->params['logradouro'], 'LTBR', 0, 'J');
        $this->Cell(10, 16,'','', 0, 'J');
        $this->Cell(50, 16, $this->params['numero'], 'LTBR', 0, 'J');
        $this->Cell(10, 16,'','', 0, 'J');
        $this->Cell(130, 16, $this->params['bairro'], 'LTBR', 0, 'J');
        $this->Cell(10, 16,'','', 0, 'J');
        $this->Cell(60, 16, $this->params['cep'], 'LTBR', 0, 'J');
        $this->Cell(10, 16,'','', 0, 'J');
        $this->Cell(120, 16, $this->params['cidade'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        /* REVISAR
        foreach( $this->controle->getAtuacoes(0,$this->solicitante->pessoaId) as $i=>$atuacao )
        {
            $n = $i + 1;
            $this->SetFont('Arial','', 9);
            $this->Cell(280,10,"Local de trabalho $n", '', 0, 'J');
            $this->Cell(10,10,' ', '', 0, 'J');
            $this->Cell(260,10,'Função', '', 0, 'J');
            $this->Cell(10,10,' ', '', 0, 'J');
            $this->Cell(60,10,'Período', '', 0, 'J');
            $this->Cell(10,10,' ', '', 0, 'J');
            $this->Cell(120,10,'Dias da semana', '', 0, 'J');
            $this->Cell(10,10,' ', '', 0, 'J');
            $this->Cell(30,10,'C.H.', '', 1, 'J');

            $this->SetFont('Arial', 'B', 10);
            $this->Cell(280, 16, $atuacao['local'], 'LTBR', 0, 'J');
            $this->Cell(10, 16,'','', 0, 'J');
            $this->Cell(260, 16, $atuacao['funcao'], 'LTBR', 0, 'J');
            $this->Cell(10, 16,'','', 0, 'J');
            $this->Cell(60, 16, $atuacao['periodo'], 'LTBR', 0, 'J');
            $this->Cell(10, 16,'','', 0, 'J');
            $this->Cell(120, 16, $atuacao['dias_semana'], 'LTBR', 0, 'J');
            $this->Cell(10, 16,'','', 0, 'J');
            $this->Cell(30, 16, $atuacao['carga_horaria'], 'LTBR', 1, 'J');
            $this->Ln(5);
        }*/
        
        //----------------------------------------------------------------
        for($i = 1; $i <= 3; $i++) {
            if($i == 1 || isset($this->params['empresa_' . $i])) {
                $this->SetFont('Arial', '', 9);
                $this->Cell(230, 10, "Empresa de transporte {$i}", '', 0, 'J');
                $this->Cell(10, 10, ' ', '', 0, 'J');
                $this->Cell(50, 10, 'Qtd. diária', '', 0, 'J');
                $this->Cell(10, 10, ' ', '', 0, 'J');
                $this->Cell(80, 10, 'Cartão SIM nº', '', 0, 'J');
                $this->Cell(10, 10, ' ', '', 0, 'J');
                $this->Cell(400, 10, 'Itinerários', '', 1, 'J');

                //Questão de retrocompatibilidade com os requerimentos que só possuíam uma empresa.
                $sufixo = '';
                if($i > 1) {
                    $sufixo = '_' . $i;
                }
                $this->SetFont('Arial', 'B', 10);
                $this->Cell(230, 16, $this->params['empresa' . $sufixo], 'LTBR', 0, 'J');
                $this->Cell(10, 16, '', '', 0, 'J');
                $this->Cell(50, 16, $this->params['quantidade_diaria' . $sufixo], 'LTBR', 0, 'J');
                $this->Cell(10, 16, '', '', 0, 'J');
                $this->Cell(80, 16, $this->params['cartao_sim' . $sufixo], 'LTBR', 0, 'J');
                $this->Cell(10, 16, ' ', '', 0, 'J');
                $this->Cell(400, 16, $this->params['itinerarios' . $sufixo], 'LTBR', 1, 'J');
                $this->Ln(5);
            }
        }
        
    }
    
    private function segundaFolha($via) {
        $this->titulo = 'TERMO DE COMPROMISSO';
        $this->AddPage('L');
        $this->AliasNbPages();
        
        $this->SetFont('Arial','', 12);
        $this->Ln(20);
        $texto = "Eu, {$this->params['nome']}, venho requerer a Vsª se digne conceder o VALE TRANSPORTE. "
            . 'Através do presente, firmo compromisso com a Prefeitura de Itajaí, de utilizar o vale-transporte '
            . 'exclusivamente para o meu deslocamento diário, residência/trabalho e vice-versa, sendo que declaração '
            . 'falsa ou uso indevido do vale transporte constitui falta grave sujeita a sanções disciplinares. '
            . 'Outrossim, autorizo o desconto da parcela relativa aos 6% (seis por cento) sob meu vencimento.';
        $this->MultiCell(800,12, $texto, 0, 'J');

        $this->Ln(20);
        $this->MultiCell(800, 12, 'Nestes termos', 0, 'J');
        $this->MultiCell(800, 12, 'Pede Deferimento', 0, 'J');

        $this->Ln(10);
        $this->MultiCell(800, 16, '____________________________________          ____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(800, 16, '                Assinatura do(a) Requerente                    Ass. e carimbo do Gestor da Unidade Escolar', 0, 'C');

        $this->Ln(15);
        $this->dataCadastro(800,16);

        $this->caixaProtocolo();
        
    }
    
}