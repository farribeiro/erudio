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

namespace IntegracaoIEducarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Psr\Log\LoggerInterface;
use IntegracaoIEducarBundle\Service\IEducarFacade;
use IntegracaoIEducarBundle\Service\ImportacaoDadosFacade;

class IndexController extends Controller {
    
    const URL_SERVICO = 'https://intranet.itajai.sc.gov.br/educar_relatorio_historico_escolar_service.php';
    
    private $ieducarFacade;
    private $importacaoDadosFacade;
    private $logger;
    
    function __construct(IEducarFacade $ieducarFacade, ImportacaoDadosFacade $importacaoDadosFacade, LoggerInterface $logger) {
        $this->ieducarFacade = $ieducarFacade;
        $this->importacaoDadosFacade = $importacaoDadosFacade;
        $this->logger = $logger;
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Integração I-Educar",
    *   description = "Unidades de Ensino do I-Educar",
    *   statusCodes = {
    *       200 = "Lista de unidades de ensino"
    *   }
    * )
    * 
    * @Route("unidades-ensino", defaults={ "_format" = "json" })
    */
    function getUnidadesEnsinoAction(Request $request) {
        try {
            $param = $request->query->has('nome') ? urlencode($request->query->get('nome')) : 'e';
            $unidadesEnsino = file_get_contents(self::URL_SERVICO . "?ws=esc&esc=" . $param);
            return new Response($unidadesEnsino);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Integração I-Educar",
    *   description = "Alunos do I-Educar",
    *   statusCodes = {
    *       200 = "Lista de alunos com nome e data de nascimento informados"
    *   }
    * )
    * 
    * @Route("alunos", defaults={ "_format" = "json" })
    */
    function getAlunosAction(Request $request) {
        try {
            $nome = urlencode($request->query->get('nome'));
            $dataNascimento = $request->query->get('dataNascimento', '');
            $alunos = file_get_contents(self::URL_SERVICO . "?ws=alu&nasc={$dataNascimento}&nome={$nome}");
            return new Response($alunos);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Integração I-Educar",
    *   description = "Histórico do I-Educar em formato JSON",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("historicos", defaults={ "_format" = "json" })
    */
    function getHistoricoAction(Request $request) {
        try {
            $unidadesEnsino = $request->query->getInt('unidadeEnsino');
            $aluno = $request->query->getInt('aluno');
            $historico = file_get_contents(self::URL_SERVICO . "?ws=his&alu={$aluno}&esc={$unidadesEnsino}");
            return new Response($historico);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Integração I-Educar",
    *   description = "Histórico do I-Educar em formato PDF",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("historicos-impressos", defaults={ "_format" = "pdf" })
    */
    function getHistoricoConsolidadoAction(Request $request) {
        try {
            $unidadesEnsino = $request->query->getInt('unidadeEnsino');
            $aluno = $request->query->getInt('aluno');
            $options = ['http' => [
                'method' => 'GET',
                'header' => 'Accept: application/pdf'
            ]];
            $context = stream_context_create($options);
            $historico = file_get_contents(
                self::URL_SERVICO . "?ws=his&alu={$aluno}&esc={$unidadesEnsino}",
                false,
                $context
            );
            return new Response($historico);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
     /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Integração I-Educar",
    *   description = "Executar importação dos dados do I-Educar. Esta é uma operação idempotente.",
    *   statusCodes = {
    *       204 = "Sem retorno"
    *   }
    * )
    * 
    * @Route("importacao")
    * @Method({"POST","HEAD"})
    */
    function postImportacaoCompletaAction(Request $request) {
        try {
            $this->importacaoDadosFacade->importarMatriculas(
                $request->query->get('curso', 10),
                $request->query->get('etapa', null),
                $request->query->get('unidadeEnsino', null)
            );
            return new Response('Importação realizada', 204);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
}
