<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class AfastamentoPosGraduacaoReport extends RequerimentoReport
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
        $this->titulo = 'REQUERIMENTO DE AFASTAMENTO PARA CURSO DE PÓS-GRADUAÇÃO EM NÍVEL DE MESTRADO E DOUTORADO (somente efetivos)';
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

        $this->Ln(10);
        $this->SetFont('Arial', '', 10);
        $texto = "Eu, servidor acima identificado, atuando como {$this->params['cargo_atual']}, venho requerer a Vsª se digne conceder o AFASTAMENTO PARA " .
                "CURSAR {$this->params['curso']} de acordo com a Lei nº4.075, art. 3, parágrafo único:";
        $this->MultiCell(540, 12, $texto, 0, 'J');
        $this->SetFont('Arial', '', 8);
        $this->Cell(100,60,'','',0,'J');
        $this->MultiCell(420,10,'"O afastamento para frequentar curso de pós-graduação stricto sensu, na modalidade de programas de mestrado ou doutorado, poderá ser integral ou envolver somente parte da jornada de trabalho do servidor. Parágrafo Único - Quando total, o prazo máximo para afastamento frequentando curso de pós-graduação será de:', 0, 'J');
        $this->Cell(100,60,'','',0,'J');
        $this->MultiCell(420,10,'I - 2 (dois) anos para mestrado e;', 0, 'J');
        $this->Cell(100,60,'','',0,'J');
        $this->MultiCell(420,10,'II - 3 (três) anos para doutorado."', 0, 'J');
        
        $this->Ln(10);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(540, 16, "AFASTAMENTO:   Início: {$this->params['data_inicio']}     Término: {$this->params['data_termino']}", '', 'J');
        
        $this->Ln(15);
        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(540, 16, 'TERMO DE COMPROMISSO', 'TRL', 'C');
        $this->SetFont('Arial', '', 8);
        $this->MultiCell(540, 10, 'De acordo com a Lei 4.075/2004 art. 4, I, II e III:','RL', 'J');
        $this->MultiCell(540, 10, 'I - ressarcir ao Município todas as despesas, inclusive de remuneração, no caso de desistência ou não cumprimento das condições do Termo de Compromisso a ser firmado por ocasião da concessão do benefício, exceto se o descumprimento ocorrer por motivo de força maior;','RL', 'J');
        $this->MultiCell(540, 10, 'II - não terá direito a férias relativas ao período em que estiver afastado, quando o afastamento for por período integral;','RL', 'J');
        $this->MultiCell(540, 10, 'III - permanecer vinculado ao serviço municipal pelo mesmo período que recebeu o benefício ou restituir o investimento realizado pelo município, incluída a remuneração no interregno mencionado.','RL', 'J');
        $this->MultiCell(540, 10, ' ','RL', 'J');
        $this->SetFont('Arial', '',10);
        $this->MultiCell(540, 16, 'EU, servidor acima identificado, me comprometo a não exercer nenhuma atividade remunerada durante o tempo em que estiver afastado(a) para cursar Mestrado/Doutorado. Devo continuar vinculado ao serviço público municipal, por período e carga horária no mínimo igual ao do afastamento após conclusão do curso.','BRL', 'J');
        $this->Ln(10);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(540, 16, 'Nestes termos', 0, 'J');
        $this->MultiCell(540, 16, 'Pede Deferimento', 0, 'J');

        $this->MultiCell(540, 16, '____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(540, 16, 'Assinatura do(a) Requerente', 0, 'C');

        $this->Ln(10);
        $this->dataCadastro();

        $this->Ln(15);
        $this->SetFont('Arial', 'B', 10);
        $this->MultiCell(540, 14, "ANEXAR OS SEGUINTES DOCUMENTOS:", 'TRL', 'C');
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(540, 14, "1) Comprovante de matrícula no curso     2) Grade curricular     3) Certificado de conclusão de graduação", 'RL', 'J');
        $this->MultiCell(540, 14, "4) Portaria de nomeação do servidor        5) Manifestação da chefia imediata", 'RL', 'J');
        $this->MultiCell(540, 14, "6) Justificativa do servidor quanto à aplicação do curso na área de atuação", 'BRL', 'J');
        $this->Ln(10);
        
        $this->Ln(10);
        $this->caixaProtocolo();
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
        $this->MultiCell(540, 18, 'CUMPRIMENTO DOS REQUISITOS PARA AFASTAMENTO (LEI 4.075/04)', 0, 'C');
        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(540, 18, 'Para preenchimento da Diretoria de Gestão de Pessoas da Secretaria de Administração', 0, 'C');
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
        $this->Cell(540, 20, 'MANIFESTAÇÃO DO SECRETÁRIO DE EDUCAÇÃO', 'LTR', 1, 'C', FALSE);
        $this->SetFont('Arial', '', 12);
        $this->Cell(250, 15, '( ) Favorável desde que cumprido todos os', 'LTR', 0, 'L', false);
        $this->Cell(290, 15, '( ) Desfavorável. MOTIVO:', 'LTR', 1, 'L', false);
        $this->Cell(250, 15, 'requisitos de acordo com a lei 4075/04.', 'LR', 0, 'L', false);
        $this->Cell(290, 15, '', 'LR', 1, 'L', false);
        $this->Cell(250, 60, '', 'LR', 0, 'C', false);
        $this->Cell(290, 60, '', 'LR', 1, 'C', false);
        $this->MultiCell(540, 20, '', 'LTR', 'C');
        $this->MultiCell(540, 15, '____________________________________', 'LR', 'C');
        $this->MultiCell(540, 12, "{$this->assinaturas['secretario']}",'LR', 'C');
        $this->MultiCell(540, 12, 'Secretário(a) Municipal de Educação','LBR', 'C');
        
        $this->Ln(30);
        $this->MultiCell(540, 14, "Data: ___/___/______", 0, 'R');

    }
    
}