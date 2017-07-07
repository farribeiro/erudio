<?php

namespace SME\FilaUnicaBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\FilaUnicaBundle\Entity\Inscricao;
use SME\FilaUnicaBundle\Entity\TipoInscricao;

class TermoDesistenciaReport extends PDFDocument {
    
    private $inscricao;
    
    function header($title = 'SECRETARIA DE EDUCAÇÃO', $subtitle = 'Termo de Desistência') {
        parent::header($title, 
                $this->inscricao->getTipoInscricao()->getId() === TipoInscricao::TRANSFERENCIA 
                ? $subtitle . ' - Requerimento de Transferência' 
                : $subtitle . ' - Fila Única');
    }
    
    public function build() {
        $this->AddPage();
        $this->AliasNbPages();
        $this->rel();
    }
    
    private function rel() {
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Ln(20);
        $texto = $this->inscricao->getTipoInscricao()->getId() === TipoInscricao::TRANSFERENCIA
                ? 'do requerimento de transferência para a unidade de ensino ' . $this->getInscricao()->getUnidadeDestino()->getNome() . ','
                : 'da solicitação de vaga na ' . $this->getInscricao()->getZoneamento()->getNome() . ' (' . $this->getInscricao()->getZoneamento()->getDescricao() . '),';
        $this->MultiCell($this->ws, 8, 'Eu, ______________________________________________________________, portador(a) do CPF nº '
                . '____________________________, responsável pela criança ' . $this->inscricao->getCrianca()->getNome()
                . ', residente no endereço ' . $this->inscricao->getCrianca()->getEndereco()->getLogradouro()
                . ' ' . $this->inscricao->getCrianca()->getEndereco()->getNumero() . ' / '
                . $this->inscricao->getCrianca()->getEndereco()->getBairro() . ', declaro que estou desistindo '
                . $texto
                . ' com o protocolo nº ' . $this->inscricao->getProtocolo() . ' do Programa Fila Única (Lei Nº 5.542, de 28 de junho de 2010).', '', 'J');
        $this->Ln(20);
        
        $now = new \DateTime();
        $this->MultiCell($this->ws, 5, 'Itajaí, ' . $now->format('d/m/Y'), '', 'R');
        $this->MultiCell($this->ws, 5, 'Horário: ' . $now->format('H:i:s'), '', 'R');
        
        $this->Ln(20);
        $this->Cell(95, 5, '___________________________________', '', 0, 'C');
        $this->Cell(95, 5, '___________________________________', '', 1, 'C');
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