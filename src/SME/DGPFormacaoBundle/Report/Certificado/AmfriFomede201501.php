<?php

namespace SME\DGPFormacaoBundle\Report\Certificado;

use SME\DGPFormacaoBundle\Report\Certificado\CertificadoReport;

class AmfriFomede201501 extends CertificadoReport {
    
    public function header($title = 'CERTIFICADO', $subtitle = '') {
        if($this->header) {
            $this->Image(
                dirname(__FILE__) . '/../../Resources/images/modelos/amfri-fomede201501/amfri.jpg', 
                70, 10, 33, 30
            );
            $this->Image(
                dirname(__FILE__) . '/../../Resources/images/brasao-municipal.jpg', 
                $this->w - 180, 10, 64, 16
            );
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
            $this->SetY(28);
            $this->SetX($this->w - 180);
            $this->Cell(64, 8, 'SECRETARIA DE EDUCAÇÃO', 'T', 1, 'C');
            $this->Image(
                dirname(__FILE__) . '/../../Resources/images/modelos/amfri-fomede201501/fomede.jpg', 
                $this->w - 100, 10, 62, 18
            );
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 28);
            $this->Ln(10);
            $this->SetX(20);
            $this->MultiCell($this->w - 40, 18, $title, '', 'C');
        }
    }
    
    protected function renderFront() {
        parent::renderFront();
        
    }
    

}

