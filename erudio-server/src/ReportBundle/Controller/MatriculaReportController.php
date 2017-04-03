<?php

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class MatriculaReportController extends Controller {
    
    function getMatriculaFacade() {
        return $this->get('facade.matricula.matriculas');
    }
    
    function getEnturmacaoFacade() {
        return $this->get('facade.matricula.enturmacoes');
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
           $matricula = $this->getMatriculaFacade()->find($request->query->getInt('matricula'));
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
            $this->get('logger')->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
}
