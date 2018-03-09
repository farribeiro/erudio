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
use CoreBundle\ORM\Exception\IllegalOperationException;
use MatriculaBundle\Service\MatriculaFacade;
use CursoBundle\Service\TurmaFacade;
use CursoBundle\Service\EtapaFacade;
use VinculoBundle\Service\VinculoFacade;
use MatriculaBundle\Entity\Matricula;
use MatriculaBundle\Entity\EtapaCursada;

class CertificadoReportController extends Controller {
    
    private $matriculaFacade;
    private $turmaFacade;
    private $etapaFacade;
    private $vinculoFacade;
    private $logger;
    
    function __construct(MatriculaFacade $matriculaFacade, TurmaFacade $turmaFacade, 
            EtapaFacade $etapaFacade, VinculoFacade $vinculoFacade, LoggerInterface $logger) {
        $this->matriculaFacade = $matriculaFacade;
        $this->turmaFacade = $turmaFacade;
        $this->etapaFacade = $etapaFacade;
        $this->vinculoFacade = $vinculoFacade;
        $this->logger= $logger;
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Certificados",
    *   description = "Certificado de conclusão de curso",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("certificado-conclusao", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function certificadoAction(Request $request) {
        
        $matricula = $this->matriculaFacade->find($request->query->getInt('matricula'));
        $vinculo = $this->vinculoFacade->find($request->query->getInt('vinculo'));
        if (!$matricula || !$matricula->getEtapaAtual()) {
            throw new IllegalOperationException('Não é possível emitir o certificado, aluno nunca foi enturmado');
        }
        if ($matricula->getStatus() !== Matricula::STATUS_APROVADO) {
            throw new IllegalOperationException('Não é possível emitir o certificado, o aluno ainda não foi aprovado');
        }
        try {
            return $this->render('reports/certificado/conclusao.pdf.twig', [
                'instituicao' => $matricula->getUnidadeEnsino(),
                'matricula' => $matricula, 
                'diretor' => $vinculo->getFuncionario()
            ]);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Certificados",
    *   description = "Certificado de conclusão de curso por turma",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("certificados-conclusao", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function certificadoPorTurmaAction(Request $request) {
        try {
            $turma = $this->turmaFacade->find($request->query->getInt('turma')); 
            $vinculo = $this->vinculoFacade->find($request->query->getInt('vinculo'));
            $enturmacoesAprovadas = $turma->getEnturmacoes()->filter(function($e) {
                return $e->getEtapaCursada() && 
                       $e->getEtapaCursada()->getStatus() === EtapaCursada::STATUS_APROVADO;
            });
            return $this->render('reports/certificado/conclusaoPorTurma.pdf.twig', [
                'instituicao' => $turma->getUnidadeEnsino(),
                'enturmacoes' => $enturmacoesAprovadas,
                'diretor' => $vinculo->getFuncionario()
            ]);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
}
