<?php

namespace SME\DGPFormacaoBundle\Report\Certificado;

use SME\DGPFormacaoBundle\Report\Certificado\CertificadoReport;

class Unesco201401 extends CertificadoReport {
    
    protected function renderFront() {
        $this->header = false;
        $this->AddPage('L');
        $this->AliasNbPages();
        $this->Image(
            dirname(__FILE__) . '/../../Resources/images/modelos/unesco201401/front.jpg', 
            0, 0, 
            $this->w, $this->h
        );
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 24);
        $this->SetX(0);
        $this->setY($this->h / 2 - 10);
        $this->Cell($this->w - 15, 20, $this->getMatricula()->getPessoa()->getNome(), '', 1, 'C');
    }
    
    protected function renderBack() {
        $this->header = false;
        $this->AddPage('L');
        $this->AliasNbPages();
        $this->Image(
            dirname(__FILE__) . '/../../Resources/images/modelos/unesco201401/back.jpg', 
            20, 20, 
            $this->w - 40, $this->h - 40
        );
    }

}
