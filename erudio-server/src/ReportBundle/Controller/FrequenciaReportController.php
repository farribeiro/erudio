<?php

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;

class FrequenciaReportController extends Controller {
    
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
    * @Route("/diario-frequencia", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function diarioAction(Request $request) {
        try {
            $mes = $request->query->getInt('mes', 1);
            $disciplina = $this->getDisciplinaOfertadaFacade()->find(
                $request->query->getInt('disciplina')
            );
            return $this->render('reports/frequencia/diarios.pdf.twig', [
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
    * @Route("/diarios-frequencia", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function diariosAction(Request $request) {
        try {
            $auto = $request->query->get('auto', true);
            $mes = $request->query->getInt('mes', 1);
            $turma = $this->getTurmaFacade()->find($request->query->getInt('turma'));
            $diarios = [];
            foreach ($turma->getDisciplinas() as $disciplina) {
                $diarios[] = $this->gerarDiario($disciplina, $mes, $auto);
            }
            return $this->render('reports/frequencia/diarios.pdf.twig', [
                'instituicao' => $disciplina->getTurma()->getUnidadeEnsino(),
                'mes' => $this->mesToString($mes),
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
    private function gerarDiario($disciplina, $mes, $autoPreenchimento = true) {
         $aulas = $autoPreenchimento 
            ? $this->getAulaFacade()->findAll(['mes' => $mes, 'disciplina' => $disciplina->getId()]) 
            : [];
        $enturmacoes = $disciplina->getTurma()->getEnturmacoes()->toArray();
        usort($enturmacoes, function($e1, $e2) {
            return strcasecmp($e1->getMatricula()->getAluno()->getNome(), 
                    $e2->getMatricula()->getAluno()->getNome()); 
        });
        return [
            'disciplina' => $disciplina,
            'aulas' => $aulas,
            'enturmacoes' => $enturmacoes
        ];
    }
    
    function mesToString($mes) {
        switch($mes) {
            case 1: return 'Janeiro';
            case 2: return 'Fevereiro';
            case 3: return 'Março';
            case 4: return 'Abril';
            case 5: return 'Maio';
            case 6: return 'Junho';
            case 7: return 'Julho';
            case 8: return 'Agosto';
            case 9: return 'Setembro';
            case 10: return 'Outubro';
            case 11: return 'Novembro';
            case 12: return 'Dezembro';
        }
    }
    
}
