<?php

namespace SME\SuporteTecnicoBundle\Report;

use SME\PDFBundle\Report\PDFDocument;

class ListaChamadosReport extends PDFDocument {
    
    private $chamados;
    
    public function build() {	
        $this->AddPage('L');
        $this->AliasNbPages();
        $this->MultiCell(0, 12, count($this->chamados) . ' chamado(s) encontrado(s)');
        $this->Ln(2);
        $this->SetWidths(array(60, 30, 30, 45, 25, 25, 30, 30));
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $this->Row(array('Categoria', 'Tags', 'Responsável', 'Unidade', 'Criado em', 'Encerrado em', 'Prioridade', 'Status'));
        $this->SetAligns(array('C', 'C', 'C', 'L', 'C', 'C', 'C', 'C'));
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        foreach($this->chamados as $chamado) {
            $tags = '';
            foreach($chamado->getTags() as $tag) {
                $tags = $tags . $tag->getNome() . '; ';
            }
            $tags = substr($tags, 0, strlen($tags) - 2);
            $this->Row(array(
                $chamado->getCategoria()->getNomeHierarquico(),
                $tags,
                $chamado->getCategoria()->getEquipe()->getDepartamento(),
                $chamado->getLocal()->getNome(),
                $chamado->getDataCadastro()->format('d/m/Y H:i'),
                $chamado->getEncerrado() ? $chamado->getDataEncerramento()->format('d/m/Y H:i') : '-',
                $chamado->getPrioridade()->getNome(),
                $chamado->getStatus()->getNome()
            ));
        }
    }
    
    function header($title = 'SECRETARIA DE EDUCAÇÃO', $subtitle = 'Suporte Técnico - Listagem de Chamados') {
        parent::header($title, $subtitle);
        $this->Ln(5);
    }
    
    public function getChamados() {
        return $this->chamados;
    }

    public function setChamados(array $chamados) {
        $this->chamados = $chamados;
    }
    
}
