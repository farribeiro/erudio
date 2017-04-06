<?php //

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
use MatriculaBundle\Entity\Enturmacao;

class BoletimReportController extends Controller {
    
    function getEnturmacaoFacade() {
        return $this->get('facade.matricula.enturmacoes');
    }
    
    function getTurmaFacade() {
        return $this->get('facade.curso.turmas');
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Relatórios",
    *   description = "Boletim individual",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/boletim", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function individualAction(Request $request) {
        try {
            $enturmacao = $this->getEnturmacaoFacade()->find($request->query->getInt('enturmacao'));
            $turma = $enturmacao->getTurma();
            $template = $this->isSistemaQualitativo($turma->getEtapa()) 
                ? 'reports/boletim/qualitativo.pdf.twig'
                : 'reports/boletim/quantitativo.pdf.twig';
            return $this->render($template, [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turma' => $turma,
                'boletins' => $this->gerarBoletim($enturmacao),
                'conceitos' => $this->isSistemaQualitativo($turma->getEtapa()) 
                    ? $this->getConceitoFacade()->findAll() : [],
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
    *   description = "Boletins por turma",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/boletins", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function turmaAction(Request $request) {
        try {
            $turma = $this->getTurmaFacade()->find($request->query->getInt('turma'));
            $enturmacoes = $turma->getEnturmacoes();
            $boletins = [];
            foreach ($enturmacoes as $e) {
                $boletins[] = $this->gerarBoletim($e);
            }
            $template = $this->isSistemaQualitativo($turma->getEtapa()) 
                ? 'reports/boletim/qualitativo.pdf.twig' 
                : 'reports/boletim/quantitativo.pdf.twig';
            return $this->render($template, [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turma' => $turma,
                'boletins' => $boletins,
                'conceitos' => $this->isSistemaQualitativo($turma->getEtapa()) 
                    ? $this->getConceitoFacade()->findAll() : [],
            ]);
        } catch (\Exception $ex) {
            $this->get('logger')->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    private function gerarBoletim(Enturmacao $enturmacao) {
        return [
            'matricula' => $enturmacao->getMatricula(), 
            'disciplinas' => $enturmacao->getDisciplinasCursadas()
        ];
    }
    
    private function isSistemaQualitativo($etapa) {
        return $etapa->getSistemaAvaliacao()->isQualitativo();
    }
    
}
