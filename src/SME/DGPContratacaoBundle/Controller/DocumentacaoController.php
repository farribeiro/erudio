<?php

namespace SME\DGPContratacaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\DGPBundle\Entity\Vinculo;
use SME\DGPContratacaoBundle\Report\FichaCadastralReport;
use SME\DGPContratacaoBundle\Report\EncaminhamentoReport;
use SME\DGPContratacaoBundle\Report\ChecklistAdmissaoReport;
use SME\DGPContratacaoBundle\Report\TermoPosseReport;
use SME\DGPContratacaoBundle\Report\ParecerRegularidadeReport;

class DocumentacaoController extends Controller {
    
    public function imprimirFichaCadastralAction(Vinculo $vinculo) {
        $doc = new FichaCadastralReport();
        $doc->setAtendente($this->getUser()->getPessoa());
        $doc->setVinculo($vinculo);
        return $this->get('pdf')->render($doc);
    }
    
    public function imprimirEncaminhamentoAction(Vinculo $vinculo) {
        $doc = new EncaminhamentoReport();
        $doc->setAtendente($this->getUser()->getPessoa());
        $doc->setVinculo($vinculo);
        return $this->get('pdf')->render($doc);
    }
    
    public function imprimirChecklistAction(Vinculo $vinculo) {
        $doc = new ChecklistAdmissaoReport();
        $doc->setAtendente($this->getUser()->getPessoa());
        $doc->setVinculo($vinculo);
        return $this->get('pdf')->render($doc);
    }
    
    public function imprimirTermoPosseAction(Vinculo $vinculo) {
        $doc = new TermoPosseReport();
        $doc->setVinculo($vinculo);
        return $this->get('pdf')->render($doc);
    }
    
    public function imprimirParecerRegularidadeAction(Vinculo $vinculo) {
        $doc = new ParecerRegularidadeReport();
        $doc->setVinculo($vinculo);
        return $this->get('pdf')->render($doc);
    }
    
}
