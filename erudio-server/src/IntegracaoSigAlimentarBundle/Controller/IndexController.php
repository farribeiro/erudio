<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    @author Municipio de Itajaí - Secretaria de Educação - DITEC         *
 *    @updated 30/06/2016                                                  *
 *    Pacote: Erudio                                                       *
 *                                                                         *
 *    Copyright (C) 2016 Prefeitura de Itajaí - Secretaria de Educação     *
 *                       DITEC - Diretoria de Tecnologias educacionais     *
 *                        ditec@itajai.sc.gov.br                           *
 *                                                                         *
 *    Este  programa  é  software livre, você pode redistribuí-lo e/ou     *
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme     *
 *    publicada pela Free  Software  Foundation,  tanto  a versão 2 da     *
 *    Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.    *
 *                                                                         *
 *    Este programa  é distribuído na expectativa de ser útil, mas SEM     *
 *    QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-     *
 *    ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-     *
 *    sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.     *
 *                                                                         *
 *    Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU     *
 *    junto  com  este  programa. Se não, escreva para a Free Software     *
 *    Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
 *    02111-1307, USA.                                                     *
 *                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace IntegracaoSigAlimentarBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\ViewHandlerInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use MatriculaBundle\Service\EnturmacaoFacade;
use PessoaBundle\Service\UnidadeEnsinoFacade;
use IntegracaoSigAlimentarBundle\Model\MatriculaSig;
use IntegracaoSigAlimentarBundle\Model\UnidadeEnsinoSig;

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
    * @FOS\Get("enturmacoes")
    * @FOS\QueryParam(name = "unidadeEnsino", requirements="\d+", nullable = true) 
    */
    function getEnturmacoesAction(ParamFetcherInterface $paramFetcher) {
        $unidade = $paramFetcher->get('unidadeEnsino');
        $params = $unidade ? ['turma_unidadeEnsino' => $unidade] : [];
        $enturmacoes = $this->enturmacaoFacade->findAll($params, null, 5000);
        $enturmacoesSig = array_map(function($e) {
            return MatriculaSig::fromEnturmacao($e);
        }, $enturmacoes);
        return $this->viewHandler->handle(View::create(['dados' => $enturmacoesSig]));
    }
    
    /**
    * @FOS\Get("unidades-ensino")
    */
    function getUnidadesAction() {
        $unidades = $this->unidadeEnsinoFacade->findAll();
        $unidadesSig = array_map(function($u) {
            return UnidadeEnsinoSig::fromUnidadeEnsino($u);
        }, $unidades);
        return $this->viewHandler->handle(View::create($unidadesSig));
    }
    
}
