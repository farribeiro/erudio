<?php

namespace SME\DGPContratacaoBundle\Report;

use SME\CommonsBundle\Util\DocumentosUtil;
use SME\PDFBundle\Report\PDFDocument;
use SME\DGPBundle\Entity\TipoVinculo;
use SME\DGPBundle\Util\AssinaturasUtil;
use SME\DGPBundle\Entity\Vinculo;

class EncaminhamentoReport extends PDFDocument {
    
    private $vinculo;
    private $atendente;
    
    public function build() {
        foreach($this->vinculo->getAlocacoesOriginais() as $atuacao) {
            $this->AddPage();
            $this->AliasNbPages();
            $this->Ln(2);
            $this->rel($atuacao);
            $this->Ln(5);
            $this->SetLineWidth(1);
            $this->Line($this->x, $this->y, $this->x + 190, $this->y);
            $this->SetLineWidth(0.1);
            $this->Ln(5);
            $this->setHeaderX($this->x);
            $this->setHeaderY($this->y);
            $this->header('SECRETARIA DE EDUCAÇÃO', 'Diretoria de Gestão de Pessoas');
            $this->Ln(2);
            $this->rel($atuacao);
            $this->setHeaderX(10);
            $this->setHeaderY(10);
        }
    }
    
    private function rel($atuacao) {
        $this->SetFont(self::FONT_DEFAULT_TYPE,'B', 14);
        $tipoVinculo = strtoupper($this->vinculo->getTipoVinculo()->getNome());
        $this->MultiCell(190, 8, "ENCAMINHAMENTO PARA PROFISSIONAL {$tipoVinculo}" , 0, 'C');
        $this->Ln(2);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 11);
        $this->Cell(120, 8, 'Nome: ' . $this->vinculo->getServidor()->getNome(), 0, 0, 'L');
        $this->Cell(70, 8, 'CPF: ' . DocumentosUtil::formatarCpf($this->vinculo->getServidor()->getCpfCnpj()), 0, 1, 'L');
        if ($this->vinculo->getTipoVinculo()->getId() === TipoVinculo::EFETIVO) {
            $this->Cell(190, 8, 'Local de lotação: ' . ($atuacao->getLocalLotacao() ? $atuacao->getLocalLotacao()->getNome() : ''), 0, 1, 'L');
        }
        $this->Cell(190, 8, 'Local de trabalho: ' . $atuacao->getLocalTrabalho()->getNome(), 0, 1, 'L');
        $this->Cell(190, 8, 'Cargo / Disciplina: ' . $this->vinculo->getInscricaoVinculacao()->getCargo()->getNome(), 0, 1, 'L');
        $this->Cell(50, 8, 'Carga Horária: ' . $atuacao->getCargaHoraria(), 0, 0, 'L');
        $this->Cell(70, 8, 'Turno: ' . ($atuacao->getPeriodo() ? $atuacao->getPeriodo()->getNome() : ''), 0, 0, 'L');
        $this->Cell(70, 8, 'Classificação: ' . $this->vinculo->getInscricaoVinculacao()->getClassificacao(), 0, 1, 'L');
        if ($this->vinculo->getTipoVinculo()->getId() === TipoVinculo::EFETIVO) {
            $this->Cell(95, 8, 'Data da Nomeação: '. $this->vinculo->getDataInicio()->format('d/m/Y'), 0, 0, 'L');
            $this->Cell(95, 8, 'Data da Posse: '. $this->vinculo->getDataTermino()->format('d/m/Y'), 0, 1, 'L');
        } else {
            $this->Cell(120, 8, 'Período de Contratação: ' . $this->vinculo->getDataInicio()->format('d/m/Y') . ' a ' . $this->vinculo->getDataTermino()->format('d/m/Y'), 0, 0, 'L');
            $this->Cell(70, 8, 'Turma: ' . $atuacao->getObservacao(), 0, 1, 'L');
            $this->MultiCell(190, 8, 'Motivo: ' . $atuacao->getMotivoEncaminhamento(), 0, 'L');
        }
        $this->Cell(190, 20,'Assinatura: _____________________________________________',0,1,'C');

        /**
                    /* Assinatura Diretor DGP */
        $this->Ln(5);
        $this->Cell(95, 5,'', 0, 0);
        $this->Image(dirname(__FILE__) . '/../Resources/images/assinaturas/diretor-dgp.jpg',$this->w - 75, $this->y - 11, '35%');
        $this->Cell(95, 5, "______________________________", 0, 1, 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(95, 5, '', 0, 0);
        $this->Cell(95, 5, AssinaturasUtil::DIRETOR_DGP, 0, 1, 'C');
        $this->Cell(95, 5, '', 0, 0);
        $this->Cell(95, 5, 'Diretoria de Gestão de Pessoas', 0, 1, 'C');
        if ($this->atendente) {
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 9);
            $this->Cell(190, 8, 'Atendido por: ' . $this->atendente->getNome(), 0, 1, 'L');
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 8);
        }
    }
    
    function getVinculo() {
        return $this->vinculo;
    }

    function getAtendente() {
        return $this->atendente;
    }

    function setVinculo(Vinculo $vinculo) {
        $this->vinculo = $vinculo;
    }

    function setAtendente($atendente) {
        $this->atendente = $atendente;
    }
    
}
