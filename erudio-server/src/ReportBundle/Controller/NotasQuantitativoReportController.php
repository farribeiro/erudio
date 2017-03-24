<?php

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;

class NotasQuantitativoReportController extends Controller {
    
    function getAulaFacade() {
        return $this->get('facade.calendario.aulas');
    }
    
    function getDisciplinaOfertadaFacade() {
        return $this->get('facade.curso.disciplinas_ofertadas');
    }
    
    function getTurmaFacade() {
        return $this->get('facade.curso.turmas');
    }
    
    /**
    * @Route("/notas-quantitativo", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function diarioAction(Request $request) {
        try {
            $mes = $request->query->getInt('mes', (new \DateTime())->format('m'));
            $media = $request->query->getInt('media', 1);
            $disciplina = $this->getDisciplinaOfertadaFacade()->find(
                $request->query->getInt('disciplina')
            );
            return $this->render('reports/nota/notaQuantitativo.pdf.twig', [
                'instituicao' => $disciplina->getTurma()->getUnidadeEnsino(),
                'mes' => $this->mesToString($mes),
                'diarios' => [ $this->gerarDiario($disciplina, $mes) ]
            ]);
        } catch (\Exception $ex) {
            $this->get('logger')->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    /**
    * @Route("/notas-quantitativos", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function diariosAction(Request $request) {
        try {
            $auto = $request->query->get('auto', true);
            $media = $request->query->getInt('media', 1);
            $turma = $this->getTurmaFacade()->find($request->query->getInt('turma'));
            $diarios = [];
            foreach ($turma->getDisciplinas() as $disciplina) {
                $diarios[] = $this->gerarDiario($disciplina, $auto);
            }
            return $this->render('reports/nota/notaQuantitativo.pdf.twig', [
                'instituicao' => $disciplina->getTurma()->getUnidadeEnsino(),
                'media' => $media,
                'diarios' => $diarios
            ]);
        } catch (\Exception $ex) {
            $this->get('logger')->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    /**
     * Gera a estrutura de um diário de frequência, para determinada disciplina ofertada, 
     * em determinado mês.
     * 
     * @param $disciplina
     * @param $mes
     * @return array diário de frequência
     */
    private function gerarDiario($disciplina, $autoPreenchimento = true) {
        $enturmacoes = $disciplina->getTurma()->getEnturmacoes()->toArray();
        usort($enturmacoes, function($e1, $e2) {
            return strcasecmp($e1->getMatricula()->getAluno()->getNome(), 
                    $e2->getMatricula()->getAluno()->getNome()); 
        });
        return [
            'disciplina' => $disciplina,
            'enturmacoes' => $enturmacoes
        ];
    }
    
}
