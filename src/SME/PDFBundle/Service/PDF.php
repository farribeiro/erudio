<?php

namespace SME\PDFBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use SME\PDFBundle\Report\PDFDocument;

class PDF
{

    public function render(PDFDocument $pdf) {
        return new Response(
            $pdf->render(),
            200,
            array('Content-type' => 'application/pdf')
        );
    }
    
}
