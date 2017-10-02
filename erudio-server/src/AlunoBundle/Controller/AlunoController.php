<?php

namespace AlunoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as FOS;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use MatriculaBundle\Service\MatriculaFacade;
use MatriculaBundle\Service\EnturmacaoFacade;

/**
 * @FOS\NamePrefix("alunos")
 */
class AlunoController extends Controller {
    
    private $matriculaFacade;
    private $enturmacaoFacade;
    
    function __construct(MatriculaFacade $matriculaFacade, EnturmacaoFacade $enturmacaoFacade) {
        $this->matriculaFacade = $matriculaFacade;
        $this->enturmacaoFacade = $enturmacaoFacade;
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Alunos",
    *   description = "Retorna todas as matrículas do aluno autenticado",
    *   statusCodes = {
    *       200 = "Array de matrículas"
    *   }
    * )
    * 
    * 
    * @FOS\Get("matriculas")
    */
    function getMatriculas() {
        $aluno = $this->getUser()->getPessoa();
        $matriculas = $this->matriculaFacade->findAll(['aluno' => $aluno]);
        return new JsonResponse(array_map(function($m) {
            return [
                'id' => $m->getId(),
                'codigo' => $m->getCodigo(),
                'curso' => $m->getCurso()->getNome(),
                'aluno' => $m->getAluno()->getNome(),
                'unidadeEnsino' => $m->getUnidadeEnsino()->getNomeCompleto(),
                'dataCadastro' => $m->getDataCadastro()->format('Y-m-d H:i:s'),
                'status' => $m->getStatus()
            ];
        }, $matriculas));
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Alunos",
    *   description = "Retorna todas as enturmações ativas da matrícula, ou seja, 
    *   as que estão em andamento ou concluídas.",
    *   statusCodes = {
    *       200 = "Array de enturmações"
    *   }
    * )
    * 
    * @FOS\Get("matriculas/{id}/enturmacoes")
    */
    function getEnturmacoes($id) {
        $matricula = $this->matriculaFacade->find($id);
        if ($matricula->getAluno() != $this->getUser()->getPessoa()) {
            return JsonResponse::create(null, JsonResponse::HTTP_FORBIDDEN);
        }
        $enturmacoes = $matricula->getEnturmacoesAtivas()->toArray();
        return new JsonResponse(array_map(function($e) {
            return [
                'id' => $e->getId(),
                'turma' => $e->getTurma()->getNomeExibicao(),
                'unidadeEnsino' => $e->getTurma()->getUnidadeEnsino()->getNomeCompleto(),
                'etapa' => $e->getTurma()->getEtapa()->getNome(),
                'concluido' => $e->getConcluido(),
                'dataCadastro' => $e->getDataCadastro()->format('Y-m-d H:i:s')
            ];
        }, $enturmacoes));
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Alunos",
    *   description = "Retorna todas as disciplinas cursadas pelo aluno vinculadas
    *   a uma enturmação",
    *   statusCodes = {
    *       200 = "Array de disciplinas, cada uma contendo suas médias parciais"
    *   }
    * )
    * 
    * @FOS\Get("enturmacoes/{id}/disciplinas")
    */
    function getDisciplinas($id) {
        $enturmacao = $this->enturmacaoFacade->find($id);
        if ($enturmacao->getAluno() != $this->getUser()->getPessoa()) {
            return JsonResponse::create(null, JsonResponse::HTTP_FORBIDDEN);
        }
        $disciplinas = $enturmacao->getDisciplinasCursadas();
        return new JsonResponse($disciplinas->map(function($d) {
            return [
                'id' => $d->getId(),
                'disciplina' => $d->getNomeExibicao(),
                'sigla' => $d->getSigla(),
                'status' => $d->getStatus(),
                'mediaFinal' => $d->getMediaFinal(),
                'frequencia' => $d->getFrequenciaTotal(),
                'medias' => $d->getMedias()->map(function($m) {
                    return [
                        'id' => $m->getId(),
                        'nome' => $m->getNome(),
                        'numero' => $m->getNumero(),
                        'nota' => $m->getValor(),
                        'faltas' => $m->getFaltas()
                    ];
                })->toArray()
            ];
        }, $disciplinas)->toArray());
    }
    
}
