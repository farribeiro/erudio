<?php


namespace SME\DGPContratacaoBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\DGPBundle\Entity\TipoVinculo;
use SME\DGPBundle\Util\AssinaturasUtil;
use SME\DGPBundle\Entity\Vinculo;
use SME\CommonsBundle\Util\DateTimeUtil;

class ParecerRegularidadeReport extends PDFDocument {
    
    private $vinculo;
    
    public function build() {
        $this->AddPage();
        $this->AliasNbPages();
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $tipo = $this->vinculo->getTipoVinculo()->getId() === TipoVinculo::EFETIVO ? 'EFETIVO' : 'ACT';
        $hoje = new \DateTime();
        $this->MultiCell(190, 6, 'PARECER SOBRE REGULARIDADE DOS ATOS DE ADMISSÃO ' . $tipo . ' - Nº ' . ($this->vinculo->getNumeroControle() ? $this->vinculo->getNumeroControle() : '_____') . '/' . $hoje->format('Y'), 1, 'C');
        $this->Ln(15);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->MultiCell(190, 6, 'Após o exame e conferência da documentação anexa aos atos, considero REGULAR, ' .
                'sob os aspectos legais e formais, a admissão no serviço público municipal de ' .
                $this->vinculo->getServidor()->getNome() . '.', 0, 'J');
        $this->Ln(2);
        $this->MultiCell(190, 6, 'Arquiva-se os presentes autos, para fins de inspeção ou auditoria "in loco" pelo ' .
                'Tribunal de Contas do Estado, conforme previsto no § 1º do art. 10, da Instrução Normativa N.TC. ' . 
                '11/2011, do TCE-SC.', 0, 'J');
        $this->Ln(2);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->MultiCell(190, 6, 'É o parecer.', 0, 'J');
        $this->Ln(10);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->MultiCell(190, 6, 'Itajaí (SC), ' . DateTimeUtil::dataPorExtenso(new \DateTime()), 0, 'J');
        
        $this->Ln(10);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 9);
        $this->MultiCell(190, 4, '_________________________________________', 0, 'C');
        $this->MultiCell(190, 4, AssinaturasUtil::FISCAL_CONTROLE_INTERNO, 0, 'C');
        $this->MultiCell(190, 4, AssinaturasUtil::CARGO_FISCAL_CONTROLE_INTERNO, 0, 'C');
        $this->MultiCell(190, 4, 'Professor III', 0, 'C');
        $this->MultiCell(190, 4, 'Marcelo Bomfim Caetano', 0, 'C');
        $this->MultiCell(190, 4, '(Unidade Setorial de Controle Interno)', 0, 'C');
    }
    
    function getVinculo() {
        return $this->vinculo;
    }

    function setVinculo(Vinculo $vinculo) {
        $this->vinculo = $vinculo;
    }

}
