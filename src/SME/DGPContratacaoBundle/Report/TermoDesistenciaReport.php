<?php

namespace SME\DGPContratacaoBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\DGPBundle\Util\AssinaturasUtil;
use SME\DGPContratacaoBundle\Entity\Inscricao;
use SME\DGPContratacaoBundle\Entity\Convocacao;
use SME\DGPContratacaoBundle\Entity\TipoProcesso;

class TermoDesistenciaReport extends PDFDocument {
    
    private $inscricao;
    private $convocacao;
    private $cargaHoraria;
    private $dataNomeacao;
    
    private $atendente;
    
    public function build() {
        $this->AddPage();
        $this->AliasNbPages();
        $this->rel();
        $this->Ln(1);
        $this->SetLineWidth(1);
        $this->Line($this->x, $this->y, $this->x + 190, $this->y);
        $this->SetLineWidth(0.1);
        $this->Ln(5);
        $this->setHeaderX($this->x);
        $this->setHeaderY($this->y);
        $this->header('SECRETARIA DE EDUCAÇÃO', 'Diretoria de Gestão de Pessoas');
        $this->rel();
    }

    private function rel() {
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $this->Ln(1);
        $this->MultiCell(190, 5, "TERMO DE DESISTÊNCIA", 0, 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->MultiCell(190, 5, $this->inscricao->getCargo()->getProcesso()->getTipoProcesso()->getNome() . 
                ' disciplinado pelo Edital ' . $this->inscricao->getCargo()->getProcesso()->getEdital(), 0, 'C');

        $this->Ln(2);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
        if ($this->inscricao->getProcesso()->getTipoProcesso()->getId() === TipoProcesso::CONCURSO_PUBLICO) {
            $this->MultiCell(190, 7, "EU, {$this->inscricao->getCandidato()->getNome()}, CPF nº: {$this->inscricao->getCandidato()->getCpfCnpj()} declaro que desisto da vaga oferecida a mim, por ocasião da Chamada do Concurso Público disciplinado pelo Edital nº {$this->inscricao->getCargo()->getProcesso()->getEdital()}. Estou ciente de que, em face deste termo, fica a Secretaria Municipal de Educação liberada para preencher a vaga que a mim corresponderia, atribuindo-a a outro candidato subsequente da lista de classificação.", 0, 'J');
        } else {
            $this->MultiCell(190, 7, "EU, {$this->inscricao->getCandidato()->getNome()}, CPF nº: {$this->inscricao->getCandidato()->getCpfCnpj()} declaro que desisto da vaga oferecida a mim, por ocasião da {$this->convocacao->getNome()} do {$this->inscricao->getCargo()->getProcesso()->getNome()} disciplinado pelo Edital nº {$this->inscricao->getCargo()->getProcesso()->getEdital()}. Estou ciente de que, em face deste termo, fica a Secretaria Municipal de Educação liberada para preencher a vaga que a mim corresponderia, atribuindo-a a outro candidato subsequente da lista de classificação.", 0, 'J');
        }
        $this->Ln(2);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $this->Cell(40, 7, 'Cargo / Disciplina: ', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
        $this->Cell(150, 7, $this->inscricao->getCargo()->getNome(), 0, 1, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $this->Cell(32, 7, 'Classificação: ', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
        $this->Cell(20, 7, $this->inscricao->getClassificacao(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $this->Cell(32, 7, "Carga Horária: ", 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
        $this->Cell(15, 7, $this->cargaHoraria . 'h', 0, 0, 'L');
        if ($this->inscricao->getProcesso()->getTipoProcesso()->getId() === TipoProcesso::CONCURSO_PUBLICO) {
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
            $this->Cell(42, 7, "Data de Nomeação: ", 0, 0, 'L');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
            $this->Cell(60, 7, $this->dataNomeacao->format('d/m/Y'), 0, 1, 'L');
        } else {
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
            $this->Cell(65, 7, "Data do Edital de Convocação: ", 0, 0, 'L');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
            $this->Cell(40, 7, $this->convocacao->getDataRealizacao()->format('d/m/Y'), 0, 1, 'L');
        }
        $this->Ln(2);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $this->Cell(28, 12, 'Assinatura: ', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
        $this->Cell(90, 12, '__________________________________', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $this->Cell(15, 12, 'Data: ', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
        $this->Cell(40, 12, '______/______/______', 0, 1, 'L');
        /* Assinatura Diretor DGP */
        $this->Ln(8);
        $this->Cell(95, 5,'',0,0);
        $this->Image(dirname(__FILE__) . '/../Resources/images/assinaturas/diretor-dgp.jpg',$this->w - 75, $this->y - 11, '35%');
        $this->Cell(95, 5, "______________________________", 0, 1, 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(95, 5, '', 0, 0);
        $this->Cell(95, 5, AssinaturasUtil::DIRETOR_DGP,0,1,'C');
        $this->Cell(95, 5, '', 0, 0);
        $this->Cell(95, 5,"Diretoria de Gestão de Pessoas",0,1,'C');
        if ($this->atendente) {
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 9);
            $this->Cell(190, 8, "Atendido por: {$this->atendente->getNome()}", 0, 1, 'L');
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 8);
        }
    }
    
    function getInscricao() {
        return $this->inscricao;
    }

    function getConvocacao() {
        return $this->convocacao;
    }

    function getCargaHoraria() {
        return $this->cargaHoraria;
    }

    function getDataNomeacao() {
        return $this->dataNomeacao;
    }

    function setInscricao(Inscricao $inscricao) {
        $this->inscricao = $inscricao;
    }

    function setConvocacao(Convocacao $convocacao) {
        $this->convocacao = $convocacao;
    }

    function setCargaHoraria($cargaHoraria) {
        $this->cargaHoraria = $cargaHoraria;
    }

    function setDataNomeacao(\DateTime $dataNomeacao) {
        $this->dataNomeacao = $dataNomeacao;
    }

    function getAtendente() {
        return $this->atendente;
    }

    function setAtendente($atendente) {
        $this->atendente = $atendente;
    }
    
}
