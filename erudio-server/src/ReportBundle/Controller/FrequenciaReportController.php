<?php

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class FrequenciaReportController extends Controller {
    
    function getAulaFacade() {
        return $this->get('facade.calendario.aulas');
    }
    
    function getDiaFacade() {
        return $this->get('facade.calendario.dias');
    }
    
    function getDisciplinaOfertadaFacade() {
        return $this->get('facade.curso.disciplinas_ofertadas');
    }
    
    function getTurmaFacade() {
        return $this->get('facade.curso.turmas');
    }
    
    /**
    * 
    * @Route("/diarios-frequencia-por-professor", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function diariosPorProfessor(Request $request) {
         try {
            $turma = $this->getTurmaFacade()->find($request->query->getInt('turma'));
            $mes = $request->query->getInt('mes', 1);
            $auto = $request->query->get('auto', true);
            $modelos = [];
            foreach ($turma->getDisciplinas() as $d) {
                foreach ($d->getProfessores() as $p) {
                    if(array_key_exists($p->getId(), $modelos) == false) {
                        $modelos[$p->getId()] = ['professor' => $p, 'disciplinas' => [$d]];
                    } else {
                        $modelos[$p->getId()]['disciplinas'][] = $d;
                    }
                }
            }
            $diarios = [];
            foreach ($modelos as $modelo) {
                $diarios[] = $this->gerarDiarioMultidisciplina(
                        $modelo['disciplinas'], $modelo['professor'], $mes, $auto);
            }
            return $this->render('reports/frequencia/diarios.pdf.twig', [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turma' => $turma,
                'mes' => $this->mesToString($mes),
                'enturmacoes' => $turma->getEnturmacoes(),
                'diarios' => $diarios
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
    *   description = "Diário de frequência de uma disciplina",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/diario-frequencia", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function diarioPorDisciplinaAction(Request $request) {
        try {
            $mes = $request->query->getInt('mes', 1);
            $disciplina = $this->getDisciplinaOfertadaFacade()->find(
                $request->query->getInt('disciplina')
            );
            return $this->render('reports/frequencia/diarios.pdf.twig', [
                'instituicao' => $disciplina->getTurma()->getUnidadeEnsino(),
                'turma' => $disciplina->getTurma(),
                'mes' => $this->mesToString($mes),
                'enturmacoes' => $disciplina->getTurma()->getEnturmacoes(),
                'diarios' => [ $this->gerarDiarioDisciplina($disciplina, $mes) ]
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
    *   description = "Diários de frequência por turma",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/diarios-frequencia", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function diariosPorTurmaAction(Request $request) {
        try {
            $turma = $this->getTurmaFacade()->find($request->query->getInt('turma'));
            $mes = $request->query->getInt('mes', 1);
            $auto = $request->query->get('auto', true);
            $diarios = [];
            foreach ($turma->getDisciplinas() as $disciplina) {
                $diarios[] = $this->gerarDiarioDisciplina($disciplina, $mes, $auto);
            }
            return $this->render('reports/frequencia/diarios.pdf.twig', [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turma' => $turma,
                'mes' => $this->mesToString($mes),
                'enturmacoes' => $turma->getEnturmacoes(),
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
    private function gerarDiarioDisciplina($disciplina, $mes, $autoPreenchimento = true) {
         $aulas = [];
         if ($autoPreenchimento) {
             $aulas = array_map(
                function($a) { return $a->getDia(); },
                $this->getAulaFacade()->findAll(['mes' => $mes, 'disciplina' => $disciplina->getId()])
            );
         }
        $professor = $disciplina->getProfessores()->count() > 0 
                ? $disciplina->getProfessores()->first() : '';
        return [
            'disciplina' => $disciplina->getNomeExibicao(),
            'professor' => $professor->getVinculo()->getFuncionario()->getNome(),
            'aulas' => $aulas
        ];
    }
    
    /**
     * Gera a estrutura de um diário de frequência único para todas as disciplinas
     * informadas.
     * 
     * @param $turma
     * @param $mes
     * @return array diário de frequência
     */
    private function gerarDiarioMultidisciplina($disciplinas, $professor, $mes = 1, $autoPreenchimento = true) {
        $aulas = [];
        if ($autoPreenchimento) {
            $disciplinaIds = array_map(function($d) { return $d->getId(); }, $disciplinas); 
            $aulasDisciplina = $this->getAulaFacade()->findAll(['mes' => $mes, 'disciplinas' => $disciplinaIds]);
            foreach ($aulasDisciplina as $a) {
                if (array_key_exists($a->getDia()->getId(), $aulas) == false) {
                    $aulas[$a->getDia()->getId()] = $a->getDia();
                }
            }
            ksort($aulas);
         }
        return [
            'disciplina' => count($disciplinas) > 1 
                ? 'Multidisciplinar'
                : $disciplinas[0]->getDisciplina()->getNomeExibicao(),
            'professor' => $professor->getVinculo()->getFuncionario()->getNome(),
            'aulas' => $aulas
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
