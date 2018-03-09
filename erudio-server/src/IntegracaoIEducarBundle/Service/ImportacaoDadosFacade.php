<?php

namespace IntegracaoIEducarBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use MatriculaBundle\Service\MatriculaFacade;
use MatriculaBundle\Service\EtapaCursadaFacade;
use MatriculaBundle\Service\DisciplinaCursadaFacade;
use MatriculaBundle\Service\ObservacaoHistoricoFacade;
use MatriculaBundle\Entity\ObservacaoHistorico;
use MatriculaBundle\Entity\Matricula;
use MatriculaBundle\Entity\EtapaCursada;
use CursoBundle\Service\EtapaFacade;
use CursoBundle\Service\DisciplinaFacade;
use PessoaBundle\Service\CidadeFacade;
use MatriculaBundle\Entity\DisciplinaCursada;
use CursoBundle\Entity\Disciplina;
use MatriculaBundle\Traits\CalculosMedia;
use ReportBundle\Util\StringUtil;
use Psr\Log\LoggerInterface;

class ImportacaoDadosFacade {
      
    use CalculosMedia;
    
    const URL_SERVICO = 'https://intranet.itajai.sc.gov.br/educar_relatorio_historico_escolar_service.php';
    
    private $matriculaFacade;
    private $disciplinaCursadaFacade;
    private $etapaCursadaFacade;
    private $observacaoFacade;
    private $etapaFacade;
    private $disciplinaFacade;
    private $cidadeFacade;
    private $ieducarFacade;
    private $em;
    private $logger;
    
    private $etapas;
    private $disciplinas;
    
    function __construct(MatriculaFacade $matriculaFacade, EtapaCursadaFacade $etapaCursadaFacade, 
            DisciplinaCursadaFacade $disciplinaCursadaFacade, ObservacaoHistoricoFacade $observacaoFacade,
            EtapaFacade $etapaFacade, DisciplinaFacade $disciplinaFacade, CidadeFacade $cidadeFacade, 
            IEducarFacade $ieducarFacade, EntityManagerInterface $em, LoggerInterface $logger) {
        $this->matriculaFacade = $matriculaFacade;
        $this->etapaCursadaFacade = $etapaCursadaFacade;
        $this->disciplinaCursadaFacade = $disciplinaCursadaFacade;
        $this->observacaoFacade = $observacaoFacade;
        $this->etapaFacade = $etapaFacade;
        $this->disciplinaFacade = $disciplinaFacade;
        $this->cidadeFacade = $cidadeFacade;
        $this->ieducarFacade = $ieducarFacade;
        $this->em = $em;
        $this->logger = $logger;
    }
    
    function importarMatriculas($curso, $etapa = null, $unidadeEnsino = null) {
        $this->etapas = $this->etapaFacade->findAll(['curso' => $curso]);
        $this->disciplinas = $this->disciplinaFacade->findAll(['curso' => $curso]);
        $statuses = [Matricula::STATUS_CURSANDO, Matricula::STATUS_TRANCADO, Matricula::STATUS_ABANDONO, Matricula::STATUS_MUDANCA_DE_CURSO];
        foreach ($statuses as $status) {
            $page = 0;
            $matriculas = [];
            $params = [
                'curso' => $curso, 
                'status' => $status
            ];
            if ($etapa) {
                $params['etapa'] = $etapa;
            }
            if ($unidadeEnsino) {
                $params['unidadeEnsino'] = $unidadeEnsino;
            }
            while ($page === 0 || count($matriculas) > 0) {
                $matriculas = $this->matriculaFacade->findAll($params, $page, 50);
                foreach ($matriculas as $matricula) {
                    $this->importarMatricula($matricula);
                    $this->em->detach($matricula);
                }
                $page++;
            }
        }
    }
    
    function importarMatricula(Matricula $matricula) {
        $nome = trim(preg_replace('/\s+/', ' ', StringUtil::removerAcentos($matricula->getAluno()->getNome())));
        $dataNascimento = $matricula->getAluno()->getDataNascimento() 
                ? $matricula->getAluno()->getDataNascimento()->format('d/m/Y') : '';
        $json = $this->ieducarFacade->getAlunos($nome, $dataNascimento, true);
        $alunos = $json ? json_decode($json) : false;
        if($alunos) {
            $matriculasValidas = $this->filtrarMatriculas($matricula, $alunos);
            foreach ($matriculasValidas as $m) {
                $h = json_decode($this->ieducarFacade->getHistorico($m->cod_aluno, 65), 1);
                if ($h['notas']) {
                    $notas = array_map(function($n) use ($h) {
                        $n['aprovado'] = $h['etapas'][$n['ano']]['aprovado'];
                        return $n;
                    }, $h['notas']);
                    $this->importarDisciplinas($matricula, $notas);
                }
                if ($h['etapas']) {
                    $this->importarEtapas($matricula, $h['etapas']);
                }
                if ($h['obs']) {
                    $this->importarObservacoes($matricula, $h['obs']);
                }
            }
        }
    }
    
    function filtrarMatriculas(Matricula $matricula, array $matriculas) {
        $matriculasValidas = array_filter($matriculas, function($m) use ($matricula) {
           $nomeErudio = preg_replace('/\s+/', '', StringUtil::removerAcentos($matricula->getNomeAluno()));
           $nomeIeducar = preg_replace('/\s+/', '', StringUtil::removerAcentos($m->nome));
           return strcasecmp($nomeIeducar, $nomeErudio) == 0;
        });
        usort($matriculasValidas, function($m1, $m2) {
            return $m1->data_nasc ? -1 : 1;
        });
        return $matriculasValidas;
    }
    
    function importarDisciplinas(Matricula $matricula, array $disciplinas) {
        $disciplinasValidas = $this->filtrarDisciplinas($matricula, $disciplinas);
        foreach ($disciplinasValidas as $d) {
            if (!$d['disciplina'] || !$d['ano'] || !$d['serie'] || trim($d['media']) === '') {
                continue;
            }
            $d['disciplina'] = $this->corrigirNomeDisciplina($d['disciplina']);
            $disciplina = array_pop(array_filter($this->disciplinas, function($dis) use ($d) {
                return $dis->getEtapa()->getOrdem() == substr(trim($d['serie']), 0, 1) && $this->compararDisciplinas($dis->getNomeExibicao(), $d['disciplina']);
            }));
            $status = $d['aprovado'] === 'Aprovado' ? DisciplinaCursada::STATUS_APROVADO : EtapaCursada::STATUS_REPROVADO;
            if (!$disciplina && strlen($d['disciplina']) <= 40) {
                $disciplina = $this->criarNovaDisciplina($d['disciplina'], $d['serie']);
            }
            if ($disciplina) {
                try {
                    $disciplinaCursada = new DisciplinaCursada($matricula, $disciplina, $status, $d['ano'], true);
                    $disciplinaCursada->setMediaFinal($d['media']);
                    try {
                        $disciplinaCursada->setFrequenciaTotal($disciplina->getEtapa()->getOrdem() <= 5 ? 100.0 : $this->calcularFrequenciaPorAula($disciplinaCursada, (int)$d['falta']));
                    } catch (\Exception $ex) {
                        $disciplinaCursada->setFrequenciaTotal(100.0);
                    }
                    $this->disciplinaCursadaFacade->create($disciplinaCursada, true, true);
                    $this->em->detach($disciplinaCursada);
                } catch (\Exception $ex) {
                    //ignorar duplicidades
                    $this->logger->error($ex->getMessage());
                }
            }
        }
    }
    
    function corrigirNomeDisciplina($nomeDisciplina) {
        $disciplina = trim($nomeDisciplina);
        if ($disciplina === 'Artes') { return 'Arte'; }
        if ($disciplina === 'Matemátca') { return 'Matemática'; }
        if ($disciplina === 'Matematic') { return 'Matemática'; }
        if ($disciplina === 'Matermatica') { return 'Matemática'; }
        if ($disciplina === 'EMSINO RELIGIOSO') { return 'Ensino Religioso'; }
        if ($disciplina === 'E. Religioso') { return 'Ensino Religioso'; }
        if ($disciplina === 'Ed. Física') { return 'Educação Física'; }
        if ($disciplina === 'Educaçõa e Cidadania') { return 'Educação e Cidadania'; }
        if ($disciplina === 'L. Portuguesa') { return 'Língua Portuguesa'; }
        if ($disciplina === 'L´´ingua Portuguesa') { return 'Língua Portuguesa'; }
        if ($disciplina === 'Língua Estrangeira- Inglês') { return 'Língua Estrangeira - Inglês'; }
        if ($disciplina === 'Língua Estrangeira (Inglês)') { return 'Língua Estrangeira - Inglês'; }
        if ($disciplina === 'Língua Estrangeira ( Inglês )') { return 'Língua Estrangeira - Inglês'; }
        return $disciplina;
    }
    
    function criarNovaDisciplina($nomeDisciplina, $nomeEtapa) {
        $etapa = array_pop(array_filter($this->etapas, function($et) use ($nomeEtapa) {
            return $et->getOrdem() == substr(trim($nomeEtapa), 0, 1);
        }));
        if (!$etapa) {
            return null;
        }
        try {
            $novaDisciplina = new Disciplina(trim($nomeDisciplina), trim($nomeDisciplina), $etapa);
            $disciplina = $this->disciplinaFacade->create($novaDisciplina, true, true);
            $this->disciplinas[] = $disciplina;
        } catch (\Exception $ex) {
            $this->logger->error('Disciplina ' . $nomeDisciplina . ' não pode ser criada: ' . $ex->getMessage());
        }
    }
    
    function compararDisciplinas($dErudio, $dIeducar) {
        $dIeducarFormatada = StringUtil::removerAcentos(
            trim(preg_replace('/\s+/', '', $dIeducar))
        );
        $dErudioFormatada = StringUtil::removerAcentos(
            trim(preg_replace('/\s+/', '', $dErudio))
        );
        return mb_strtoupper($dIeducarFormatada) === mb_strtoupper($dErudioFormatada);
    }
    
    function filtrarDisciplinas(Matricula $matricula, array $disciplinas) {
        $anoNascimento = $matricula->getAluno()->getDataNascimento() ? $matricula->getAluno()->getDataNascimento()->format('Y') : 2001;
        $disciplinasValidas = array_filter($disciplinas, function($d) use ($anoNascimento) {
            return $d['ano'] >= $anoNascimento;
        });
        $disciplinasJson = array_map('json_encode', $disciplinasValidas);
        $disciplinasUnicas = array_unique($disciplinasJson);
        return array_map(function($i) { return json_decode($i, 1); }, $disciplinasUnicas);
    }
    
    function importarEtapas(Matricula $matricula, array $etapas) {
        $etapasValidas = $this->filtrarEtapas($matricula, $etapas);
        foreach ($etapasValidas as $e) {
            $cidade = $this->cidadeFacade->findOne(['nome' => trim(preg_replace('/\s+/', ' ', $e['escola_cidade'])), 'estado_sigla' => $e['escola_uf']]);
            $etapa = array_pop(array_filter($this->etapas, function($et) use ($e) {
                return $et->getOrdem() == substr(trim($e['serie']), 0, 1);
            }));
            $status = $e['aprovado'] === 'Aprovado' ? EtapaCursada::STATUS_APROVADO : EtapaCursada::STATUS_REPROVADO;
            if ($etapa && $cidade) {
                try {
                    $etapaCursada = new EtapaCursada($matricula, $etapa, $e['ano'], $e['escola'], $cidade, $status, null, true);
                    $this->etapaCursadaFacade->create($etapaCursada, true, true);
                    $this->em->detach($etapaCursada);
                } catch (\Exception $ex) {
                    //ignorar duplicidades
                    $this->logger->error($ex->getMessage());
                }
            }
        }
    }
    
    function filtrarEtapas(Matricula $matricula, array $etapas) {
        $anoNascimento = $matricula->getAluno()->getDataNascimento() ? $matricula->getAluno()->getDataNascimento()->format('Y') : 2001;
        $etapasValidas = array_filter($etapas, function($e) use ($anoNascimento) {
            return $e['ano'] >= $anoNascimento;
        });
        $etapasJson = array_map('json_encode', $etapasValidas);
        $etapasUnicas = array_unique($etapasJson);
        return array_map(function($i) { return json_decode($i, 1); }, $etapasUnicas);
    }
    
    function importarObservacoes(Matricula $matricula, array $observacoes) {
        foreach ($observacoes as $ano => $obs) {
            if ($obs && strlen($obs) > 10) {
                try {
                    $observacao = ObservacaoHistorico::criar($matricula, $ano . ' - ' . $obs);
                    $this->observacaoFacade->create($observacao, true, true);
                    $this->em->detach($observacao);
                } catch (\Exception $ex) {
                    //ignorar duplicidades
                    $this->logger->error($ex->getMessage());
                }
            }
        }
    }
    
}
