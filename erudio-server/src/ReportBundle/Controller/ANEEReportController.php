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
use Psr\Log\LoggerInterface;
use PessoaBundle\Service\UnidadeEnsinoFacade;
use CursoBundle\Service\CursoOfertadoFacade;
use MatriculaBundle\Service\MatriculaFacade;

class ANEEReportController extends Controller {
    
    private $unidadeEnsinoFacade;
    private $cursoOfertadoFacade;
    private $matriculaFacade;
    private $logger;
    
    function __construct(UnidadeEnsinoFacade $unidadeEnsinoFacade, CursoOfertadoFacade $cursoOfertadoFacade, 
            MatriculaFacade $matriculaFacade, LoggerInterface $logger) {
        $this->unidadeEnsinoFacade = $unidadeEnsinoFacade;
        $this->cursoOfertadoFacade = $cursoOfertadoFacade;
        $this->matriculaFacade = $matriculaFacade;
        $this->logger = $logger;
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Relatórios",
    *   description = "Relação nominal de alunos ANEE por instituição",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/alunos/anee-nominal-instituicao", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function nominalPorInstituicaoAction(Request $request) {
        try {
            $unidadesEnsino = $this->unidadeEnsinoFacade->findAll([
                'instituicaoPai' => $request->query->getInt('instituicao')
            ]);
            $relatorios = [];
            foreach ($unidadesEnsino as $unidadeEnsino) {
                $relatorios[] = $this->gerarRelatorio($unidadeEnsino);
            }
            return $this->render('reports/aluno/aneeNominal.pdf.twig', [
                'instituicao' => $unidadesEnsino[0]->getInstituicaoPai(),
                'relatorios' => $relatorios
            ]);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Relatórios",
    *   description = "Relação nominal de alunos ANEE por unidade de ensino",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/alunos/anee-nominal-unidade", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function nominalPorUnidadeEnsinoAction(Request $request) {
        try {
            $unidadeEnsino = $this->unidadeEnsinoFacade->find($request->query->getInt('unidade'));
            $relatorio = $this->gerarRelatorio($unidadeEnsino);
            return $this->render('reports/aluno/aneeNominal.pdf.twig', [
                'instituicao' => $unidadeEnsino,
                'relatorios' => [$relatorio]
            ]);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    private function gerarRelatorio($unidadeEnsino) {
        $cursosOfertados = $this->cursoOfertadoFacade->findAll([
            'unidadeEnsino' => $unidadeEnsino
        ]);
        $alunosPorCurso = [];
        foreach ($cursosOfertados as $curso) {
            $alunosPorCurso[$curso->getCurso()->getNome()] = $this->gerarAlunos($curso);
        }
        return ['unidadeEnsino' => $unidadeEnsino, 'alunosPorCurso' => $alunosPorCurso];
    }
    
    /**
    * 
    * @param type $cursoOfertado
    * @return type
    */
    private function gerarAlunos($cursoOfertado) {
        $matriculas = $this->matriculaFacade->findAll([
            'curso' => $cursoOfertado->getCurso(),
            'unidadeEnsino' => $cursoOfertado->getUnidadeEnsino(),
            'aluno_deficiente' => true
        ]);
        return array_map(function($m) {
            $aluno = $m->getAluno();
            return [
                'codigo' => $m->getCodigo(),
                'nome' => $aluno->getNome(),
                'dataNascimento' => $aluno->getDataNascimento(),
                'genero' => $aluno->getGenero(),
                'enturmacoes' => $m->getEnturmacoesEmAndamento(),
                'telefones' => $aluno->getTelefones(),
                'particularidades' => $aluno->getParticularidades()
            ];
        }, $matriculas);
    }
    
}
