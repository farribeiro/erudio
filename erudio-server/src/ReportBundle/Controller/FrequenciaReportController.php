<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    @author Municipio de Itajaí - Secretaria de Educação - DITEC         *
 *    @updated 30/06/2016                                                  *
 *    Pacote: Erudio                                                       *
 *                                                                         *
 *    Copyright (C) 2016 Prefeitura de Itajaí - Secretaria de Educação     *
 *                       DITEC - Diretoria de Tecnologias educacionais     *
 *                        ditec@itajai.sc.gov.br                           *
 *                                                                         *
 *    Este  programa  é  software livre, você pode redistribuí-lo e/ou     *
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme     *
 *    publicada pela Free  Software  Foundation,  tanto  a versão 2 da     *
 *    Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.    *
 *                                                                         *
 *    Este programa  é distribuído na expectativa de ser útil, mas SEM     *
 *    QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-     *
 *    ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-     *
 *    sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.     *
 *                                                                         *
 *    Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU     *
 *    junto  com  este  programa. Se não, escreva para a Free Software     *
 *    Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
 *    02111-1307, USA.                                                     *
 *                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ReportBundle\Util\DateTimeUtil;
use CursoBundle\Service\TurmaFacade;
use CursoBundle\Service\DisciplinaOfertadaFacade;
use CalendarioBundle\Service\DiaFacade;
use CalendarioBundle\Service\AulaFacade;

class FrequenciaReportController extends Controller {
    
    private $turmaFacade;
    private $disciplinaOfertadaFacade;
    private $diaFacade;
    private $aulaFacade;
    
    function __construct(TurmaFacade $turmaFacade, DisciplinaOfertadaFacade $disciplinaOfertadaFacade, 
            DiaFacade $diaFacade, AulaFacade $aulaFacade) {
        $this->turmaFacade = $turmaFacade;
        $this->disciplinaOfertadaFacade = $disciplinaOfertadaFacade;
        $this->diaFacade = $diaFacade;
        $this->aulaFacade = $aulaFacade;
    }
    
    function getTurmaFacade() {
        return $this->turmaFacade;
    }

    function getDisciplinaOfertadaFacade() {
        return $this->disciplinaOfertadaFacade;
    }

    function getDiaFacade() {
        return $this->diaFacade;
    }

    function getAulaFacade() {
        return $this->aulaFacade;
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
                'mes' => DateTimeUtil::mesPorExtenso($mes),
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
            $diarios = $this->gerarDiariosTurma($turma, $mes, $auto);
            return $this->render('reports/frequencia/diarios.pdf.twig', [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turma' => $turma,
                'mes' => DateTimeUtil::mesPorExtenso($mes),
                'enturmacoes' => $turma->getEnturmacoes(),
                'diarios' => $diarios
            ]);
        } catch (\Exception $ex) {
            $this->get('logger')->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    /**
     * Gera as estruturas de todos os diários da turma, que podem variar de acordo
     * com curso, etapa, e outras variáveis.
     * 
     * @param $turma
     * @param $mes mês do diário
     * @param $auto indica se o relatório usará as aulas cadastradas para montar o diário
     * @return type
     */
    private function gerarDiariosTurma($turma, $mes, $auto = true) {
        $diarios = [];
        if ($turma->getEtapa()->getFrequenciaUnificada()) {
            $this->get('logger')->info('>> BINGO');
            $modelos = $this->gerarModelosDiariosPorProfessor($turma);
            foreach ($modelos as $modelo) {
                $diarios[] = $this->gerarDiarioMultidisciplina(
                        $modelo['disciplinas'], $modelo['professor'], $mes, $auto);
            }
        } else {
            foreach ($turma->getDisciplinas() as $disciplina) {
                $diarios[] = $this->gerarDiarioDisciplina($disciplina, $mes, $auto);
            }
        }
        return $diarios;
    }
    
    /**
     * Gera um array, onde as chaves são os ids dos professores da turma, e os valores
     * são arrays contendo o professor e as disciplinas que este ministra.
     * 
     * @param $turma
     * @return array
     */
    private function gerarModelosDiariosPorProfessor($turma) {
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
        return $modelos;
    }
    
    /**
     * Gera a estrutura de um diário de frequência, para determinada disciplina ofertada.
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
                ? $disciplina->getProfessores()->first()->getVinculo()->getFuncionario()->getNome() 
                : '';
        return [
            'disciplina' => $disciplina->getNomeExibicao(),
            'professor' => $professor,
            'aulas' => $aulas
        ];
    }
    
    /**
     * Gera a estrutura de um diário de frequência único para um grupo de disciplinas.
     * Utilizado principalmente nos primeiros anos do Ensino Fundamental, onde um professor
     * ministra várias disciplinas.
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
        $nomeProfessor = $professor ? $professor->getVinculo()->getFuncionario()->getNome() : '';
        return [
            'disciplina' => count($disciplinas) > 1 
                ? 'Multidisciplinar'
                : $disciplinas[0]->getDisciplina()->getNomeExibicao(),
            'professor' => $nomeProfessor,
            'aulas' => $aulas
        ];
    }
    
}
