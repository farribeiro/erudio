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
use CursoBundle\Service\CursoFacade;
use CursoBundle\Service\CursoOfertadoFacade;
use MatriculaBundle\Service\EnturmacaoFacade;

class AlunoReportController extends Controller {
    
    private $cursoFacade;
    private $cursoOfertadoFacade;
    private $enturmacaoFacade;
    private $logger;
    
    function __construct(CursoFacade $cursoFacade, CursoOfertadoFacade $cursoOfertadoFacade, 
            EnturmacaoFacade $enturmacaoFacade, LoggerInterface $logger) {
        $this->cursoFacade = $cursoFacade;
        $this->cursoOfertadoFacade = $cursoOfertadoFacade;
        $this->enturmacaoFacade = $enturmacaoFacade;
        $this->logger = $logger;
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
            $curso = $this->cursoFacade->find($request->query->getInt('curso'));
            $ofertados = $this->cursoOfertadoFacade->findAll(['curso' => $curso->getId()]);
            $relatorios = [];
            foreach ($ofertados as $c) {
                $relatorios[] = $this->gerarRelatorioDefasados($c);
            }
            return $this->render('reports/aluno/defasados-nominal.pdf.twig', [
                'instituicao' => $curso->getInstituicao(),
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
            $cursoOfertado = $this->cursoOfertadoFacade->find($request->query->getInt('curso'));
            $relatorio = $this->gerarRelatorioDefasados($cursoOfertado);
            return $this->render('reports/aluno/defasadosNominal.pdf.twig', [
                'instituicao' => $cursoOfertado->getUnidadeEnsino(),
                'relatorios' => [$relatorio]
            ]);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    /**
     * 
     * @param type $cursoOfertado
     * @return type
     */
    private function gerarRelatorioDefasados($cursoOfertado) {
        $relatorio = ['unidadeEnsino' => $cursoOfertado->getUnidadeEnsino(), 'alunosPorEtapa' => []];
        foreach ($cursoOfertado->getCurso()->getEtapas() as $etapa) {
            $enturmacoes = $this->enturmacaoFacade->getAlunosDefasados($cursoOfertado, $etapa);
            if (count($enturmacoes)) {
                $relatorio['alunosPorEtapa'][$etapa->getNomeExibicao()] = $enturmacoes;
            }
        }
        return $relatorio;
    }
    
}
