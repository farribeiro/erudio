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

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Psr\Log\LoggerInterface;
use MatriculaBundle\Service\MatriculaFacade;
use CursoBundle\Service\TurmaFacade;
use CursoBundle\Service\EtapaFacade;

class MatriculaReportController extends Controller {
    
    private $matriculaFacade;
    private $turmaFacade;
    private $etapaFacade;
    private $logger;
    
    function __construct(MatriculaFacade $matriculaFacade, TurmaFacade $turmaFacade, 
            EtapaFacade $etapaFacade, LoggerInterface $logger) {
        $this->matriculaFacade = $matriculaFacade;
        $this->turmaFacade = $turmaFacade;
        $this->etapaFacade = $etapaFacade;
        $this->logger= $logger;
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Relatórios",
    *   description = "Atestado de Matrícula",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("atestados/matricula", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function atestadoAction(Request $request) {
        try {
           $matricula = $this->matriculaFacade->find($request->query->getInt('matricula'));
           $enturmacoes = $matricula->getEnturmacoesAtivas();
           $etapa = count($enturmacoes) 
                   ? $enturmacoes->first()->getTurma()->getEtapa()->getNomeExibicao() 
                   : 'Não Enturmado';
            return $this->render('reports/atestado/matricula.pdf.twig', [
                'instituicao' => $matricula->getUnidadeEnsino(),
                'matricula' => $matricula,
                'etapa' => $etapa
            ]);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Relatórios",
    *   description = "Ficha de rematrícula",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("ficha-rematricula", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function fichaRematriculaPorTurmaAction(Request $request) {
        try {
            $turma = $this->turmaFacade->find($request->query->getInt('turma'));
            $etapa = $turma->getEtapa();
            $etapaSeguinte = $this->etapaFacade->findOne([
                'ordem' => $etapa->getOrdem() + 1,
                'curso' => $etapa->getCurso()
            ]);
            return $this->render('reports/matricula/fichaRematricula.pdf.twig', [
                'instituicao' => $turma->getUnidadeEnsino(),
                'enturmacoes' => $turma->getEnturmacoes(),
                'etapaSeguinte' => $etapaSeguinte
            ]);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
}
