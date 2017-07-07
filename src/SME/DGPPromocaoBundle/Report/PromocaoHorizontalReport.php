<?php

namespace SME\DGPPromocaoBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\DGPPromocaoBundle\Entity\PromocaoHorizontal;
use SME\CommonsBundle\Util\DateTimeUtil;
use SME\CommonsBundle\Util\DocumentosUtil;

class PromocaoHorizontalReport extends PDFDocument {
    
    private $promocaoHorizontal;
    private $alocacoes;
    private $funcaoAtual;
    
    public function getPromocaoHorizontal() {
        return $this->promocaoHorizontal;
    }

    public function setPromocaoHorizontal(PromocaoHorizontal $promocaoHorizontal) {
        $this->promocaoHorizontal = $promocaoHorizontal;
    }
    
    public function getAlocacoes() {
        return $this->alocacoes;
    }

    public function setAlocacoes($alocacoes) {
        $this->alocacoes = $alocacoes;
    }
  
    public function build() {
        for ($i = 1; $i <= 3; $i++) {
            $this->rel_requerimento_promocao_horizontal($i);
            if ($i <= 2) {
                $this->rel_promocao_funcional($i);
                $this->rel_manifestacao_comissao($i);
            }
        }
    }
    
    private function rel_requerimento_promocao_horizontal($via) {
        $this->AddPage();
        $this->AliasNbPages();
        $this->via($via);

        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $this->Ln(3);
        $this->MultiCell(190, 6, 'REQUERIMENTO DE PROMOÇÃO HORIZONTAL', 0, 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
        $this->Ln(3);
        $this->MultiCell(190, 6, 'Ilustríssimo Senhor Secretário Municipal de Educação', 0, 'J');
        $this->MultiCell(190, 6, 'NESTA', 0, 'J');
        $this->Ln(1);

        //----------------------------------------------------------------
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);
        $this->Cell(105, 6,'Nome', '', 0, 'J');
        $this->Cell(5, 6,' ', '', 0, 'J');
        $this->Cell(80, 6,'CPF', '', 1, 'J');

        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(105, 8, $this->promocaoHorizontal->getVinculo()->getServidor()->getNome(), 'LTBR', 0, 'J');
        $this->Cell(5, 8,'','', 0, 'J');
        $this->Cell(80, 8, DocumentosUtil::formatarCpf($this->promocaoHorizontal->getVinculo()->getServidor()->getCpfCnpj(), 0, ',', '.'), 'LTBR', 1, 'J');
        $this->Ln(1);
        
        //----------------------------------------------------------------
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);

        $this->Cell(105, 6,'Cargo de Origem', '', 0, 'J');
        $this->Cell(5, 6,' ', '', 0, 'J');
        $this->Cell(40, 6,'Matrícula', '', 0, 'J');
        $this->Cell(5, 6,' ', '', 0, 'J');
        $this->Cell(35, 6,'Data de Nomeação', '', 1, 'J');

        $this->SetFont(self::FONT_DEFAULT_TYPE,'', 10);
        $this->Cell(105, 8, $this->promocaoHorizontal->getVinculo()->getCargo()->getNome(), 'LTBR', 0, 'J');
        $this->Cell(5, 8,'','', 0, 'J');
        $this->Cell(40, 8, $this->promocaoHorizontal->getVinculo()->getMatricula(), 'LTBR', 0, 'J');
        $this->Cell(5, 8,'','', 0, 'J');
        $this->Cell(35, 8, $this->promocaoHorizontal->getVinculo()->getDataNomeacao()->format('d/m/Y'), 'LTBR', 1, 'J');
        $this->Ln(1);
        
        //----------------------------------------------------------------
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);

        $this->Cell(190, 6,'Locais de Trabalho', '', 1, 'J');

        $this->SetFont(self::FONT_DEFAULT_TYPE,'', 10);
        $this->Cell(190, 1, '', 'LTR', 1, 'J');
        foreach($this->alocacoes as $a) {
            $this->Cell(190, 6, $a->getLocalTrabalho()->getNome(), 'LR', 1, 'J');
        }
        $this->Cell(190, 1, '', 'LBR', 1, 'J');
        
        //----------------------------------------------------------------
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
        $funcaoAtual = count($this->alocacoes) > 0
            ? $this->alocacoes[0]->getFuncaoAtual() 
            : $this->promocaoHorizontal->getVinculo()->getCargo()->getNome();
        $texto = 'O servidor efetivo abaixo assinado, do Quadro do Magistério Público Municipal, ' .
                'atuando na função de ' . $funcaoAtual . ', requer a Vsª se digne conceder, PROMOÇÃO HORIZONTAL, ' .
                'conforme habilitação específica e apresentação de certificados dos cursos, em anexo, de acordo ' .
                'com o artigo 24 da Lei Complementar Nº 132, de 2 abril de 2008.';
        $this->MultiCell(190, 8, $texto, 0, 'J');

        $this->Ln(10);
        $this->MultiCell(190, 7, 'Nestes termos', 0, 'J');
        $this->MultiCell(190, 7, 'Pede Deferimento', 0, 'J');

        $this->Ln(20);
        $this->MultiCell(190, 6, '____________________________________', 0, 'C');
        $this->Ln(1);
        $this->MultiCell(190, 8, 'Assinatura do(a) Requerente', 0, 'C');

        $this->Ln(5);
        $now = new \DateTime();
        $dia = $now->format('d');
        $mes = DateTimeUtil::mesPorExtenso($now->format('m'));
        $ano = $now->format('Y');
        $this->MultiCell(190, 14, "Itajaí, $dia de $mes de $ano", 0, 'R');
        $this->Ln(20);
        $this->caixaProtocolo();
    }

    private function rel_promocao_funcional($via) {
        $this->AddPage();
        $this->AliasNbPages();
        $this->via($via);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->MultiCell(0 , 8, 'REQUERENTE: ' . $this->promocaoHorizontal->getVinculo()->getServidor()->getNome());
        $this->Ln(1);

        $this->SetFillColor(230);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 8);
        $this->Cell(190, 6, 'Apresentou certificado(s) conforme anexo e listados abaixo', 1, 1, 'C', true);

        // Cabeçalho Linha 2
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 8);
        $this->Cell(145, 6, 'Curso(s)', 1, 0, 'L', true);
        $this->Cell(30, 6, 'Data de Conclusão', 1, 0, 'C', true);
        $this->Cell(15, 6, 'C.H.', 1, 1, 'C', true);

        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 8);
        
        foreach($this->promocaoHorizontal->getFormacoesInternas() as $f) {
            $this->Cell(145, 6, $f->getMatricula()->getFormacao()->getNomeCertificado(), 1, 0, 'J', false);
            $this->Cell(30, 6, $f->getMatricula()->getFormacao()->getDataTerminoFormacao()->format('d/m/Y'), 1, 0, 'C', false);
            $this->Cell(15, 6, $f->getMatricula()->getFormacao()->getCargaHoraria(), 1, 1, 'C', false);
        }
        foreach($this->promocaoHorizontal->getFormacoesExternas() as $f) {
            $this->Cell(145, 6, $f->getNome(), 1, 0, 'J', false);
            $this->Cell(30, 6, $f->getDataConclusao()->format('d/m/Y'), 1, 0, 'C', false);
            $this->Cell(15, 6, $f->getCargaHoraria(), 1, 1, 'C', false);
        }

        //Total
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 8);
        $this->Cell(175, 6, 'TOTAL ', 1, 0, 'R', false);
        $this->Cell(15, 6, $this->promocaoHorizontal->getCargaHorariaAcumulada(), 1, 1, 'C', false);
        //--------------------------------------------------------------------------------------

        $this->ln(2);

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
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell(165, 5, 'FICHA DE AVALIAÇÃO DE DESEMPENHO DO MAGISTÉRIO PÚBLICO MUNICIPAL', 'LTR', 0, 'C', FALSE);
        $this->Cell(25, 5, 'Pontuação', 'LTR', 1, 'C', false);

        // Cabeçalho Geral - Linha 2
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 8);
        $this->Cell(165, 3, '(Preenchido pela Chefia Imediata)', 'LBR', 0, 'C', false);
        $this->Cell(25, 3, '', 'LBR', 1, 'C', false);

        foreach ($dados as $D) {
            if ($D[0] != '') {
                // Grupo
                $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 8);
                $this->Cell(5, 6, $D[0], 1, 0, 'C', true);
                $this->Cell(185, 6, $D[1], 1, 1, 'J', true);
            } else {
                // Linhas das Informações
                $this->SetFont(self::FONT_DEFAULT_TYPE, '', 6);
                $this->Cell(5, 6, '', 1, 0, 'C', false);
                $this->Cell(160, 6, $D[1], 1, 0, 'J', false);
                $this->Cell(25, 6, '', 1, 1, 'C', false);
            }
        }
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 8);
        $this->Cell(165, 6, 'TOTAL DE PONTOS', 1, 0, 'R', false);
        $this->Cell(25, 6, '', 1, 1, 'C', false);
        // -------------------------------------------------------------------------------------

        $this->Ln(3);

        // Tabela de Ciente --------------------------------------------------------------------
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);

        // Linha 1
        $this->Cell(100, 6, 'CIENTE DO AVALIADO', 'LTR', 0, 'C', false);
        $this->Cell(60, 6, 'Assinatura do Avaliado', 'LTR', 0, 'C', false);
        $this->Cell(30, 6, 'Data', 'LTR', 1, 'C', false);

        // Linha 2
        $this->Cell(100, 6, '', 'LBR', 0, 'C', false);
        $this->Cell(60, 6, '', 'LBR', 0, 'C', false);
        $this->Cell(30, 6, '___/___/_______', 'LBR', 1, 'C', false);
        // -------------------------------------------------------------------------------------

        $this->Ln(3);

        // Tabela de Parecer -------------------------------------------------------------------
        // Cabeçalho 
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell(190, 6, 'PARECER DA CHEFIA IMEDIATA (DIRETOR OU COORDENADOR):', 'LTR', 1, 'C', FALSE);

        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);

        // Linha 1
        $this->Cell(50, 5, '(  ) Favorável', 'LTR', 0, 'L', false);
        $this->Cell(140, 5, '(  ) Desfavorável. MOTIVO:', 'LTR', 1, 'L', false);

        $this->Cell(50, 8, '', 'LBR', 0, 'C', false);
        $this->Cell(140, 8, '', 'LBR', 1, 'C', false);

        // Linha 2
        $this->Cell(50, 6, 'ASS. E CARIMBO', 'LTR', 0, 'C', false);
        $this->Cell(140, 6, '', 'LTR', 1, 'L', false);

        $this->Cell(50, 6, '(Chefia Imediata)', 'LBR', 0, 'C', false);
        $this->Cell(140, 6, '', 'LBR', 1, 'C', false);
    }

    private function rel_manifestacao_comissao($via) {
        $this->AddPage();
        $this->AliasNbPages();
        $this->via($via);

        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $this->Ln(3);
        $this->MultiCell(190, 6, 'MANIFESTAÇÃO DA COMISSÃO (Portarias 001/2017 - SME)', 0, 'C');
        $this->Ln(3);

        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->MultiCell(190, 6, 'REQUERENTE: ' . $this->promocaoHorizontal->getVinculo()->getServidor()->getNome(), 0, 'J');
        $this->Cell(140, 6, 'CARGO DE ORIGEM: ' . $this->promocaoHorizontal->getVinculo()->getCargo()->getNome(), 0, 0, 'J');
        $this->Cell(50, 6, 'MATRÍCULA: ' . $this->promocaoHorizontal->getVinculo()->getMatricula(), 0, 1, 'L');
        $this->MultiCell(190, 6, 'DATA DA NOMEAÇÃO: ' . $this->promocaoHorizontal->getVinculo()->getDataNomeacao()->format('d/m/Y'), 0, 'J');

        $this->Ln(3);

        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->MultiCell(190, 6, 'CUMPRIMENTO DOS REQUISITOS PARA PERCEPÇÃO DA PROMOÇÃO HORIZONTAL (ART.24 DA LEI 132/08)', 0, 'J');

        $this->Ln(2);

        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);

        $this->MultiCell(190, 6, '[__] I - os interstícios e percentuais previstos nos Anexos I e I-A', 0, 'J');
        $this->MultiCell(190, 6, '[__] II - a obtenção da aprovação mínima necessária na avaliação de desempenho, na forma prevista no decreto que a regulamentará.', 0, 'J');
        $this->MultiCell(190, 6, '[__] III - a participação em cursos de formação continuada afins ao cargo que ocupa. § 1ª Para efeito da promoção de que trata este artigo, será considerada a participação do servidor em cursos de formação continuada com carga mínima total de 40 horas.', 0, 'J');

        $this->Ln(2);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->MultiCell(190, 6, 'VOTOS DA COMISSÃO', 0, 'J');
        $this->Ln(2);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        // Definição dos Membros da Comissão
        $this->comissao = array();
        $this->comissao[] = 'Patrícia A.A. Obelar Coelho';
        $this->comissao[] = 'Viviane F. Dittrich de Souza';
        $this->comissao[] = 'Marcelo Bomfim Caetano';
        $this->comissao[] = 'Clara S. Ignacio de Mendonça';
        $this->comissao[] = 'Claudio da Silva';

        foreach ($this->comissao as $c) {
            $this->Cell(60, 7, 'Voto do membro da Comissão:', 'LTB', 0, 'J');
            $this->Cell(80, 7, $c, 'TB', 0, 'J');
            $this->Cell(50, 7, 'Voto:', 'TBR', 1, 'J');
        }

        $this->Ln(2);
        $this->MultiCell(190, 6, 'POR MAIORIA com base nas portarias 001/2017, e no Decreto nº 9327/2011 que regulamenta o inciso II do art. 24 da Lei Complementar 132/2008(alterada pelas LC 194 e 195/2011),', 0, 'J');
        $this->Ln(2);

        $this->MultiCell(190, 6, '[__] Deferimos a Promoção Horizontal a contar de ___/___/___', 0, 'J');
        $this->MultiCell(190, 6, '[__] Indeferimos o pedido do(a) requerente pelo motivo:', 0, 'J');
        $this->MultiCell(190, 6, '', 'B', 'J');
        $this->MultiCell(190, 6, '', 'B', 'J');
        $this->MultiCell(190, 6, '(do indeferimento dos títulos, caberá pedido de reconsideração, no prazo de cinco dias úteis)', 0, 'J');

        $this->Ln(2);

        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->MultiCell(190, 6, '*Art. 25 O acréscimo pecuniário decorrente da promoção horizontal será pago (LC/132/2008)', 0, 'J');

        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->MultiCell(190, 6, '[__] I - Automaticamente, no mês subsequente ao término do interstício , se o servidor preencher dentro deste, também os requisitos previstos nos incisos II e III do art.24', 0, 'J');
        $this->MultiCell(190, 6, '[__] II - A contar da data de protocolização, se o servidor preencher o requisito III do art 24 após ao término do interstício.', 0, 'J');
        $this->MultiCell(190, 6, '[__] III - No mês subsequente à data em que o servidor alcançar a pontuação mínima necessária a obtenção do benefício, na hipótese de que trata o §1º do art. 24.', 0, 'J');

        $this->Ln(2);

        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->MultiCell(190, 6, 'COMUNICAÇÃO DESTA DECISÃO', 0, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->MultiCell(190, 6, 'Comunique-se esta decisão a(o) requerente, pessoalmente, por resposta ao requerimento ou portaria publicada no Jornal do Município.', 0, 'J');

        $this->Ln(2);

        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->MultiCell(190, 6, 'Itajaí, ________________de ________ de ___________', 0, 'R');
    }
	
    function via($via) {
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 8);
        switch($via) {
            case 1:
                $texto = $via . 'ª via - SME'; break;
            case 2:
                $texto = $via . 'ª via - Sec. de Administração'; break;
            case 3:
                $texto = $via . 'ª via - Requerente'; break;
        }
        $this->Cell(190, 4, $texto, '', 1, 'R');
    }
    
    function caixaProtocolo() {
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);
        $L = 70;
        $X = 15;
        $this->SetX($X);
        $this->MultiCell($L, 6, 'Protocolo: ' . $this->promocaoHorizontal->getProtocolo(), 'LTR', 'J');
        $this->SetX($X);
        $this->MultiCell($L, 6, 'Recebido por:', 'LTR', 'J');
        $this->SetX($X);
        $this->MultiCell($L, 6, 'Assinatura do recebedor:', 'LTR', 'J');
        $this->SetX($X);
        $this->MultiCell($L, 6, 'Recebido em: ______/_____/__________', 'LTRB', 'J');
    }
    
}