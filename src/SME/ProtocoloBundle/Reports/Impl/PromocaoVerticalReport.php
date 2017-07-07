<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;

class PromocaoVerticalReport extends RequerimentoReport
{

    public $comissao;
    
    public function prepareDocument() {
        for ($i = 1; $i <= 3; $i++) {
            $this->via = $i;
            $this->rel_requerimento_promocao_vertical($i);
            if ($i <= 2) {
                $this->rel_promocao_funcional($i);
                $this->rel_manifestacao_comissao($i);
            }
        }
    }
	
    private function rel_requerimento_promocao_vertical($via) {
        $this->titulo = 'REQUERIMENTO DE PROMOÇÃO VERTICAL';
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
                'atuando na função de ' . $this->params['cargo_atual'] . ', requer a Vsª se digne conceder, PROMOÇÃO VERTICAL, ' .
                'conforme habilitação específica e apresentação de certificados dos cursos, em anexo, de acordo ' .
                'com o artigo 27 da Lei Complementar Nº 132, de 2 abril de 2008.';
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

    private function rel_promocao_funcional($via) {
        $this->titulo = 'PROMOÇÃO VERTICAL';
        $this->AddPage();
        $this->AliasNbPages();
        $this->Write(20, 'NOME: ' . $this->params['nome']);
        $this->Ln(20);

        $this->SetFillColor(230);
        //$this->SetTextColor(255); //23,45,78
        // Tabela 1 ----------------------------------------------------------------------------
        // Cabeçalho Linha 1
        $this->SetFont('Arial', '', 8);
        $this->Cell(540, 14, 'Apresentou Certificado(s) conforme anexo e listados abaixo', 1, 1, 'C', true);

        // Cabeçalho Linha 2
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(364, 14, 'Cursos(s)', 1, 0, 'L', true);
        $this->Cell(118, 14, 'Data de Emissão', 1, 0, 'C', true);
        $this->Cell(58, 14, 'Carga Horária', 1, 1, 'C', true);

        //Linha 1
        $this->SetFont('Arial', '', 8);
        $this->Cell(364, 14, $this->params['curso_nome'], 1, 0, 'J', false);
        $this->Cell(118, 14, $this->params['curso_conclusao'], 1, 0, 'C', false);
        $this->Cell(58, 14, $this->params['curso_ch'], 1, 1, 'R', false);

        $this->ln();

        $dados = array(
            array('A', 'Idoniedade Moral: Máximo 20 pontos')
            , array('', 'O Servidor mantém em sigilo as informações do seu trabalho e setor. [10 pontos]')
            , array('', 'O Servidor age mantendo um bom clima de trabalho e considera aos valores e os sentimentos individuais e coletivos. [10 pontos]')
            , array('B', 'Assiduidade e Pontualidade: Máximo 20 pontos')
            , array('', 'Sem registro de faltas nos últimos 12 meses. [20 pontos]')
            , array('', 'Registro de até 7 faltas justificadas com atestados médicos nos últimos 12 meses. [10 pontos]')
            , array('', 'Registro de faltas injustificadas nos últimos 12 meses (salvo Licença- Gestação e Licença-Prêmio). [Não Pontua].')
            , array('C', 'Disciplina: Máximo 10 pontos')
            , array('', 'Não foi advertido por escrito e não sofreu penalidades em processo disciplinar nos últimos 12 meses. [10 pontos]')
            , array('', 'Foi advertido por escrito pela direção, coordenação da instituição de ensino/e ou sofreu penalidades em processo disciplinar nos últimos 12 meses. [não pontua]')
            , array('D', 'Eficiência : Máximo 15 pontos')
            , array('', 'Realiza as tarefas com qualidade (cuidado, exatidão e precisão). [5 pontos]')
            , array('', 'O servidor presta informações e orientações com clareza, segurança e objetividade. [5 pontos]')
            , array('', 'O servidor tem facilidade em buscar soluções para imprevistos no trabalho quando solicitado. [5 pontos]')
            , array('E', 'Cumprimento dos deveres e obrigações funcionais: Máximo 15 pontos')
            , array('', 'O servidor tem responsabilidade, mostrando-se zeloso e preocupado em economizar materiais e trabalhos. [5 pontos]')
            , array('', 'O servidor é receptivo a críticas, discute, analisa e adota aquelas que proporcionam melhoria à instituição e ao local de trabalho. [5 pontos]')
            , array('', 'O servidor observa e cumpre seu posicionamento hierárquico, bem como seus limites de atribuições. [5 pontos]')
            , array('F', 'Participação em eventos escolares, culturais e esportivos: Máximo 10 pontos')
            , array('', 'Participou em eventos promovidos pela instituição de ensino e/ou participou de exposições, feira, festivais, teatro, cinemas, concertos e eventos esportivos. [10 pontos]')
            , array('G', 'Gestão de projetos: Máximo 10 pontos')
            , array('', 'Desenvolveu e/ou participou na gestão de um projeto na área da Educação que resultou na melhoria da qualidade do ensino. [10 pontos]')
        );

        // Tabela 2 ----------------------------------------------------------------------------
        // Cabeçalho Geral - Linha 1
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(482, 14, 'FICHA DE AVALIAÇÃO DE DESEMPENHO DO MAGISTÉRIO PÚBLICO MUNICIPAL', 'LTR', 0, 'C', FALSE);
        $this->Cell(58, 14, 'Pontuação', 'LTR', 1, 'C', FALSE);

        // Cabeçalho Geral - Linha 2
        $this->SetFont('Arial', '', 8);
        $this->Cell(482, 14, '(Preenchido pela Chefia Imediata)', 'LBR', 0, 'C', FALSE);
        $this->Cell(58, 14, '', 'LBR', 1, 'C', FALSE);

        foreach ($dados as $D) {
            if ($D[0] != '') {
                // Grupo
                $this->SetFont('Arial', 'B', 8);

                $this->Cell(14, 14, $D[0], 1, 0, 'C', TRUE);
                $this->Cell(526, 14, $D[1], 1, 1, 'J', TRUE);
            } else {
                // Linhas das Informações
                $this->SetFont('Arial', '', 6);

                $this->Cell(14, 14, '', 1, 0, 'C', false);
                $this->Cell(468, 14, $D[1], 1, 0, 'J', false);
                $this->Cell(58, 14, '', 1, 1, 'C', false);
            }
        }

        // Total
        $this->SetFont('Arial', 'B', 8);

        $this->Cell(482, 14, 'TOTAL DE PONTOS', 1, 0, 'R', false);
        $this->Cell(58, 14, '', 1, 1, 'C', false);
        // -------------------------------------------------------------------------------------

        $this->Ln();

        // Tabela de Ciente --------------------------------------------------------------------
        $this->SetFont('Arial', 'B', 10);

        // Linha 1
        $this->Cell(330, 14, 'CIENTE DO AVALIADO', 'LTR', 0, 'C', false);
        $this->Cell(127, 14, 'Assinatura do Avaliado', 'LTR', 0, 'C', false);
        $this->Cell(84, 14, 'Data', 'LTR', 1, 'C', false);

        // Linha 2
        $this->Cell(330, 20, '', 'LBR', 0, 'C', false);
        $this->Cell(127, 20, '', 'LBR', 0, 'C', false);
        $this->Cell(84, 20, '___/___/_______', 'LBR', 1, 'C', false);
        // -------------------------------------------------------------------------------------

        $this->Ln();

        // Tabela de Parecer -------------------------------------------------------------------
        // Cabeçalho 
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(540, 14, 'PARECER DA CHEFIA IMEDIATA (DIRETOR OU COORDENADOR):', 'LTR', 1, 'C', FALSE);

        $this->SetFont('Arial', '', 10);

        // Linha 1
        $this->Cell(116, 20, '( ) Favorável', 'LTR', 0, 'L', false);
        $this->Cell(424, 20, '( ) Desfavorável. MOTIVO:', 'LTR', 1, 'L', false);

        $this->Cell(116, 30, '', 'LBR', 0, 'C', false);
        $this->Cell(424, 30, '', 'LBR', 1, 'C', false);

        // Linha 2
        $this->Cell(116, 20, 'ASS. E CARIMBO', 'LTR', 0, 'C', false);
        $this->Cell(424, 20, '', 'LTR', 1, 'L', false);

        $this->Cell(116, 30, '(Chefia Imediata)', 'LBR', 0, 'C', false);
        $this->Cell(424, 30, '', 'LBR', 1, 'C', false);
        // -------------------------------------------------------------------------------------
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
        $this->MultiCell(540, 14, 'CURSO: ' . $this->params['curso_nome'], 0, 'J');

        $this->Ln();
        $this->Ln();

        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(540, 14, 'CUMPRIMENTO DOS REQUISITOS PARA PERCEPÇÃO DA PROMOÇÃO VERTICAL', 0, 'C');
        $this->MultiCell(540, 14, '(ART.27 DA LEI 132/08)', 0, 'C');

        $this->Ln();

        $this->SetFont('Arial', '', 10);

        $this->MultiCell(540, 14, '[__] I - o interstício mínimo de 06 (seis) anos, não exigido qualquer prazo em relação à primeira progressão após a publicação desta Lei Complementar, desde que cumprido o estágio probatório.', 0, 'J');
        $this->MultiCell(540, 14, '[__] II - ter obtido a aprovação necessária na avaliação de desempenho, na forma prevista no decreto que a regulamentará.', 0, 'J');
        $this->MultiCell(540, 14, '[__] III - a apresentação das titulações exigidas no artigo 5º.', 0, 'J');

        $this->Ln();
        $this->SetFont('Arial', 'B', 12);

        $this->MultiCell(540, 14, 'VOTOS DA COMISSÃO', 0, 'J');

        $this->SetFont('Arial', '', 10);

        // Definição dos Membros da Comissão
        $this->comissao = array();
        $this->comissao[] = 'Patrícia A.A. Obelar Coelho';
        $this->comissao[] = 'Viviane F. Dittrich de Souza';
        $this->comissao[] = 'Marcelo Bombim Caetano';
        $this->comissao[] = 'Clara S. Ignacio de Mendonça';
        $this->comissao[] = 'Claudio da Silva';

        foreach ($this->comissao as $C) {
            //$this->MultiCell(540,14,'Voto do membro da Comissão ('.$C.'): _________________________________',0,'J');
            $this->Cell(150, 16, 'Voto do membro da Comissão:', 'LTB', 0, 'J');
            $this->Cell(200, 16, $C, 'TB', 0, 'J');
            $this->Cell(190, 16, 'Voto:', 'TBR', 1, 'J');
        }

        $this->Ln();

        $this->MultiCell(540, 14, 'POR MAIORIA com base nas portarias 001/2017, e no Decreto nº 9327/2011, que regulamenta o inciso II do art. 27 da Lei Complementar 132/2008(alterada pelas LC 194 e 195/2011),', 0, 'J');

        $this->Ln();

        $this->MultiCell(540, 14, '[__] Promovemos verticalmente a contar de ___/___/___  Padrão/Nível _______________', 0, 'J');
        $this->MultiCell(540, 14, '[__] Indeferimos o pedido do(a) requerente pelo motivo:', 0, 'J');
        $this->MultiCell(540, 14, '', 'B', 'J');
        $this->MultiCell(540, 14, '', 'B', 'J');
        $this->MultiCell(540, 14, '(do indeferimento dos títulos, caberá pedido de reconsideração, no prazo de cinco dias úteis)', 0, 'J');

        $this->Ln();

        $this->SetFont('Arial', 'B', 10);
        $this->MultiCell(540, 14, '*Art. 28 O acréscimo pecuniário decorrente da promoção vertical será pago (LC/132/2008)', 0, 'J');

        $this->SetFont('Arial', '', 10);
        $this->MultiCell(540, 14, '[__] I - Automaticamente, no mês subsequente ao término do interstício , se o servidor preencher dentro deste, também os requisitos previstos nos incisos II e III do art.27.', 0, 'J');
        $this->MultiCell(540, 14, '[__] II - A contar da data de protocolização, se o servidor preencher o requisito III do art 27 após ao término do interstício.', 0, 'J');
        $this->MultiCell(540, 14, '[__] III - No mês subsequente à data em que o servidor alcançar a pontuação mínima necessária a obtenção do benefício, na hipótese de que trata o §1º do art. 27.', 0, 'J');

        $this->Ln();

        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(540, 14, 'COMUNICAÇÃO DESTA DECISÃO', 0, 'J');
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(540, 14, 'Comunique-se esta decisão a(o) requerente, por resposta ao requerimento ou portaria publicada no Jornal do Município.', 0, 'J');

        $this->Ln();
        $this->Ln();

        $this->SetFont('Arial', '', 12);
        $this->MultiCell(540, 14, 'Itajaí, ________________de ________ de ___________', 0, 'R');
    }
        
}