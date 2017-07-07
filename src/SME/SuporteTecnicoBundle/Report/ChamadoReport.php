<?php

namespace SME\SuporteTecnicoBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\SuporteTecnicoBundle\Entity\Chamado;

class ChamadoReport extends PDFDocument {
    
    private $chamados;
    private $numero;
    
    public function build() {	
        foreach($this->chamados as $chamado) {
            $this->numero = $chamado->getId();
            $this->AddPage();
            $this->AliasNbPages();
            $this->rel($chamado);
        }
    }
    
    function header($title = 'SECRETARIA DE EDUCAÇÃO', $subtitle = 'Suporte Técnico - Chamado Nº ') {
        parent::header($title, $subtitle . $this->numero . ' ');
        $this->Ln(5);
    }

    private function rel(Chamado $chamado) {
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $this->MultiCell($this->w, 12, 'Informações do Chamado Nº ' . $chamado->getId());
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell(20, 6, 'Unidade:', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(170, 6, $chamado->getLocal()->getNome(), 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell(20, 6, 'Endereço:', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(75, 6, $chamado->getEndereco(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell(20, 6, 'Telefone:', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(75, 6, $chamado->getTelefones(), 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell(20, 6, 'Solicitante:', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(75, 6, $chamado->getPessoaCadastrou()->getNome(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell(20, 6, 'E-mail:', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(75, 6, $chamado->getPessoaCadastrou()->getEmail(), 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell(20, 6, 'Categoria:', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(170, 6, $chamado->getCategoria()->getNomeHierarquico(), 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell(20, 6, 'Status:', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(75, 6, $chamado->getStatus()->getNome(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell(20, 6, 'Prioridade:', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(75, 6, $chamado->getPrioridade()->getNome(), 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell(20, 6, 'Criado em:', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(75, 6, $chamado->getDataCadastro()->format('d/m/Y H:i'), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell(24, 6, 'Fechado em:', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(75, 6, $chamado->getDataEncerramento() 
                ? $chamado->getDataEncerramento()->format('d/m/Y H:i') : '-', 0, 1, 'L');
        
        $tags = '';
        foreach($chamado->getTags() as $tag) {
            $tags = $tags . $tag->getNome() . '; ';
        }
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell(35, 6, 'Itens relacionados:', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(155, 6, $tags, 0, 1, 'L');
        
        $this->Ln(2);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->MultiCell(190, 6, 'Descrição', 'LTR', 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->MultiCell(190, 6, trim($chamado->getDescricao()), 'LBR', 'L');
        
        if($chamado->getEncerrado()) {
            $this->Ln(2);
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
            $this->MultiCell(190, 6, 'Solução', 'LTR', 'L');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
            $this->MultiCell(190, 6, trim($chamado->getSolucao()), 'LBR', 'L');
        }
        
        if(count($chamado->getAtividades())) {
            $this->Ln(2);
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
            $this->MultiCell($this->w, 12, 'Atividades');
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
            $this->SetWidths(array(25, 25, 50, 90));
            $this->SetAligns(array('C', 'C', 'C', 'C'));
            $this->Row(array('Início', 'Término', 'Técnicos', 'Descrição'));
            $this->SetAligns(array('C', 'C', 'L', 'L'));
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
            foreach($chamado->getAtividades() as $atividade) {
                $tecnicos = '';
                foreach($atividade->getTecnicos() as $tecnico) {
                    $tecnicos = $tecnicos . $tecnico->getNome() . '; ';
                }
                $tecnicos = substr($tecnicos, 0, strlen($tecnicos) - 2);
                $this->Row(array(
                    $atividade->getInicio()->format('d/m/Y H:i'),
                    $atividade->getTermino()->format('d/m/Y H:i'),
                    $tecnicos,
                    $atividade->getDescricao()
                ));
            }
        }
        if(count($chamado->getAnotacoes())) {
            $this->Ln(2);
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
            $this->MultiCell($this->w, 12, 'Anotações');
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
            $this->SetWidths(array(100, 30, 60));
            $this->SetAligns(array('C', 'C', 'C'));
            $this->Row(array('Descrição', 'Data', 'Autor'));
            $this->SetAligns(array('L', 'C', 'C'));
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
            foreach($chamado->getAnotacoes() as $anotacao) {
                $this->Row(array(
                    $anotacao->getDescricao(),
                    $anotacao->getDataCadastro()->format('d/m/Y H:i'),
                    $anotacao->getPessoaCadastrou()->getNome()
                ));
            }
        }
    }
    
    public function getChamados() {
        return $this->chamados;
    }

    public function setChamados(array $chamados) {
        $this->chamados = $chamados;
    }
    
}
