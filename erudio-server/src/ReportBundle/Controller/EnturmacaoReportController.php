<?php

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class EnturmacaoReportController extends Controller {
    
    function getTurmaFacade() {
        return $this->get('facade.curso.turmas');
    }
    
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
    *   description = "Relação nominal de alunos enturmados por turma",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/enturmacoes/nominal-turma", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function nominalPorTurmaAction(Request $request) {
        try {
            $turma = $this->getTurmaFacade()->find($request->query->get('turma'));
            $enturmacoes = $turma->getEnturmacoes()->toArray();
            usort($enturmacoes, function($e1, $e2) {
                return strcasecmp($e1->getMatricula()->getAluno()->getNome(), 
                        $e2->getMatricula()->getAluno()->getNome()); 
            });
            return $this->render('reports/enturmacao/nominalPorTurma.pdf.twig', [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turma' => $turma,
                'enturmados' => $enturmacoes
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
    *   description = "Relatório quantitativo de alunos enturmados por instituição",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/enturmacoes/quantitativo-instituicao", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml", enableCache = true)
    */
    function quantitativoPorInstituicaoAction(Request $request) {
        try {
            $instituicao = $this->getDoctrine()->getRepository('PessoaBundle:Instituicao')->find(
                ['id' => $request->query->getInt('instituicao', 0), 'ativo' => true]
            );
            $tipo = $request->query->getInt('tipo', 0);
            $unidades = $this->getDoctrine()->getRepository('PessoaBundle:UnidadeEnsino')->findBy(
                ['instituicaoPai' => $instituicao, 'ativo' => true, 'tipo' => $tipo]
            );
            $relatorios = [];
            foreach ($unidades as $unidade) {
                $relatorios[] = $this->gerarRelatorio($unidade);
            }
            return $this->render('reports/enturmacao/quantitativoPorInstituicao.pdf.twig', [
                'instituicao' => $instituicao,
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
    *   description = "Relatório quantitativo de alunos enturmados por unidade de ensino",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/enturmacoes/quantitativo-unidade", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function quantitativoPorUnidadeAction(Request $request) {
        try {
            $unidadeEnsino = $this->getDoctrine()->getRepository('PessoaBundle:UnidadeEnsino')->findOneBy(
                ['id' => $request->query->getInt('unidadeEnsino', 0), 'ativo' => true]
            );
            $relatorio = $this->gerarRelatorio($unidadeEnsino);
            return $this->render('reports/enturmacao/quantitativoPorUnidade.pdf.twig', [
                'instituicao' => $unidadeEnsino,
                'enturmados' => $relatorio['enturmados'],
                'totais' => $relatorio['totais']
            ]);
        } catch (\Exception $ex) {
            $this->get('logger')->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    private function gerarRelatorio($unidadeEnsino) {
        $turmas = $this->getTurmaFacade()->findAll(['unidadeEnsino' => $unidadeEnsino->getId()]);
        $enturmados = [];
        $totais = ['masculino' => 0, 'feminino' => 0];
        foreach ($turmas as $turma) {
            $total = $this->getEnturmacaoFacade()->countByTurma($turma);
            $masc = $this->getEnturmacaoFacade()->countByTurma($turma, 'M');
            $enturmados[] = [
                'turma' => $turma,
                'masculino' => $masc,
                'feminino' => $total - $masc,
                'total' => $total
            ];
            $totais['masculino'] += $masc;
            $totais['feminino'] += ($total - $masc);
        }
        return ['unidadeEnsino' => $unidadeEnsino, 'enturmados' => $enturmados, 'totais' => $totais];
    }
    
}
