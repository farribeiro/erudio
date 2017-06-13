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
use CursoBundle\Entity\Turma;
use ReportBundle\Util\StringUtil;
use MatriculaBundle\Entity\DisciplinaCursada;
use CursoBundle\Service\TurmaFacade;
use MatriculaBundle\Service\EnturmacaoFacade;
use AvaliacaoBundle\Service\ConceitoFacade;

class EspelhoReportController extends Controller {
    
    private $turmaFacade;
    private $enturmacaoFacade;
    private $conceitoFacade;
    
    function __construct(TurmaFacade $turmaFacade, EnturmacaoFacade $enturmacaoFacade, 
            ConceitoFacade $conceitoFacade) {
        $this->turmaFacade = $turmaFacade;
        $this->enturmacaoFacade = $enturmacaoFacade;
        $this->conceitoFacade = $conceitoFacade;
    }
    
    function getTurmaFacade() {
        return $this->turmaFacade;
    }

    function getEnturmacaoFacade() {
        return $this->enturmacaoFacade;
    }

    function getConceitoFacade() {
        return $this->conceitoFacade;
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
            $template = $turma->getEtapa()->isSistemaQualitativo()
                ? 'reports/espelho/qualitativo.pdf.twig' 
                : 'reports/espelho/quantitativo.pdf.twig';
            return $this->render($template, [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turma' => $turma,
                'quantidadeMedias' => $turma->getEtapa()->getSistemaAvaliacao()->getQuantidadeMedias(),
                'unidadeRegime' => $turma->getEtapa()->getSistemaAvaliacao()->getRegime()->getUnidade(),
                'folhas' => $this->gerarEspelhoCompleto($turma),
                'conceitos' => $turma->getEtapa()->isSistemaQualitativo() 
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
            $template = $turma->getEtapa()->isSistemaQualitativo()
                ? 'reports/espelho/qualitativo.pdf.twig' 
                : 'reports/espelho/quantitativoPorMedia.pdf.twig';
            $consolidado = $this->gerarEspelhoConsolidado($turma, $media);
            return $this->render($template, [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turma' => $turma,
                'media' => $media,
                'unidadeRegime' => $turma->getEtapa()->getSistemaAvaliacao()->getRegime()->getUnidade(),
                'nomeRegime' => $turma->getEtapa()->getSistemaAvaliacao()->getRegime()->getnome(),
                'disciplinas' => $turma->getDisciplinas(),
                'enturmacoes' => $consolidado['enturmacoes'],
                'mediasTurma' => $consolidado['mediasTurma'],
                'conceitos' => $turma->getEtapa()->isSistemaQualitativo() 
                    ? $this->getConceitoFacade()->findAll() : [],
            ]);
        } catch (\Exception $ex) {
            $this->get('logger')->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    private function gerarEspelhoCompleto(Turma $turma) {
        $disciplinasOfertadas = $turma->getDisciplinas();
        $folhas = [];
        foreach ($disciplinasOfertadas as $disciplinaOfertada) {
            $disciplinasCursadas = $this->get('facade.matricula.disciplinas_cursadas')->findAll([
                'disciplinaOfertada' => $disciplinaOfertada,
                'status' => DisciplinaCursada::STATUS_CURSANDO
            ]);
            usort($disciplinasCursadas, function($a, $b) {
                return strcasecmp(
                    StringUtil::removerAcentos($a->getMatricula()->getAluno()->getNome()), 
                    StringUtil::removerAcentos($b->getMatricula()->getAluno()->getNome())
                );
            });
            foreach ($disciplinasCursadas as $disciplinaCursada) {
                $folhas[$disciplinaOfertada->getNomeExibicao()][] = [
                    'nomeAluno' => $disciplinaCursada->getMatricula()->getAluno()->getNome(),
                    'medias' => $disciplinaCursada->getMedias()
                ];
            }
        }
        return $folhas;
    }
    
    private function gerarEspelhoConsolidado(Turma $turma, $media) {
        $enturmacoes = [];
        $mediasTurma = [];
        foreach ($turma->getDisciplinas() as $d) {
            $mediasTurma[$d->getDisciplina()->getId()] = ['valor' => 0.0, 'faltas' => 0];
        }
        foreach($turma->getEnturmacoes() as $enturmacao) {
            $disciplinasCursadas = $this->get('facade.matricula.disciplinas_cursadas')->findAll([
                'enturmacao' => $enturmacao,
                'status' => DisciplinaCursada::STATUS_CURSANDO
            ]);
            $medias = array_map(function($d) use ($media, &$mediasTurma) {
                $media = $this->get('facade.matricula.medias')->findOne([
                    'disciplinaCursada' => $d,
                    'numero' => $media
                ]);
                $mediasTurma[$d->getDisciplina()->getId()]['valor'] += $media->getValor();
                $mediasTurma[$d->getDisciplina()->getId()]['faltas'] += $media->getFaltas();
                return $media;
            }, $disciplinasCursadas);
            $enturmacoes[] = [
                'matricula' => $enturmacao->getMatricula(),
                'medias' => $medias
            ];
        }
        foreach ($turma->getDisciplinas() as $d) {
            $mediasTurma[$d->getDisciplina()->getId()]['valor'] /= count($enturmacoes);
            $mediasTurma[$d->getDisciplina()->getId()]['faltas'] /= count($enturmacoes);
        }
        return ['enturmacoes' => $enturmacoes, 'mediasTurma' => $mediasTurma];
    }
}