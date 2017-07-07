<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class BolsaEspecializacaoReport extends RequerimentoReport
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
        $this->titulo = 'REQUERIMENTO DE AUXÍLIO FINANCEIRO PARA CURSO DE PÓS-GRADUAÇÃO ESPECIALIZAÇÃO LATO SENSU (somente efetivos)';
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

        $texto = "Eu, servidor acima identificado, atuando como {$this->params['cargo_atual']}, venho requerer a Vsª se digne conceder o AUXÍLIO FINANCEIRO PARA CURSO" .
                " DE PÓS-GRADUAÇÃO LATO SENSU em {$this->params['curso']}, de acordo com Lei nº3.650 de 15 de outubro de 2001:";
        $this->MultiCell(540, 12, $texto, 0, 'J');
        $this->Ln(10);
        $this->SetFont('Arial', '', 9);
        $this->Cell(100,60,'','',0,'J');
        $this->MultiCell(420,10,'"Art. 1º - Fica instituído auxílio financeiro para o servidor efetivo da administração direta e fundações do Município de Itajaí, que freqüentar curso de pós-graduação, em nível de especialização lato sensu, a partir de janeiro de 2002.', 0, 'J');
        $this->Cell(100,60,'','',0,'J');
        $this->MultiCell(420,10,'Art. 2º - O auxílio financeiro de que trata o artigo anterior corresponderá em até 50% (cinqüenta por cento) do valor da mensalidade do curso, a critério do Chefe do Poder Executivo.', 0, 'J');
        $this->Cell(100,60,'','',0,'J');
        $this->MultiCell(420,10,'Art. 3º - O auxílio financeiro somente será concedido quando o servidor:', 0, 'J');
        $this->Cell(100,60,'','',0,'J');
        $this->MultiCell(420,10,'II - não estiver estado em licença sem vencimento ou à disposição de órgãos não pertencentes ao Município, nos últimos 3 (três) anos;', 0, 'J');
        $this->Cell(100,60,'','',0,'J');
        $this->MultiCell(420,10,'III - não tiver sofrido penas disciplinares;', 0, 'J');
        $this->Cell(100,60,'','',0,'J');
        $this->MultiCell(420,10,'V - se membro do magistério, não estiver em desvio de função.', 0, 'J');
        $this->Cell(100,60,'','',0,'J');
        $this->MultiCell(420,10,'Art. 4º - Além dos requisitos acima, o auxílio financeiro só será concedido quando o curso pretendido for:', 0, 'J');
        $this->Cell(100,60,'','',0,'J');
        $this->MultiCell(420,10,'I - compatível com o interesse do órgão de lotação do servidor e da administração pública municipal;', 0, 'J');
        $this->Cell(100,60,'','',0,'J');
        $this->MultiCell(420,10,'II - afim com o cargo e a área de atuação do interessado, no serviço público municipal;', 0, 'J');
        $this->Cell(100,60,'','',0,'J');
        $this->MultiCell(420,10,'III - credenciado ou reconhecido, por órgão competente federal ou estadual, conforme legislação vigente."', 0, 'J');
        
        
        $this->Ln(20);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(540, 12, 'Nestes termos', 0, 'J');
        $this->MultiCell(540, 12, 'Pede Deferimento', 0, 'J');

        $this->Ln(5);
        $this->MultiCell(540, 12, '____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(540, 12, 'Assinatura do(a) Requerente', 0, 'C');
        $this->Ln(20);
        $dia = date("d");
        $mes = self::mesNumericoParaExtenso( date("m") );
        $ano = date("Y");
        $this->MultiCell(540, 14, "Itajaí, $dia de $mes de $ano", 0, 'R');

        $this->Ln(15);
        $this->SetFont('Arial', 'B', 10);
        $this->MultiCell(540, 14, "ANEXAR OS SEGUINTES DOCUMENTOS:", 'TRL', 'C');
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(540, 14, "1) Comprovante de matrícula no curso     2) Grade curricular     3) Certificado de conclusão de graduação", 'RL', 'J');
        $this->MultiCell(540, 14, "4) Portaria de nomeação do servidor        5) Manifestação da chefia imediata", 'RL', 'J');
        $this->MultiCell(540, 14, "6) Justificativa do servidor quanto à aplicação do curso na área de atuação", 'BRL', 'J');
        $this->Ln(10);
        
        if ($via == 1 || $via == 3) {
            $this->Ln(25);
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
        $this->MultiCell(540, 18, 'CUMPRIMENTO DOS REQUISITOS PARA AUXÍLIO FINANCEIRO PARA CURSO DE PÓS-GRADUAÇÃO ESPECIALIZAÇÃO LATO SENSU', 0, 'C');
        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(540, 18, 'Para preenchimento da Diretoria de Gestão de Pessoas', 0, 'C');
        $this->Ln(15);
        $this->SetFillColor(230);
        
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(540, 18, 'Art. 3: O benefício de que trata esta lei somente será concedido se atendidos os seguintes requisitos:', 'TLRB', 'J',true);
        $celulas = array('I - Que o servidor: (Assinalar com X  o requisito que estiver plenamente atendido)',
            '(  ) II - não gozou de licença sem vencimento ou ficou à disposição de órgãos não pertencentes ao Município, nos últimos três anos;',
            '(  ) III - não sofreu aplicação de pena disciplinar;',
            '(  ) V - Se membro do Magistério, não está em desvio de função.',
            'Art. 4: Além dos requisitos acima, o auxílio financeiro só será concedido quando o curso pretendido for: (Assinalar com X o requisito que estiver plenamente atendido)',
            '(  ) I - compatível com os interesses do órgão de lotação do servidor e  da Administração Pública Municipal;',
            '(   ) II - afim com o cargo e a área de atuação do interessado, no serviço público municipal;',
            '(  ) III - autorizado ou reconhecido pelo órgão federal ou estadual de educação que tiver competência, nos termos da legislação.');
        foreach($celulas as $i=>$celula) {
            if( $i == 4 ) 
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