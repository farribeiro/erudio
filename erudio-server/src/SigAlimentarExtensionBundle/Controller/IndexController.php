<?php

namespace SigAlimentarExtensionBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use \FOS\RestBundle\View\ViewHandlerInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use MatriculaBundle\Service\EnturmacaoFacade;
use PessoaBundle\Service\UnidadeEnsinoFacade;
use SigAlimentarExtensionBundle\Model\MatriculaSig;
use SigAlimentarExtensionBundle\Model\UnidadeEnsinoSig;

/**
* @FOS\Prefix("sig-alimentar")
*/
class IndexController {
    
    private $viewHandler;
    private $enturmacaoFacade;
    private $unidadeEnsinoFacade;
    
    function __construct(ViewHandlerInterface $viewHandler, UnidadeEnsinoFacade $unidadeEnsinoFacade,
            EnturmacaoFacade $enturmacaoFacade) {
        $this->viewHandler = $viewHandler;
        $this->enturmacaoFacade = $enturmacaoFacade;
        $this->unidadeEnsinoFacade = $unidadeEnsinoFacade;
    }
    
    /**
    * @FOS\QueryParam(name = "unidade", requirements="\d+", nullable = true) 
    * @FOS\QueryParam()
    */
    function getEnturmacoesAction(ParamFetcherInterface $paramFetcher) {
        $unidade = $paramFetcher->get('unidade');
        $params = $unidade ? ['turma_unidadeEnsino' => $unidade] : [];
        $enturmacoes = $this->enturmacaoFacade->findAll($params, null, 5000);
        $enturmacoesSig = array_map(function($e) {
            return MatriculaSig::fromEnturmacao($e);
        }, $enturmacoes);
        return $this->viewHandler->handle(View::create(['dados' => $enturmacoesSig]));
    }
    
    /**
    * @FOS\Get("/unidades-ensino")
    */
    function getUnidadesAction() {
        $unidades = $this->unidadeEnsinoFacade->findAll();
        $unidadesSig = array_map(function($u) {
            return UnidadeEnsinoSig::fromUnidadeEnsino($u);
        }, $unidades);
        return $this->viewHandler->handle(View::create($unidadesSig));
    }
    
}
