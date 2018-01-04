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
use Psr\Log\LoggerInterface;
use CursoBundle\Entity\Turma;
use ReportBundle\Util\StringUtil;
use MatriculaBundle\Entity\DisciplinaCursada;
use CursoBundle\Service\TurmaFacade;
use MatriculaBundle\Service\DisciplinaCursadaFacade;
use MatriculaBundle\Service\MediaFacade;
use AvaliacaoBundle\Service\ConceitoFacade;

class EspelhoReportController extends Controller {
    
    private $turmaFacade;
    private $disciplinaCursadaFacade;
    private $mediaFacade;
    private $conceitoFacade;
    private $logger;
    
    function __construct(TurmaFacade $turmaFacade, ConceitoFacade $conceitoFacade, 
            DisciplinaCursadaFacade $disciplinaCursadaFacade, MediaFacade $mediaFacade, LoggerInterface $logger) {
        $this->turmaFacade = $turmaFacade;
        $this->disciplinaCursadaFacade = $disciplinaCursadaFacade;
        $this->conceitoFacade = $conceitoFacade;
        $this->mediaFacade = $mediaFacade;
        $this->logger = $logger;
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Relatórios",
    *   description = "Espelho de notas da turma",
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
            $turma = $this->turmaFacade->find($request->query->getInt('turma'));
            return $this->render('reports/espelho/quantitativo.pdf.twig', [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turma' => $turma,
                'quantidadeMedias' => $turma->getEtapa()->getSistemaAvaliacao()->getQuantidadeMedias(),
                'unidadeRegime' => $turma->getEtapa()->getSistemaAvaliacao()->getRegime()->getUnidade(),
                'folhas' => $this->gerarEspelhoCompleto($turma),
                'conceitos' => $turma->getEtapa()->isSistemaQualitativo() 
                    ? $this->conceitoFacade->findAll() : [],
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
    *   description = "Ata de Resultado Final da Turma",
    *   statusCodes = {
    *       200 = "Documento PDF"
    *   }
    * )
    * 
    * @Route("/ata-resultado-final", defaults={ "_format" = "pdf" })
    * @Pdf(stylesheet = "reports/templates/stylesheet.xml")
    */
    function ataResultadosAction(Request $request) {
        try {
            $turma = $this->turmaFacade->find($request->query->getInt('turma'));
            $modeloAta = $this->gerarAtaResultadoFinal($turma);
            $disciplinas = [];
            foreach ($turma->getEtapa()->getDisciplinas()->toArray() as $d) {
                $disciplinas[$d->getSigla()] = $d;
            }
            return $this->render('reports/ataResultadoFinal/turma.pdf.twig', [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turma' => $turma,
                'resultados' => $modeloAta['resultados'],
                'disciplinas' => $disciplinas
            ]);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
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
            $turma = $this->turmaFacade->find($request->query->getInt('turma'));
            $consolidado = $this->gerarEspelhoConsolidado($turma, $media);
            return $this->render('reports/espelho/quantitativoPorMedia.pdf.twig', [
                'instituicao' => $turma->getUnidadeEnsino(),
                'turma' => $turma,
                'media' => $media,
                'unidadeRegime' => $turma->getEtapa()->getSistemaAvaliacao()->getRegime()->getUnidade(),
                'nomeRegime' => $turma->getEtapa()->getSistemaAvaliacao()->getRegime()->getnome(),
                'disciplinas' => $turma->getDisciplinas(),
                'enturmacoes' => $consolidado['enturmacoes'],
                'mediasTurma' => $consolidado['mediasTurma'],
                'professor' => $consolidado['professor'],
                'conceitos' => $turma->getEtapa()->isSistemaQualitativo() 
                    ? $this->conceitoFacade->findAll() : [],
            ]);
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());
            return new Response($ex->getMessage(), 500);
        }
    }
    
    function gerarEspelhoCompleto(Turma $turma) {
        $disciplinasOfertadas = $turma->getDisciplinas();
        $folhas = [];
        foreach ($disciplinasOfertadas as $disciplinaOfertada) {
            $disciplinasCursadas = $this->disciplinaCursadaFacade->findAll([
                'disciplinaOfertada' => $disciplinaOfertada
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
                    'disciplinaCursada' => $disciplinaCursada,
                    'mediaFinal' => $disciplinaCursada->getMediaPreliminar(),
                    'medias' => $disciplinaCursada->getMedias()
                ];
            }
        }
        return $folhas;
    }
    
    function gerarEspelhoConsolidado(Turma $turma, $numeroMedia) {
        $enturmacoes = [];
        $mediasTurma = [];
        foreach ($turma->getDisciplinas() as $d) {
            $mediasTurma[$d->getDisciplina()->getId()] = ['valor' => 0.0, 'faltas' => 0];
            $professor = $d->getProfessores()->count() > 0 
                ? $d->getProfessores()->first()->getVinculo()->getFuncionario()->getNome() 
                : '';
        }
        foreach($turma->getEnturmacoes() as $enturmacao) {
            $disciplinasCursadas = $this->disciplinaCursadaFacade->findAll([
                'enturmacao' => $enturmacao
            ]);
            $medias = array_map(function($d) use ($numeroMedia, &$mediasTurma) {
                $media = $this->mediaFacade->findOne([
                    'disciplinaCursada' => $d,
                    'numero' => $numeroMedia
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
        return ['enturmacoes' => $enturmacoes, 'mediasTurma' => $mediasTurma, 'professor' => $professor];
    }
    
    function gerarAtaResultadoFinal($turma) {
        $enturmacoes = $turma->getEnturmacoes();
        $resultados = $enturmacoes->map(function($e) use ($turma) {
           $disciplinas = [];
           $disciplinasCursadas = $turma->getEtapa()->getIntegral() 
                ? $e->getDisciplinasCursadas()
                : $this->disciplinaCursadaFacade->findAll([
                    'matricula' => $e->getMatricula(),
                    'etapa' => $turma->getEtapa(),
                    'status' => DisciplinaCursada::STATUS_APROVADO
                ]);
           foreach ($disciplinasCursadas as $d) {
               $disciplinas[$d->getSigla()] = [
                   'nota' => $d->getMediaFinal(),
                   'frequencia' => $d->getFrequenciaTotal()
               ];
           }
           return [
               'matricula' => $e->getMatricula()->getCodigo(),
               'aluno' => $e->getMatricula()->getAluno()->getNome(),
               'dataNascimento' => $e->getMatricula()->getAluno()->getDataNascimento(),
               'disciplinas' => $disciplinas,
               'status' => $e->getStatus()
           ];
        });
        return [
            'resultados' => $resultados->toArray()
        ];
    }
    
}
