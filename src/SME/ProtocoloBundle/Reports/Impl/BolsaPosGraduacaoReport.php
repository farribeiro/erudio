<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class BolsaPosGraduacaoReport extends RequerimentoReport
{
    
    public function prepareDocument() {
                for ($i = 1; $i <= 3; $i++) {
            $this->via = $i;
            $this->rel_primeira_pagina($i);
            if ($i <= 2) {
                $this->rel_segunda_pagina($i);
            }
        }
    }

    private function rel_primeira_pagina($via) {
        $this->titulo = 'REQUERIMENTO DE AUXÍLIO FINANCEIRO PARA CURSO DE PÓS-GRADUAÇÃO EM NÍVEL DE MESTRADO E DOUTORADO (somente efetivos)';
        $this->AddPage();
        $this->AliasNbPages();

        $this->SetFont('Arial', '', 10);

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
        
        //Requerimentos a partir de abril de 2014
        if(isset($this->params['matricula']) && isset($this->params['cargo_atual']))
        {
            $this->SetFont('Arial','', 9);
            $this->Cell(350,10,'Cargo Atual', '', 0, 'J');
            $this->Cell(10,10,' ', '', 0, 'J');
            $this->Cell(180,10,'Matrícula', '', 1, 'J');

            $this->SetFont('Arial', '', 10);
            $this->Cell(350, 20, $this->params['cargo_atual'], 'LTBR', 0, 'J');
            $this->Cell(10, 20,'','', 0, 'J');
            $this->Cell(180, 20, $this->params['matricula'], 'LTBR', 1, 'J');
            $this->Ln(5);
        }

        $this->Ln(15);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(540, 20, "Curso: {$this->params['curso']}", 'TRL','J');
        $this->MultiCell(540, 20, "Instituição: {$this->params['instituicao']}", 'RL','J');
        $this->MultiCell(540, 20, "Início do curso: {$this->params['data_inicio']}   Término do curso: {$this->params['data_termino']}", 'BRL','J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->Ln(10);
        $this->SetFont('Arial', '', 10);
        $texto = "Eu, servidor acima identificado, atuando como {$this->params['cargo_atual']}, venho requerer a Vsª se digne conceder o AUXÍLIO FINANCEIRO DE 50% DA" .
                " MENSALIDADE DO CURSO PARA CURSAR {$this->params['curso']}, de acordo com a Lei nº4.075, art. 1, I e II:";
        $this->MultiCell(540, 16, $texto, 0, 'J');
        $this->Ln(10);
        $this->SetFont('Arial', '', 8);
        $this->Cell(100,60,'','',0,'J');        
        $this->MultiCell(420,10,'"Art 1º - O servidor efetivo do quadro funcional da Secretaria Municipal de Educação, para frequentar o curso de pós-graduação em nível de mestrado ou doutorado, nas unidades de Ensino Superior Federais e Estaduais, ou outra Instituição de Ensino Superior autorizada ou reconhecida pelo Ministério da Educação (MEC) ou pela CAPES - Coordenação de Aperfeiçoamento de Pessoal de Nível Superior, poderá: (Redação dada pela Lei nº 6704/2016)', 0, 'J');
        $this->Cell(100,60,'','',0,'J');
        $this->MultiCell(420,10,'II - permanecer no cargo, cumprindo a carga horária de trabalho, recebendo a titulo de auxílio financeiro para bolsa de estudo, 50 % (cinquenta por cento) da mensalidade do curso.', 0, 'J');
        
        $this->Ln(10);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(540, 12, 'Nestes termos', 0, 'J');
        $this->MultiCell(540, 12, 'Pede Deferimento', 0, 'J');

        $this->Ln(5);
        $this->MultiCell(540, 16, '____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(540, 16, 'Assinatura do(a) Requerente', 0, 'C');
        $this->Ln(20);
        $dia = date("d");
        $mes = self::mesNumericoParaExtenso( date("m") );
        $ano = date("Y");
        $this->MultiCell(540, 14, "Itajaí, $dia de $mes de $ano", 0, 'R');

        $this->Ln(5);
        $this->SetFont('Arial', 'B', 8);
        $this->MultiCell(540, 14, "ANEXAR OS SEGUINTES DOCUMENTOS:", 'TRL', 'C');
        $this->SetFont('Arial', '', 8);
        $this->MultiCell(540, 14, "1) Comprovante de matrícula no curso     2) Grade curricular     3) Certificado de conclusão de graduação", 'RL', 'J');
        $this->MultiCell(540, 14, "4) Portaria de nomeação do servidor        5) Manifestação da chefia imediata", 'RL', 'J');
        $this->MultiCell(540, 14, "6) Justificativa do servidor quanto à aplicação do curso na área de atuação", 'BRL', 'J');
        $this->Ln(10);
        
        if ($via == 1 || $via == 3) {
            $this->Ln(10 );
            $this->caixaProtocolo();
        }
    }

    private function rel_segunda_pagina($via) {
        $this->titulo='';
        $this->AddPage();
        $this->AliasNbPages();
        $this->SetFont('Arial', '', 12);
        $this->Ln(10);
        $this->MultiCell(540, 20, "NOME: {$this->params['nome']}", 0, 'J');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->MultiCell(540, 18, 'CUMPRIMENTO DOS REQUISITOS PARA AUXÍLIO FINANCEIRO PARA CURSO DE PÓS-GRADUAÇÃO EM NÍVEL DE MESTRADO E DOUTORADO', 0, 'C');
        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(540, 18, 'Para preenchimento da Diretoria de Gestão de Pessoas', 0, 'C');
        $this->Ln(15);
        $this->SetFillColor(230);
        
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(540, 18, 'Art. 2: O benefício de que trata esta lei somente será concedido se atendidos os seguintes requisitos:', 'TLRB', 'J');
        $celulas = array('I - Que o servidor: (Assinalar com X  o requisito que estiver plenamente atendido)',
            '(  ) a) - não esteja cumprindo Estágio Probatório',
            '(  ) b) - não esteja em exercício de Cargo em Comissão ou Função de Confiança',
            '(  ) c) - não tenha gozado de licença sem vencimento ou ficado à disposição de órgãos não pertencentes ao Município, nos últimos três anos;',
            '(  ) d) - não tenha sofrido aplicação de pena disciplinar;',
            '(  ) e) - não conte com tempo de serviço, para fins de aposentadoria.',
            'II - Que o curso pretendido seja: (Assinalar com X  o requisito que estiver plenamente atendido)',
            '(  ) a) - compatível com os interesses e objetivos da Secretaria de Educação do Município de Itajaí e da Administração Pública Municipal;',
            '(   ) b) - afim com o cargo e a área de atuação do interessado, no serviço público municipal;',
            '(  ) c) - autorizado ou reconhecido pelo órgão federal ou estadual de educação que tiver competência, nos termos da legislação.');
        foreach($celulas as $i=>$celula) {
            if($i == 0 || $i == 6 ) 
                $cor = true;
            else 
                $cor = false;
            $this->MultiCell(540, 20, $celula, 'LRB', 'J',$cor);
        }
        
        $this->Ln(15);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(540, 14, 'PARECER DA SECRETARIA DE EDUCAÇÃO', 'LTR', 1, 'C', FALSE);
        $this->SetFont('Arial', '', 12);
        $this->Cell(116, 20, '( ) Deferido', 'LTR', 0, 'L', false);
        $this->Cell(424, 20, '( ) Indeferido. MOTIVO:', 'LTR', 1, 'L', false);
        $this->Cell(116, 50, '', 'LBR', 0, 'C', false);
        $this->Cell(424, 50, '', 'LBR', 1, 'C', false);
        
        $this->Ln(30);
        $this->MultiCell(540, 14, "Data: ___/___/______", 0, 'R');
        $this->Ln(50);
        $this->MultiCell(540, 16, '____________________________________          ____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(540, 16, "{$this->assinaturas['diretoriaDGP']}                                                   {$this->assinaturas['secretario']}", 0, 'C');
        $this->Ln(1);
        $this->MultiCell(540, 16, 'Diretoria de Gestão de Pessoas                                   Secretário(a) de Educação', 0, 'C');

    }
    
}