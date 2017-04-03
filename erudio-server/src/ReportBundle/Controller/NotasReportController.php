<?php

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;

class NotasReportController extends Controller {
    
    function getAulaFacade() {
        return $this->get('facade.calendario.aulas');
    }
    
    function getDisciplinaOfertadaFacade() {
        return $this->get('facade.curso.disciplinas_ofertadas');
    }
    
    function getTurmaFacade() {
        return $this->get('facade.curso.turmas');
    }
    
    function getHabilidadeFacade() {
        return $this->get('facade.avaliacao.habilidades');
    }
    
    function getConceitoFacade() {
        return $this->get('facade.avaliacao.conceitos');
    }
    
    /**
    * @Route("/diario-notas", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function diarioAction(Request $request) {
//        try {
//            $mes = $request->query->getInt('mes', (new \DateTime())->format('m'));
//            $disciplina = $this->getDisciplinaOfertadaFacade()->find(
//                $request->query->getInt('disciplina')
//            );
//            return $this->render('reports/nota/notaQuantitativo.pdf.twig', [
//                'instituicao' => $disciplina->getTurma()->getUnidadeEnsino(),
//                'mes' => $this->mesToString($mes),
//                'enturmacoes' => $disciplina->getTurma()->getEnturmacoes(),
//                'diarios' => [ $this->gerarDiario($disciplina, $mes) ]
//            ]);
//        } catch (\Exception $ex) {
//            $this->get('logger')->error($ex->getMessage());
//            return new Response($ex->getMessage(), 500);
//        }
    }
    
    /**
    * @Route("/diarios-notas", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function diariosAction(Request $request) {
        try {
            $auto = $request->query->get('auto', true);
            $media = $request->query->getInt('media', 1);
            $turma = $this->getTurmaFacade()->find($request->query->getInt('turma'));
            $diarios = [];
            foreach ($turma->getDisciplinas() as $disciplina) {
                $diarios[] = $this->gerarDiario($disciplina, $media, $auto);
            }
            $template = $this->isSistemaQualitativo($turma->getEtapa()) 
                ? 'reports/nota/notaQualitativo.pdf.twig' 
                : 'reports/nota/notaQuantitativo.pdf.twig';
            return $this->render($template, [
                'instituicao' => $disciplina->getTurma()->getUnidadeEnsino(),
                'turma' => $turma,
                'media' => $media,
                'unidadeRegime' => $turma->getEtapa()->getSistemaAvaliacao()->getRegime()->getUnidade(),
                'enturmacoes' => $turma->getEnturmacoes(),
                'conceitos' => $this->isSistemaQualitativo($turma->getEtapa()) 
                    ? $this->getConceitoFacade()->findAll() : [],
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
     * @return array diário de notas
     */
    private function gerarDiario($disciplina, $media, $autoPreenchimento = true) {
        $avaliacoes = $autoPreenchimento ? [] : [];
        $professor = $disciplina->getProfessores()->count() > 0 
                ? $disciplina->getProfessores()->first()->getVinculo()->getFuncionario()->getNome() 
                : '';
        $diario = [
            'disciplina' => $disciplina->getNomeExibicao(),
            'avaliacoes' => $avaliacoes,
            'professor' => $professor
        ];
        if ($this->isSistemaQualitativo($disciplina->getDisciplina()->getEtapa())) {
            $diario['habilidades'] = $this->getHabilidadeFacade()->findAll([
                'disciplina' => $disciplina->getDisciplina()->getId(),
                'media' => $media
            ]);
        }
        return $diario;
    }
    
    private function isSistemaQualitativo($etapa) {
        return $etapa->getSistemaAvaliacao()->isQualitativo();
    }
    
}
