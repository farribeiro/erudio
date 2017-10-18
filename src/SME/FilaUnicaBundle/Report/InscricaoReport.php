<?php

namespace SME\FilaUnicaBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\FilaUnicaBundle\Entity\Inscricao;
use SME\FilaUnicaBundle\Entity\TipoInscricao;

class InscricaoReport extends PDFDocument {
    
    private $inscricao;
    
    function header($title = 'SECRETARIA DE EDUCAÇÃO', $subtitle = '') {
        $subtitle = 'Inscrição ' . $this->inscricao->getProtocolo() . ' - Sistema Fila Única';
        parent::header($title, $subtitle);
    }
    
    function footer() {
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);
        $data = new \DateTime();
        $this->MultiCell($this->ws, 6, 'Documento impresso em ' . $data->format('d/m/Y') , '', 'R');
    }
    
    public function build() {
        $this->AddPage();
        $this->AliasNbPages();
        $this->rel();
    }
    
    private function rel() {
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);
        $this->Ln(5);
        $this->Cell(41, 5, 'Categoria', '', 0, 'J');
        $this->Cell(5,5,' ', '', 0, 'J');
        $this->Cell(45, 5, 'Status', '', 0, 'J');
        $this->Cell(5,5,' ', '', 0, 'J');
        $this->Cell(93, 5, 'Unidade Escolar Atual', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $tipo = $this->inscricao->getProcessoJudicial() 
            ? $this->inscricao->getTipoInscricao()->getNome() . ' [P.J.]'
            : $this->inscricao->getTipoInscricao()->getNome();
        $this->Cell(41, 7, $tipo, 'LTBR', 0, 'J');
        $this->Cell(5, 7,' ', '', 0, 'J');
        $this->Cell(45, 7, $this->inscricao->getStatus()->getNome(), 'LTBR', 0, 'J');
        $this->Cell(5, 7,' ', '', 0, 'J');
        $this->Cell(93, 7, $this->inscricao->getUnidadeOrigem() ? $this->inscricao->getUnidadeOrigem()->getNome() : 'Não matriculado', 'LTBR', 1, 'J');
        $this->Ln(2);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);
        $this->Cell(92, 5, 'Unidade Escolar Pretendida', '', 0, 'J');
        $this->Cell(5,5,' ', '', 0, 'J');
        $this->Cell(93, 5, 'Unidade Escolar Pretendida (Alternativa)', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(92, 7, $this->inscricao->getUnidadeDestino()->getNome(), 'LTBR', 0, 'J');
        $this->Cell(5, 7,' ', '', 0, 'J');
        $this->Cell(93, 7, $this->inscricao->getUnidadeDestinoAlternativa()->getNome(), 'LTBR', 1, 'J');
        $this->Ln(2);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);
        $this->Cell(70,5,'Zoneamento', '', 0, 'J');
        $this->Cell(5,5,' ', '', 0, 'J');
        $this->Cell(40,5,'Ano escolar', '', 0, 'J');
        $this->Cell(5,5,' ', '', 0, 'J');
        $this->Cell(35,5,'Turno', '', 0, 'J');
        $this->Cell(5,5,' ', '', 0, 'J');
        $this->Cell(30,5,'Data de inscrição', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(70, 7, $this->inscricao->getZoneamento()->getNome() . ' - ' . $this->inscricao->getZoneamento()->getDescricao(), 'LTBR', 0, 'J');
        $this->Cell(5, 7,'','', 0, 'J');
        $this->Cell(40, 7, $this->inscricao->getAnoEscolar()->getNome(), 'LTBR', 0, 'J');
        $this->Cell(5, 7,'','', 0, 'J');
        $this->Cell(35, 7, $this->inscricao->getPeriodoDia() ? $this->inscricao->getPeriodoDia()->getNome() : '', 'LTBR', 0, 'J');
        $this->Cell(5, 7,'','', 0, 'J');
        $this->Cell(30, 7, $this->inscricao->getDataCadastro()->format('d/m/Y'), 'LTBR', 1, 'J');
        $this->Ln(2);
        
        if($this->inscricao->getVagaOfertada()) {
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);
            $this->Cell(190, 5, 'Vaga ofertada:', '', 1, 'J');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
            $this->Cell(190, 7, $this->inscricao->getVagaOfertada()->getUnidadeEscolar()->getNome() . ' - ' 
                    . $this->inscricao->getVagaOfertada()->getPeriodoDia()->getNome(), 'LTBR', 1, 'J');
            $this->Ln(2);
        }
        
        if($this->inscricao->getTipoInscricao()->getId() === TipoInscricao::TRANSFERENCIA) {
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);
            $this->Cell(92, 5, 'Código de Aluno no Erudio', '', 0, 'J');
            $this->Cell(5,5,' ', '', 0, 'J');
            $this->Cell(93, 5, 'Data de Cadastro no Erudio', '', 1, 'J');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
            $this->Cell(92, 7, $this->inscricao->getCodigoAluno(), 'LTBR', 0, 'J');
            $this->Cell(5, 7,' ', '', 0, 'J');
            $this->Cell(93, 7, $this->inscricao->getDataMatricula()->format('d/m/Y'), 'LTBR', 1, 'J');
            $this->Ln(2);
        }
        
        $this->SetFont(self::FONT_DEFAULT_TYPE,'B', 9);
        $this->Cell(100, 5,'Nome da criança', '', 0, 'J');
        $this->Cell(5, 5,' ', '', 0, 'J');
        $this->Cell(50, 5, $this->inscricao->getCrianca()->getCpfCnpj() ? 'CPF' : 'Certidão de nascimento', '', 0, 'J');
        $this->Cell(5, 5,' ', '', 0, 'J');
        $this->Cell(30, 5,'Data de nascimento', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(100, 7, $this->inscricao->getCrianca()->getNome(), 'LTBR', 0, 'J');
        $this->Cell(5, 7,'','', 0, 'J');
        if($this->inscricao->getCrianca()->getCpfCnpj()) {
            $this->Cell(50, 7, $this->inscricao->getCrianca()->getCpfCnpj(), 'LTBR', 0, 'J');
        } else {
            $this->Cell(50, 7, $this->inscricao->getCrianca()->getTermoCertidaoNascimento() . ' - '
                    . $this->inscricao->getCrianca()->getLivroCertidaoNascimento() . ' - '
                    . $this->inscricao->getCrianca()->getFolhaCertidaoNascimento(), 'LTBR', 0, 'J');
        }
        $this->Cell(5, 7,'','', 0, 'J');
        $this->Cell(30, 7, $this->inscricao->getCrianca()->getDataNascimento()->format('d/m/Y'), 'LTBR', 1, 'J');
        $this->Ln(2);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);
        $this->Cell(93, 5, 'Filiação - Pai', '', 0, 'J');
        $this->Cell(5,5,' ', '', 0, 'J');
        $this->Cell(92, 5, 'Filiação - Mãe', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(93, 7, $this->inscricao->getCrianca()->getNomePai(), 'LTBR', 0, 'J');
        $this->Cell(5, 7,' ', '', 0, 'J');
        $this->Cell(92, 7, $this->inscricao->getCrianca()->getNomeMae(), 'LTBR', 1, 'J');
        $this->Ln(2);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE,'B', 9);
        $this->Cell(75, 5,'Situação Familiar', '', 0, 'J');
        $this->Cell(5, 5,' ', '', 0, 'J');
        $this->Cell(30, 5,'Renda per capita', '', 0, 'J');
        $this->Cell(5, 5,' ', '', 0, 'J');
        $this->Cell(75, 5,'E-mail', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(75, 7, $this->inscricao->getSituacaoFamiliar() ? $this->inscricao->getSituacaoFamiliar()->getDescricao() : 'Não informada', 'LTBR', 0, 'J');
        $this->Cell(5, 7,'','', 0, 'J');
        $this->Cell(30, 7, 'R$ ' . $this->inscricao->getRendaPerCapita(), 'LTBR', 0, 'J');
        $this->Cell(5, 7,'','', 0, 'J');
        $this->Cell(75, 7, $this->inscricao->getCrianca()->getEmail(), 'LTBR', 1, 'J');
        $this->Ln(2);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE,'B', 9);
        $this->Cell(160, 5,'Endereço', '', 0, 'J');
        $this->Cell(5, 5,' ', '', 0, 'J');
        $this->Cell(25, 5,'Número', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(160, 7, $this->inscricao->getCrianca()->getEndereco()->getLogradouro(), 'LTBR', 0, 'J');
        $this->Cell(5, 7,'','', 0, 'J');
        $this->Cell(25, 7, $this->inscricao->getCrianca()->getEndereco()->getNumero(), 'LTBR', 1, 'J');
        $this->Ln(2);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE,'B', 9);
        $this->Cell(75, 5,'Complemento', '', 0, 'J');
        $this->Cell(5, 5,' ', '', 0, 'J');
        $this->Cell(30, 5,'Bairro', '', 0, 'J');
        $this->Cell(5, 5,' ', '', 0, 'J');
        $this->Cell(75, 5,'CEP', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(75, 7, $this->inscricao->getCrianca()->getEndereco()->getComplemento(), 'LTBR', 0, 'J');
        $this->Cell(5, 7,'','', 0, 'J');
        $this->Cell(30, 7, $this->inscricao->getCrianca()->getEndereco()->getBairro(), 'LTBR', 0, 'J');
        $this->Cell(5, 7,'','', 0, 'J');
        $this->Cell(75, 7, $this->inscricao->getCrianca()->getEndereco()->getCep(), 'LTBR', 1, 'J');
        $this->Ln(2);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE,'B', 9);
        $this->Cell($this->ws, 5, 'Telefones para Contato', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $telefones = '';
        foreach($this->inscricao->getCrianca()->getTelefones() as $tel) {
            if($tel->getNumero()) {
                $telefones .= $tel->getNumeroFormatado() . '   ';
            }
        }
        $this->Cell($this->ws, 7, $telefones, 'LTBR', 1, 'J');
        $this->Ln(2);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE,'B', 9);
        $this->Cell($this->ws, 5,'Responsáveis', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $responsaveis = '';
        foreach($this->inscricao->getCrianca()->getRelacoes() as $responsavel) {
            if($responsavel->getResponsavel()) {
                $responsaveis .= $responsavel->getParente()->getNome() . ' - ' . $responsavel->getParente()->getCpfCnpj();
                if($responsavel->getParentesco()) {
                    $responsaveis .= ' (' . $responsavel->getParentesco()->getNome() . ')   ';
                } else {
                    $responsaveis .= '   ';
                }
            }
        }
        $this->MultiCell($this->ws, 7, $responsaveis, 'LTBR', 'J');
        $this->Ln(2);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE,'B', 9);
        $this->Cell($this->ws, 5,'Deficiências / Transtornos', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $particularidades = '';
        foreach($this->inscricao->getCrianca()->getParticularidades() as $particularidade) {
            $particularidades .= $particularidade->getNome() . '   ';
        }
        $this->MultiCell($this->ws, 7, $particularidades, 'LTBR', 'J');
        $this->Ln(2);
        
        if($this->inscricao->getTipoInscricao()->getId() === TipoInscricao::REGULAR) {
            $this->SetFont(self::FONT_DEFAULT_TYPE,'B', 9);
            $this->Cell($this->ws, 5,'Renda Familiar', '', 1, 'J');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
            $this->SetWidths(array(95, 50, 45));
            $this->SetAligns(array('L', 'L', 'L'));
            foreach($this->inscricao->getRendaDetalhada() as $componenteRenda) {
                $this->Row(array(
                    $componenteRenda->getNome(),
                    $componenteRenda->getParentesco(),
                    'R$ ' . $componenteRenda->getRendimentoMensal()
                ));
            }
        }
        
        $this->Ln(15);
        $this->Cell(95, 8, '___________________________________', '', 0, 'C');
        $this->Cell(95, 8, '___________________________________', '', 1, 'C');
        $this->Cell(95, 5, 'Assinatura do Requerente', '', 0, 'C');
        $this->Cell(95, 5, 'Assinatura do Atendente', '', 1, 'C');
    }
    
    public function getInscricao() {
        return $this->inscricao;
    }

    public function setInscricao(Inscricao $inscricao) {
        $this->inscricao = $inscricao;
    }
    
}