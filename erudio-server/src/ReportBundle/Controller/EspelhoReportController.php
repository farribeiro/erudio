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

class EspelhoReportController extends Controller {
    
    function getEnturmacaoFacade() {
        return $this->get('facade.matricula.enturmacoes');
    }
    
    function getTurmaFacade() {
        return $this->get('facade.curso.turmas');
    }
    
    function getConceitoFacade() {
        return $this->get('facade.avaliacao.conceitos');
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
                'quantidadeMedias' => $turma->getEtapa()->getSistemaAvaliacao()->getQuantidadeMedias(),
                'unidadeRegime' => $turma->getEtapa()->getSistemaAvaliacao()->getRegime()->getUnidade(),
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
    * @Route("/espelhos", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function turmaAction(Request $request) {
        try {
            $turma = $this->getTurmaFacade()->find($request->query->getInt('turma'));
            $enturmacoes = $turma->getEnturmacoes();
            $disciplinas = $enturmacoes[0]->getDisciplinasCursadas();
            $template = $this->isSistemaQualitativo($turma->getEtapa()) 
                ? 'reports/espelho/qualitativo.pdf.twig' 
                : 'reports/espelho/quantitativo.pdf.twig';
            return $this->render($template, [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turma' => $turma,
                'quantidadeMedias' => $turma->getEtapa()->getSistemaAvaliacao()->getQuantidadeMedias(),
                'unidadeRegime' => $turma->getEtapa()->getSistemaAvaliacao()->getRegime()->getUnidade(),
                'enturmacoes' => $enturmacoes,
                'disciplinas' => $disciplinas,
                'conceitos' => $this->isSistemaQualitativo($turma->getEtapa()) 
                    ? $this->getConceitoFacade()->findAll() : [],
            ]);
        } catch (\Exception $ex) {
            $this->get('logger')->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    /**
    * @Route("/espelho-media", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function mediaAction(Request $request) {
        try {
            $media = $request->query->getInt('media', 1);
            $turma = $this->getTurmaFacade()->find($request->query->getInt('turma'));
            $disciplinasOfertadas = $turma->getDisciplinas();
            $enturmacoes = $turma->getEnturmacoes();
            $disciplinasCursadas = $enturmacoes[0]->getDisciplinasCursadas();
            $disciplinas = $this->gerarMediaSimples($enturmacoes, $disciplinasCursadas, $media);
            $template = $this->isSistemaQualitativo($turma->getEtapa()) 
                ? 'reports/espelho/qualitativo.pdf.twig' 
                : 'reports/espelho/quantitativoPorMedia.pdf.twig';
            return $this->render($template, [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turma' => $turma,
                'media' => $media,
                'unidadeRegime' => $turma->getEtapa()->getSistemaAvaliacao()->getRegime()->getUnidade(),
                'nomeRegime' => $turma->getEtapa()->getSistemaAvaliacao()->getRegime()->getnome(),
                'enturmacoes' => $turma->getEnturmacoes(),
                'conceitos' => $this->isSistemaQualitativo($turma->getEtapa()) 
                    ? $this->getConceitoFacade()->findAll() : [],
                'disciplinasOfertadas' => $disciplinasOfertadas,
                'disciplinas' => $disciplinas
            ]);
        } catch (\Exception $ex) {
            $this->get('logger')->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    private function gerarMediaSimples ($enturmacoes, $disciplinas, $media) {
        foreach ($disciplinas as $d => $disciplina) {
            $notasCount = 0;
            $faltasCount = 0;
            foreach ($enturmacoes as $enturmacao) {
                $disciplinaCursada = $enturmacao->getDisciplinasCursadas();
                $alunoMedias = $disciplinaCursada[$d]->getMedias();
                $notasCount += $alunoMedias[$media-1]->getValor();
                $faltasCount += $alunoMedias[$media-1]->getFaltas();
            }
            $disciplina->mediaTotal = $notasCount / count($enturmacoes);
            $disciplina->faltasTotal = $faltasCount / count($enturmacoes);
        }
        return $disciplinas;
    }


    private function isSistemaQualitativo($etapa) {
        return $etapa->getSistemaAvaliacao()->isQualitativo();
    }
    
}
