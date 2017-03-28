<?php

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class AlunoReportController extends Controller {
    
    function getCursoFacade() {
        return $this->get('facade.curso.cursos');
    }
    
    function getCursoOfertadoFacade() {
        return $this->get('facade.curso.cursos_ofertados');
    }
    
    function getEnturmacaoFacade() {
        return $this->get('facade.matricula.enturmacoes');
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Relatórios",
    *   description = "Relatório nominal de alunos defasados por unidade de ensino",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/alunos/defasados-nominal-geral", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function defasadosNominalPorCursoAction(Request $request) {
        try {
            $curso = $this->getCursoFacade()->find($request->query->getInt('curso'));
            $ofertados = $this->getCursoOfertadoFacade()->findAll(['curso' => $curso->getId()]);
            $relatorios = [];
            foreach ($ofertados as $c) {
                $relatorios[] = $this->gerarRelatorioDefasados($c);
            }
            return $this->render('reports/aluno/defasados-nominal.pdf.twig', [
                'instituicao' => $curso->getInstituicao(),
                'relatorios' => $relatorios
            ]);
        } catch (\Exception $ex) {
            $this->get('logger')->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
     /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Relatórios",
    *   description = "Relatório nominal de alunos defasados por unidade de ensino",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/alunos/defasados-nominal", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function defasadosNominalPorCursoOfertadoAction(Request $request) {
        try {
            $cursoOfertado = $this->getCursoOfertadoFacade()->find($request->query->getInt('curso'));
            return $this->render('reports/aluno/defasados-nominal.pdf.twig', [
                'instituicao' => $cursoOfertado->getUnidadeEnsino(),
                'relatorios' => [ $this->gerarRelatorioDefasados($cursoOfertado) ]
            ]);
        } catch (\Exception $ex) {
            $this->get('logger')->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    private function gerarRelatorioDefasados($cursoOfertado) {
        $relatorio = ['unidadeEnsino' => $cursoOfertado->getUnidadeEnsino(), 'alunosPorEtapa' => []];
        foreach ($cursoOfertado->getCurso()->getEtapas() as $etapa) {
            $enturmacoes = $this->getEnturmacaoFacade()->getAlunosDefasados($cursoOfertado, $etapa);
            if (count($enturmacoes)) {
                $relatorio['alunosPorEtapa'][$etapa->getNomeExibicao()] = $enturmacoes;
            }
        }
        return $relatorio;
    }
    
}
