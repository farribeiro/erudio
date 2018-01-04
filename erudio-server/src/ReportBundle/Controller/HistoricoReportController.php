<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use Ps\PdfBundle\Annotation\Pdf;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use MatriculaBundle\Service\MatriculaFacade;
use CursoBundle\Service\TurmaFacade;
use MatriculaBundle\Service\HistoricoFacade;

class HistoricoReportController extends Controller {
    
    private $matriculaFacade;
    private $turmaFacade;
    private $historicoFacade;
    
    function __construct(MatriculaFacade $matriculaFacade, TurmaFacade $turmaFacade, HistoricoFacade $historicoFacade,
        LoggerInterface $logger) {
        $this->logger = $logger;
        $this->matriculaFacade = $matriculaFacade;
        $this->turmaFacade = $turmaFacade;
        $this->historicoFacade = $historicoFacade;
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Relatórios",
    *   description = "Histórico individual",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/historico", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function individualAction(Request $request) {
        try {
            $matricula = $this->matriculaFacade->find($request->query->getInt('matricula'));
            $historico = $this->gerarHistorico($matricula);
            return $this->render('reports/historico/historico.pdf.twig', [
                'instituicao' => $matricula->getUnidadeEnsino(),
                'historicos' => [$historico]
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
    *   description = "Históricos ds alunos de uma turma",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/historico-turma", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function turmaAction(Request $request) {
        try {
            $turma = $this->turmaFacade->find($request->query->getInt('turma'));
            $historicos = $turma->getEnturmacoes()->map(function($e) {
                return $this->gerarHistorico($e->getMatricula());
            });
            return $this->render('reports/historico/historico.pdf.twig', [
                'instituicao' => $turma->getUnidadeEnsino(),
                'historicos' => $historicos
            ]);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    function gerarHistorico($matricula) {
        $dadosHistorico = $this->historicoFacade->gerarPreliminar($matricula);
        return [
            'matricula' => $matricula,
            'aluno' => $matricula->getAluno(),
            'dados' => $dadosHistorico
        ];
    }
    
}
