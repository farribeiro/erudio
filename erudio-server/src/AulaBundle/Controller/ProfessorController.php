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

namespace AulaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use VinculoBundle\Service\AlocacaoFacade;

/**
 * @FOS\NamePrefix("disciplinas")
 */
class ProfessorController extends Controller {
    
    private $alocacaoFacade;
    
    function __construct(AlocacaoFacade $alocacaoFacade) {
        $this->alocacaoFacade = $alocacaoFacade;
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
        *   section = "Módulo Professores",
    *   description = "Retorna todas as disciplinas ofertadas do professor autenticado",
    *   statusCodes = {
    *       200 = "Array de disciplinas"
    *   }
    * )
    * 
    * @FOS\Get("disciplinas")
    * @FOS\QueryParam(name = "emAndamento", nullable = true)
    */
    function getDisciplinasAction(ParamFetcherInterface $paramFetcher) {
        $professor = $this->getUser()->getPessoa();
        $emAndamento = $paramFetcher->get('emAndamento');
        $alocacoes = $this->alocacaoFacade->findAll(['funcionario' => $professor, 'professor' => true]);
        $disciplinas = array_reduce($alocacoes, function($acc, $alocacao) use ($emAndamento) {
            return array_merge($acc, $emAndamento 
                ? $alocacao->getDisciplinasMinistradasEmAndamento()->toArray() 
                : $alocacao->getDisciplinasMinistradas()->toArray()
            );
        }, []);
        return new JsonResponse(array_map(function($d) {
            $turma = $d->getTurma();
            return [
                'id' => $d->getId(),
                'nome' => $d->getNome(),
                'nomeExibicao' => $d->getNomeExibicao(),
                'turma' => [
                    'id' => $turma->getId(),
                    'nome' => $turma->getNomeCompleto(),
                    'status' => $turma->getStatus(),
                    'unidadeEnsino' => $turma->getUnidadeEnsino()->getNomeCompleto(),
                    'etapa' => [
                        'id' => $turma->getEtapa()->getId(),
                        'nome' => $turma->getEtapa()->getNome(),
                        'frequenciaUnificada' => $turma->getEtapa()->getFrequenciaUnificada()
                    ]
                ]
            ];
        }, $disciplinas));
    }
    
}
