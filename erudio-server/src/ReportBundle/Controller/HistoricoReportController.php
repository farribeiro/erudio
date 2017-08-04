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
use Ps\PdfBundle\Annotation\Pdf;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use MatriculaBundle\Service\MatriculaFacade;
use MatriculaBundle\Service\HistoricoFacade;

class HistoricoReportController extends Controller {
    
    private $matriculaFacade;
    private $historicoFacade;
    
    function __construct(MatriculaFacade $matriculaFacade, HistoricoFacade $historicoFacade) {
        $this->matriculaFacade = $matriculaFacade;
        $this->historicoFacade = $historicoFacade;
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Relatórios",
    *   description = "Histórico preliminar individual",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/historico", defaults={ "_format" = "json" })
    * 
    */
    function individualAction(Request $request) {
        try {
            $matricula = $this->matriculaFacade->find($request->query->getInt('matricula'));
            $dadosHistorico = $this->historicoFacade->gerarPreliminar($matricula);
            return new \Symfony\Component\HttpFoundation\JsonResponse($dadosHistorico);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
}
