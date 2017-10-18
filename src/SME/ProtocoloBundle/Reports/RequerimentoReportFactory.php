<?php

namespace SME\ProtocoloBundle\Reports;

use SME\ProtocoloBundle\Entity\Protocolo;

class RequerimentoReportFactory {
    
    public function makeRequerimentoReport(Protocolo $protocolo) {
        $class = 'SME\\ProtocoloBundle\\Reports\\Impl\\' . $protocolo->getSolicitacao()->getNomeIdentificacao() . 'Report';
        $report = new $class($protocolo);
        return $report;
    }
    
}
