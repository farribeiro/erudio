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
use CursoBundle\Service\TurmaFacade;
use MatriculaBundle\Service\EnturmacaoFacade;

class EnturmacaoReportController extends Controller {
    
    private $turmaFacade;
    private $enturmacaoFacade;
    private $logger;
      
    function __construct(TurmaFacade $turmaFacade, EnturmacaoFacade $enturmacaoFacade, LoggerInterface $logger) {
        $this->turmaFacade = $turmaFacade;
        $this->enturmacaoFacade = $enturmacaoFacade;
        $this->logger = $logger;
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
            $turma = $this->turmaFacade->find($request->query->get('turma'));
            return $this->render('reports/enturmacao/nominalPorTurma.pdf.twig', [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turmas' => [$turma]
            ]);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500, array('Content-type' => 'text/html'));
        }
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
    * @Route("/enturmacoes/nominal-unidade", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function nominalPorUnidadeAction(Request $request) {
        try {
            $turmas = $this->turmaFacade->findAll(['unidadeEnsino' => $request->query->get('unidade')]);
            return $this->render('reports/enturmacao/nominalPorTurma.pdf.twig', [
                'instituicao' => $turmas[0]->getUnidadeEnsino(),
                'turmas' => $turmas
            ]);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500, array('Content-type' => 'text/html'));
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
            $instituicao = $this->getDoctrine()->getRepository('PessoaBundle:Instituicao')->findOneBy(
                ['id' => $request->query->getInt('instituicao', 0), 'ativo' => true]
            );
            $curso = $request->query->getInt('curso', 0);
            $unidades = $this->getDoctrine()->getRepository('PessoaBundle:UnidadeEnsino')->findBy(
                ['instituicaoPai' => $instituicao, 'ativo' => true], ['nome' => 'ASC']
            );
            $relatorios = [];
            foreach ($unidades as $unidade) {
                $relatorios[] = $this->gerarRelatorio($unidade, $curso);
            }
            return $this->render('reports/enturmacao/quantitativoPorInstituicao.pdf.twig', [
                'instituicao' => $instituicao,
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
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    private function gerarRelatorio($unidadeEnsino, $cursoId = 0) {
        $params = ['unidadeEnsino' => $unidadeEnsino->getId()];
        if ($cursoId) {
            $params['curso'] = $cursoId;
        }
        $turmas = $this->turmaFacade->findAll($params);
        $enturmados = [];
        $totais = ['masculino' => 0, 'feminino' => 0, 'total' => 0];
        foreach ($turmas as $turma) {
            $total = $this->enturmacaoFacade->countByTurma($turma);
            $masc = $this->enturmacaoFacade->countByTurma($turma, 'M');
            $fem = $this->enturmacaoFacade->countByTurma($turma, 'F');
            $enturmados[] = [
                'turma' => $turma,
                'masculino' => $masc,
                'feminino' => $fem,
                'total' => $total
            ];
            $totais['masculino'] += $masc;
            $totais['feminino'] += $fem;
            $totais['total'] += $total;
        }
        return ['unidadeEnsino' => $unidadeEnsino, 'enturmados' => $enturmados, 'totais' => $totais];
    }
    
}
