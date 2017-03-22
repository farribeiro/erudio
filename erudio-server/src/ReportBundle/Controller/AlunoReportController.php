<?php

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;

class AlunoReportController extends Controller {
    
    function getMatriculaFacade() {
        return $this->get('facade.matricula.matriculas');
    }
    
    /**
    * @Route("/alunos/defasados", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function defasadosPorUnidadeAction(Request $request) {
        try {
            $turma = $this->getTurmaFacade()->find($request->query->get('turma'));
            return $this->render('reports/enturmacao/nominalPorTurma.pdf.twig', [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turma' => $turma,
                'enturmados' => $turma->getEnturmacoes()
            ]);
        } catch (\Exception $ex) {
            $this->get('logger')->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
}
