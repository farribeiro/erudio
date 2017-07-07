<?php

namespace SME\FilaUnicaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityNotFoundException;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use SME\FilaUnicaBundle\Entity\Inscricao;
use SME\FilaUnicaBundle\Entity\TipoInscricao;
use SME\FilaUnicaBundle\Entity\Status;
use SME\FilaUnicaBundle\Entity\Evento;
use SME\FilaUnicaBundle\Entity\ComponenteRenda;
use SME\FilaUnicaBundle\Report\InscricaoReport;
use SME\FilaUnicaBundle\Report\ComprovanteInscricaoReport;
use SME\FilaUnicaBundle\Report\TermoDesistenciaReport;
use SME\CommonsBundle\Entity\Endereco;
use SME\CommonsBundle\Entity\Cidade;
use SME\CommonsBundle\Entity\Telefone;
use SME\CommonsBundle\Entity\Relacionamento;
use SME\CommonsBundle\Util\DocumentosUtil;
use SME\CommonsBundle\Entity\PessoaFisica;

class InscricaoController extends Controller {
    
    const CPF = 'cpf';
    const CERTIDAO_NASCIMENTO_NOVA = 'certidaoNascimentoNova';
    const CERTIDAO_NASCIMENTO_ANTIGA = 'certidaoNascimentoAntiga';
    
    const ANO_ESCOLAR_INICIAL = 1;
    
    public function formFilaAction() {
        return $this->render('FilaUnicaBundle:Inscricao:formFila.html.twig', array(
            'zoneamentos' => $this->getDoctrine()->getRepository('FilaUnicaBundle:Zoneamento')->findAll(),
            'anosEscolares' => $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->findAll()
        ));
    }
    
    public function exibirFilaAction() {
        $post = $this->getRequest()->request;
        $inscricao = $this->getDoctrine()->getRepository('FilaUnicaBundle:Inscricao')->findOneBy(array('protocolo' => $post->get('protocolo')));
        if($inscricao instanceof Inscricao) {
            $aviso = 'Protocolo encontrado, status: ' . $inscricao->getStatus()->getNome();
            $zoneamento = $inscricao->getZoneamento();
            $anoEscolar = $inscricao->getAnoEscolar();
        } else {
            $aviso = 'Protocolo de busca não informado ou inválido';
            $zoneamento = $this->getDoctrine()->getRepository('FilaUnicaBundle:Zoneamento')->find($post->getInt('zoneamento'));
            $anoEscolar = $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->find($post->getInt('ano'));
        }
        //$fila = $this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO') && !$post->get('filaPublica')
        $fila = $post->get('filaPublica')
                ? $this->get('fila_unica')->gerarFilaReal($zoneamento, $anoEscolar)
                : $this->get('fila_unica')->gerarFilaPublica($zoneamento, $anoEscolar);
        return $this->render('FilaUnicaBundle:Inscricao:fila.html.twig', array(
            'inscricoes' => $fila,
            'protocolo' => $post->get('protocolo'),
            'aviso' => $aviso
        ));
    }        
    
    /** @PreAuthorize("hasRole('ROLE_SUPER_ADMIN')") */
    public function reordenarGeralAction() {
        $this->get('fila_unica')->reordenarFilaGeral();
        return $this->redirect($this->generateUrl('fu_index'));
    }
    
    /** @PreAuthorize("hasRole('ROLE_SUPER_ADMIN')") */
    public function redefinirAnosEscolaresAction() {
        $this->get('fila_unica')->aplicarViradaAnual();
        return $this->redirect($this->generateUrl('fu_index'));
    }
    
    public function formPesquisaAction() {
        $unidades =  $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('u')->from('FilaUnicaBundle:UnidadeEscolar', 'u')
                ->join('u.entidade', 'e')->join('e.pessoaJuridica', 'p')
                ->orderBy('p.nome','ASC')->getQuery()->getResult();
        return $this->render('FilaUnicaBundle:Inscricao:formPesquisa.html.twig', array(
            'unidadesEscolares' => $unidades,
            'zoneamentos' => $this->getDoctrine()->getRepository('FilaUnicaBundle:Zoneamento')->findAll(),
            'anosEscolares' => $this->getDoctrine()->getRepository('FilaUnicaBundle:AnoEscolar')->findAll(),
            'tiposInscricao' => $this->getDoctrine()->getRepository('FilaUnicaBundle:TipoInscricao')->findAll(),
            'status' => $this->getDoctrine()->getRepository('FilaUnicaBundle:Status')->findAll()
        ));
    }
    
    public function pesquisarAction() {
        $post = $this->getRequest()->request;
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
        $restricoes = $qb->expr()->andX();
        $qb = $qb->select('inscricao')
                    ->from('FilaUnicaBundle:Inscricao','inscricao')
                    ->join('inscricao.tipoInscricao','tipoInscricao')
                    ->join('inscricao.status','status')
                    ->join('inscricao.unidadeDestino', 'unidadeDestino')
                    ->join('unidadeDestino.zoneamento','zoneamento')
                    ->join('inscricao.anoEscolar','anoEscolar')
                    ->join('inscricao.crianca','crianca')
                    ->leftJoin('inscricao.unidadeOrigem', 'unidadeOrigem')
                    ->leftJoin('inscricao.vagaOfertada', 'vagaOfertada')
                    ->leftJoin('vagaOfertada.unidadeEscolar', 'unidadeVaga')
                    ->where($restricoes);
        
        if($post->get('protocolo')) {
            $restricoes = $restricoes->add($qb->expr()->eq('inscricao.protocolo', $post->get('protocolo')));
        }
        if($post->get('nome')) {
            $restricoes = $restricoes->add($qb->expr()->like('crianca.nome', ':nome'));
            $qb->setParameter('nome', '%' . $post->get('nome') . '%');
        }
        if($post->getInt('zoneamento')) {
            $restricoes = $restricoes->add($qb->expr()->eq('zoneamento.id', $post->getInt('zoneamento')));
        }
        if($post->getInt('anoEscolar')) {
            $restricoes = $restricoes->add($qb->expr()->eq('anoEscolar.id', $post->getInt('anoEscolar')));    
        }
        if($post->getInt('tipoInscricao')) {
            $restricoes = $restricoes->add($qb->expr()->eq('tipoInscricao.id', $post->getInt('tipoInscricao')));
        }
        if($post->getInt('status')) {
            $restricoes = $restricoes->add($qb->expr()->eq('status.id', $post->getInt('status')));
        }
        if($post->getInt('unidadeOrigem')) {
            $restricoes = $restricoes->add($qb->expr()->eq('unidadeOrigem.id', $post->getInt('unidadeOrigem')));
        }
        if($post->getInt('unidadeVagaOfertada')) {
            $restricoes = $restricoes->add($qb->expr()->eq('unidadeVaga.id', $post->getInt('unidadeVagaOfertada')));
        }
        if(is_numeric($post->get("processoJudicial"))) {
            $restricoes = $restricoes->add($qb->expr()->eq('inscricao.processoJudicial', $post->getInt('processoJudicial')));
        }
        if($post->get('ultimaModificacaoInicio') && $post->get('ultimaModificacaoTermino')) {
            $dataInicio = \DateTime::createFromFormat('d/m/Y', $post->get('ultimaModificacaoInicio'));
            $dataTermino = \DateTime::createFromFormat('d/m/Y', $post->get('ultimaModificacaoTermino'));
            $restricoes = $restricoes->add($qb->expr()->between('inscricao.dataModificacao', ':inicio', ':termino'));
            $qb = $qb->setParameter('inicio', $dataInicio->format('Y-m-d'))
                     ->setParameter('termino', $dataTermino->format('Y-m-d'));  
        }
        $inscricoes = $qb->getQuery()->getResult();
        foreach ($inscricoes as $y => $inscricao) {
            try {
                $nome = $inscricao->getCrianca()->getNome();
            } catch (EntityNotFoundException $ex) {
                unset($inscricoes[$y]);
            }
        }
        return $this->render('FilaUnicaBundle:Inscricao:listaInscricoes.html.twig', array(
            'inscricoes' => $inscricoes
        ));
    }
    
    public function formCadastroAction(TipoInscricao $tipoInscricao) {
        $renderParams = array();
        $renderParams['unidades'] = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('u')->from('FilaUnicaBundle:UnidadeEscolar', 'u')
                ->join('u.entidade', 'e')->join('e.pessoaJuridica', 'p')->where('u.ativo = true')
                ->orderBy('p.nome','ASC')->getQuery()->getResult();
        $renderParams['parentescos'] = $this->getDoctrine()->getRepository('CommonsBundle:Parentesco')->findBy(array(), array('nome' => 'ASC'));
        $renderParams['situacoesFamiliares'] = $this->getDoctrine()->getRepository('FilaUnicaBundle:SituacaoFamiliar')->findBy(array(), array('descricao' => 'ASC'));
        $renderParams['tipoInscricao'] = $tipoInscricao;
        $renderParams['particularidades'] = $this->getDoctrine()->getRepository('CommonsBundle:Particularidade')->findBy(array(), array('nome' => 'ASC'));
        $renderParams['racas'] = $this->getDoctrine()->getRepository('CommonsBundle:Raca')->findAll();
        if($tipoInscricao->getId() == TipoInscricao::TRANSFERENCIA) {
            $renderParams['periodos'] = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('p')->from('CommonsBundle:PeriodoDia', 'p')
                ->where('p.matutino + p.vespertino + p.noturno = :parcial OR p.matutino + p.vespertino + p.noturno = :integral')
                ->setParameter('parcial', 1)
                ->setParameter('integral', 3)
                ->getQuery()->getResult();
        }
        return $this->render('FilaUnicaBundle:Inscricao:formCadastro.html.twig', $renderParams);
    }
    
    public function validarAction() {
        $dados = $this->getRequest()->request;
        $json = new \stdClass();
        try {
            $this->validarDados($dados);
            $json->valida = true;
            return new JsonResponse($json);
        } catch(\Exception $ex) {
            $json->valida = false;
            $json->erro = $ex->getMessage();
            return new JsonResponse($json);
        }
    }
    
    public function cadastrarAction(TipoInscricao $tipoInscricao) {
        $dados = $this->getRequest()->request;
        try {
            $this->validarDados($dados);
            
            $inscricao = new Inscricao();
            $inscricao->setAtivo(true);
            $inscricao->setMovimentacaoInterna(0);
            $inscricao->setDataCadastro(new \DateTime());
            $inscricao->setDataModificacao(new \DateTime());
            $inscricao->setAtendente($this->getUser()->getPessoa());
            $inscricao->setRendaPontuada(0);
            $inscricao->setTipoInscricao($tipoInscricao);
            
            $dataNascimento = \DateTime::createFromFormat('d/m/Y', $dados->get('dataNascimento'));
            switch($dados->get('documentoIdentificacao')) {
                case self::CERTIDAO_NASCIMENTO_NOVA:
                    $crianca = $this->get('cadastro_unico')->createByCertidaoNascimento($dados->get('certidaoNascimento'));
                    $crianca->setCertidaoNascimento($dados->get('certidaoNascimento'));
                    break;
                case self::CERTIDAO_NASCIMENTO_ANTIGA:
                    $crianca = $this->get('cadastro_unico')->createByCertidaoNascimentoSegmentada(
                        $dados->getInt('termoCertidaoNascimento'), 
                        $dados->getInt('livroCertidaoNascimento'), 
                        $dados->getInt('folhaCertidaoNascimento'));
                    $crianca->setCertidaoNascimento(DocumentosUtil::gerarCertidaoNascimento(
                        $dados->getInt('termoCertidaoNascimento'), 
                        $dados->getInt('livroCertidaoNascimento'), 
                        $dados->getInt('folhaCertidaoNascimento'), 
                        '0',
                        $dataNascimento->format('Y')));
                    break;
                case self::CPF:
                    $crianca = $this->get('cadastro_unico')->createByCpf($dados->get('cpf'));
                    $crianca->setCpfCnpj($dados->get('cpf'));
                    break;
            }
            
            //Verificação anti-duplicidade de inscrição e criança
            if($crianca->getId()) {
                $duplicatas = $this->getDoctrine()->getManager()->createQueryBuilder()
                        ->select('i')->from('FilaUnicaBundle:Inscricao', 'i')->join('i.crianca', 'c')
                        ->where('i.ativo = true')->andWhere('c.id = :crianca')->setParameter('crianca', $crianca->getId())
                        ->getQuery()->getResult();
                if(count($duplicatas) > 0) { 
                    $d = $duplicatas[0];
                    throw new \Exception('A criança já possui uma inscrição ativa com o protocolo ' . $d->getProtocolo()); 
                } elseif(!$crianca->getDataNascimento() || $crianca->getDataNascimento()->format('d/m/Y') != $dataNascimento->format('d/m/Y')) {
                    throw new \Exception('Já existe outra criança cadastrada com esta certidão de nascimento'); 
                }
            }
            
            //Cadastro da criança
            $crianca->setNome(trim($dados->get('nome')));
            $crianca->setDataNascimento($dataNascimento);
            $crianca->setEndereco( $crianca->getEndereco() ? $crianca->getEndereco() : new Endereco() );
            $crianca->getEndereco()->setLogradouro($dados->get('endereco'));
            $crianca->getEndereco()->setNumero($dados->getInt('numero'));
            $crianca->getEndereco()->setBairro($dados->get('bairro'));
            $crianca->getEndereco()->setComplemento($dados->get('complemento'));
            $crianca->getEndereco()->setCep($dados->getInt('cep'));
            $crianca->getEndereco()->setCidade($this->getDoctrine()->getRepository('CommonsBundle:Cidade')->find(Cidade::ITAJAI));
            $crianca->setEmail($dados->get('email'));
            $crianca->setNomeMae($dados->get('nomeMae'));
            $crianca->setNomePai($dados->get('nomePai'));
            if($dados->getInt('raca')) {
                $raca = $this->getDoctrine()->getRepository('CommonsBundle:Raca')->find($dados->getInt('raca'));
                $crianca->setRaca($raca);
            }
            foreach($dados->get('telefone') as $tel) {
                if($tel) {
                    $telefone = new Telefone();
                    $telefone->setNumero(\trim($tel));
                    $telefone->setPessoa($crianca);
                    $crianca->getTelefones()->add($telefone); 
                }
            }
            $part = $dados->get('particularidades');
            if (is_array($part)) {
                foreach($dados->get('particularidades') as $p) {
                    if($p) {
                        $particularidade = $this->getDoctrine()->getRepository('CommonsBundle:Particularidade')->find($p);
                        if(!$crianca->getParticularidades()->contains($particularidade)) {
                            $crianca->getParticularidades()->add($particularidade);
                        }
                    }
                }
            }
            
            for ($i = 1; $i <= 2; $i++) {
                if ($dados->get("filiacaoNome$i") && $dados->get("filiacaoCPF$i")) {
                    $existe = false;
                    foreach($crianca->getRelacoes() as $relacao) {
                        if($relacao->getParente()->getCpfCnpj() == $dados->get("filiacaoCPF$i")) {
                            $existe = true;
                        }
                    }
                    $pessoa = $this->get('cadastro_unico')->createByCpf($dados->get("filiacaoCPF$i"));
                    $pessoa->setNome($dados->get("filiacaoNome$i"));
                    $pessoa->setCpfCnpj($dados->get("filiacaoCPF$i"));
                    if($pessoa instanceof PessoaFisica) {
                        $pessoa->setNumeroRg($dados->get("filiacaoRG$i"));
                    }
                    $this->get('cadastro_unico')->retain($pessoa);
                    if(!$existe) {
                        $relacionamento = new Relacionamento();
                        $relacionamento->setPessoa($crianca);
                        $relacionamento->setParente($pessoa);
                        $relacionamento->setResponsavel(true);
                        if($dados->get("filiacaoParentesco$i")) {
                            $relacionamento->setParentesco($this->getDoctrine()->getRepository('CommonsBundle:Parentesco')->find($dados->get("filiacaoParentesco$i")));
                        }
                        $crianca->getRelacoes()->add($relacionamento);
                    }
                }
            }
            
            $this->get('cadastro_unico')->retain($crianca);
            $inscricao->setCrianca($crianca);
            
            $relacoes = $crianca->getRelacoes();
            $criancaRelacoes = array();
            foreach ($relacoes as $relacao) {
                $criancaRelacoes[] = array($relacao->getParente()->getId(),$relacao->getParente()->getNome());
            }
            
            $unidadeDestino = $this->getDoctrine()->getRepository('FilaUnicaBundle:UnidadeEscolar')->find($dados->getInt('unidade'));
            $inscricao->setUnidadeDestino($unidadeDestino);
            $this->get('fila_unica')->definirAnoEscolar($inscricao); 
            $this->definirProtocolo($inscricao);   
            $inscricao->setZoneamento($unidadeDestino->getZoneamento());
            if($tipoInscricao->getId() === TipoInscricao::REGULAR) {
                $situacaoFamiliar = $this->getDoctrine()->getRepository('FilaUnicaBundle:SituacaoFamiliar')->find($dados->getInt('situacaoFamiliar'));
                $inscricao->setSituacaoFamiliar($situacaoFamiliar);
                $rendaTotal = 0.0;
                foreach($dados->get('nomeComponenteRenda') as $i => $nome) {
                    if(\trim($nome)) {
                        $renda = \str_replace(',', '.', $dados->get('rendimentoComponenteRenda')[$i]);
                        $componenteRenda = new ComponenteRenda();
                        $componenteRenda->setInscricao($inscricao);
                        $componenteRenda->setNome($nome);
                        $componenteRenda->setParentesco($dados->get('parentescoComponenteRenda')[$i]);
                        $componenteRenda->setAtividade($dados->get('atividadeComponenteRenda')[$i]);
                        $componenteRenda->setLocalTrabalho($dados->get('localTrabalhoComponenteRenda')[$i]);
                        $componenteRenda->setRendimentoMensal($renda);
                        foreach ($criancaRelacoes as $criancaRelacao) {
                            if ($criancaRelacao[1] == $nome) {
                                $componenteRenda->setResponsavel($criancaRelacao[0]);
                            }
                        }
                        $inscricao->getRendaDetalhada()->add($componenteRenda);
                        $rendaTotal += (float)$renda;
                    }
                }
                $inscricao->setRendaPerCapita($rendaTotal / count($dados->get('nomeComponenteRenda')));
                $inscricao->setStatus($this->getDoctrine()->getRepository('FilaUnicaBundle:Status')->find(Status::EM_RESERVA));
            }
            elseif($tipoInscricao->getId() === TipoInscricao::TRANSFERENCIA) {
                $periodo = $this->getDoctrine()->getRepository('CommonsBundle:PeriodoDia')->find($dados->getInt('periodo'));
                $inscricao->setPeriodoDia($periodo);
                $inscricao->setCodigoAluno($dados->get('codigoAluno'));
                $dataMatricula = \DateTime::createFromFormat('d/m/Y', $dados->get('dataMatricula'));
                $inscricao->setDataMatricula($dataMatricula);
                $inscricao->setStatus($this->getDoctrine()->getRepository('FilaUnicaBundle:Status')->find(Status::EM_ESPERA));
                $inscricao->setRendaPerCapita(0);
                if($dados->getInt('unidadeAlternativa') > 0) {
                    $unidadeAlternativa = $this->getDoctrine()->getRepository('FilaUnicaBundle:UnidadeEscolar')->find($dados->getInt('unidadeAlternativa'));
                    $inscricao->setUnidadeDestinoAlternativa($unidadeAlternativa);  
                } else {
                    $inscricao->setUnidadeDestinoAlternativa($inscricao->getUnidadeDestino());
                }
            }
            
            if($dados->getInt('unidadeAtual') > 0) {
                $unidadeOrigem = $this->getDoctrine()->getRepository('FilaUnicaBundle:UnidadeEscolar')->find($dados->getInt('unidadeAtual'));
                $inscricao->setUnidadeOrigem($unidadeOrigem);  
            }
            
            $this->getDoctrine()->getManager()->persist($inscricao);
            $this->getDoctrine()->getManager()->flush();
            return $this->render('FilaUnicaBundle:Inscricao:confirmacaoCadastro.html.twig', array('inscricao' => $inscricao));
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage() );
            return $this->redirect($this->generateUrl('fu_inscricao_formInscricao', array('tipoInscricao' => $tipoInscricao->getId())));
        }
    }
    
    private function validarDados($dados) {
        switch($dados->get('documentoIdentificacao')) {
            case self::CERTIDAO_NASCIMENTO_NOVA:
                if(!DocumentosUtil::validarCertidaoNascimento($dados->get('certidaoNascimento'))) {
                    throw new \Exception('A certidão de nascimento deve possuir 32 dígitos numéricos');
                }
                break;
            case self::CERTIDAO_NASCIMENTO_ANTIGA:
                if(!DocumentosUtil::validarDadosCertidaoNascimento(
                    $dados->get('termoCertidaoNascimento'),
                    $dados->get('livroCertidaoNascimento'), 
                    $dados->get('folhaCertidaoNascimento')))
                {
                    throw new \Exception('Os dados da certidão de nascimento estão incorretos. Informe apenas os números nos campos');
                }
                break;
            case self::CPF:
                if(!DocumentosUtil::validarCpf($dados->get('cpf'))) {
                    throw new \Exception('O CPF da criança é inválido, informe apenas os 11 dígitos numéricos');
                }
                break;
            default:
                throw new \Exception('Tipo de documento de identificação não informado ou inválido');
        }
        //CPF dos responsáveis
        for ($i = 1; $i <= 2; $i++) {
            if($dados->get("filiacaoCPF$i") && !DocumentosUtil::validarCpf($dados->get("filiacaoCPF$i"))) {
                throw new \Exception('O CPF do responsável ' . $i . ' é inválido, informe apenas os 11 dígitos numéricos');
            }
        }
        //Valores da renda per capita
        if($dados->get('tipoInscricao') == TipoInscricao::REGULAR) {
            foreach($dados->get('nomeComponenteRenda') as $i => $nome) {
                $renda = \str_replace(',', '.', $dados->get('rendimentoComponenteRenda')[$i]);
                if(!\is_numeric($renda)) {
                    throw new \Exception('Os rendimentos mensais devem possuir apenas números, sem separador de milhar');
                }
            }
        } else {
            if($dados->getInt('unidadeAtual') <= 0) {
                throw new \Exception('O campo matrícula atual é obrigatório para transferências');
            }
        }
        if(! \preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dados->get('dataNascimento'))) {
            throw new \Exception('O formato da data de nascimento está incorreto. Utilize o formato 00/00/0000');
        }
    }
    
    private function definirProtocolo(Inscricao $inscricao) {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('MAX(i.protocolo)')
            ->from('FilaUnicaBundle:Inscricao', 'i')
            ->where('i.protocolo LIKE :protocolo')
            ->setParameter('protocolo', $inscricao->getDataCadastro()->format('Y') . $inscricao->getTipoInscricao()->getId() . '%');
        $numero = $qb->getQuery()->getSingleScalarResult();
        if(!$numero) {
            $numero = $inscricao->getDataCadastro()->format('Y') . $inscricao->getTipoInscricao()->getId() . '00000';
        }
        $inscricao->setProtocolo($numero + 1);
    }
    
    public function consultarAction(Inscricao $inscricao) {
        $params = array('inscricao' => $inscricao, 'alteracaoRestrita' => true);
        $admin = $this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO');
        if($inscricao->getTipoInscricao()->getId() === TipoInscricao::TRANSFERENCIA) {
            $params['periodos'] = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('p')->from('CommonsBundle:PeriodoDia', 'p')
                ->where('p.matutino + p.vespertino + p.noturno = :parcial OR p.matutino + p.vespertino + p.noturno = :integral')
                ->setParameter('parcial', 1)
                ->setParameter('integral', 3)
                ->getQuery()->getResult();
        }
        if(($inscricao->getProcessoJudicial() && $admin) || $inscricao->getStatus()->getId() === Status::EM_RESERVA) {
            $params['unidadesEscolares'] = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('u')->from('FilaUnicaBundle:UnidadeEscolar', 'u')
                ->join('u.entidade', 'e')->join('e.pessoaJuridica', 'p')
                ->orderBy('p.nome','ASC')->getQuery()->getResult();
            $params['situacoesFamiliares'] = $this->getDoctrine()->getRepository('FilaUnicaBundle:SituacaoFamiliar')->findAll();
            $params['alteracaoRestrita'] = false;
        }
        return $this->render('FilaUnicaBundle:Inscricao:modalConsultaInscricao.html.twig', $params);
    }
    
    public function alterarAction(Inscricao $inscricao) {
        $dados = $this->getRequest()->request;
        $messages = array();
        try {
            $inscricao->getCrianca()->setNome($dados->get('nome'));
            $dataNascimento = \DateTime::createFromFormat('d/m/Y', $dados->get('dataNascimento'));
            $admin = $this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO');
            if($dados->get('periodoDia')) {
                $periodo = $this->getDoctrine()->getRepository('CommonsBundle:PeriodoDia')->find($dados->get('periodoDia'));
                $inscricao->setPeriodoDia($periodo);
            }
            if($inscricao->getProcessoJudicial() && $admin) {
                $inscricao->setNumeroOrdemJudicial($dados->get('numeroOrdemJudicial'));
                $dataProcesso = \DateTime::createFromFormat('d/m/Y', $this->getRequest()->request->get('dataProcessoJudicial'));
                $inscricao->setDataProcessoJudicial($dataProcesso ? $dataProcesso : null);
            }
            if($inscricao->getStatus()->getId() === Status::EM_RESERVA || ($inscricao->getProcessoJudicial() && $admin)) {
                $inscricao->getCrianca()->setDataNascimento($dataNascimento);
                $this->get('fila_unica')->definirAnoEscolar($inscricao);
                $unidadeDestino = $this->getDoctrine()->getRepository('FilaUnicaBundle:UnidadeEscolar')->find($dados->get('unidadeDestino'));
                $inscricao->setUnidadeDestino($unidadeDestino);
                $inscricao->setZoneamento($unidadeDestino->getZoneamento());
                if($dados->get('situacaoFamiliar')) {
                    $inscricao->setSituacaoFamiliar($this->getDoctrine()->getRepository('FilaUnicaBundle:SituacaoFamiliar')->find($dados->get('situacaoFamiliar')));
                }
            } else {
                $inscricao->getCrianca()->setDataNascimento($dataNascimento);
                $messages[] = 'Aviso: no status atual o ano escolar só poderá ser corrigido por movimentação interna';
            }
            $inscricao->getCrianca()->setNomeMae($dados->get('nomeMae'));
            $inscricao->getCrianca()->setNomePai($dados->get('nomePai'));
            $inscricao->getCrianca()->setEmail($dados->get('email'));
            $inscricao->getCrianca()->getEndereco()->setLogradouro($dados->get('endereco'));
            $inscricao->getCrianca()->getEndereco()->setNumero($dados->getInt('numero'));
            $inscricao->getCrianca()->getEndereco()->setComplemento($dados->get('complemento'));
            $inscricao->getCrianca()->getEndereco()->setBairro($dados->get('bairro'));
            $inscricao->getCrianca()->getEndereco()->setCep($dados->get('cep'));
            $this->getDoctrine()->getManager()->merge($inscricao);
            $this->getDoctrine()->getManager()->flush();
            $messages[] = 'Atualizações salvas';
            $this->get('session')->getFlashBag()->set('message', $messages);
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('fu_inscricao_consultar', array(
            'inscricao' => $inscricao->getId()
        )));
    }
    
    public function cancelarAction(Inscricao $inscricao) {
        $status = $this->getDoctrine()->getRepository('FilaUnicaBundle:Status')->find(Status::DESISTENTE_INSCRICAO);
        $inscricao->setStatus($status);
        $inscricao->setAtivo(false);
        $inscricao->setDataModificacao(new \DateTime());
        $inscricao->setPessoaUltimaModificacao($this->getUser()->getPessoa());
        $this->getDoctrine()->getManager()->merge($inscricao);
        $this->getDoctrine()->getManager()->flush();
        return $this->render('FilaUnicaBundle:Inscricao:confirmacaoCancelamento.html.twig', array('inscricao' => $inscricao));
    }
    
    public function imprimirAction(Inscricao $inscricao) {
        $impressao = new InscricaoReport();
        $impressao->setInscricao($inscricao);
        return $this->get('pdf')->render($impressao);
    }
    
    public function imprimirComprovanteAction(Inscricao $inscricao) {
        $comprovante = new ComprovanteInscricaoReport();
        $comprovante->setInscricao($inscricao);
        return $this->get('pdf')->render($comprovante);
    }
    
    public function imprimirTermoDesistenciaAction(Inscricao $inscricao) {
        $termo = new TermoDesistenciaReport();
        $termo->setInscricao($inscricao);
        return $this->get('pdf')->render($termo);
    }
    
    public function incluirTelefoneAction(Inscricao $inscricao) {
        try {
            $telefone = new Telefone();
            $telefone->setNumero($this->getRequest()->request->get('telefone'));
            $telefone->setPessoa($inscricao->getCrianca());
            $inscricao->getCrianca()->getTelefones()->add($telefone);
            $this->getDoctrine()->getManager()->merge($inscricao->getCrianca());
            $this->getDoctrine()->getManager()->flush();
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('fu_inscricao_consultar', array('inscricao' => $inscricao->getId())));
    }
    
    public function excluirTelefoneAction(Inscricao $inscricao, Telefone $telefone) {
        try {
            $this->getDoctrine()->getManager()->remove($telefone);
            $this->getDoctrine()->getManager()->flush();
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('fu_inscricao_consultar', array('inscricao' => $inscricao->getId())));
    }
    
    public function exibirHistoricoAction(Inscricao $inscricao) {
        return $this->render('FilaUnicaBundle:Inscricao:modalHistoricoInscricao.html.twig', array('inscricao' => $inscricao)); 
    }
    
    public function incluirEventoAction(Inscricao $inscricao) {
        try {
            if(!$inscricao->getAtivo() && !$this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO')) {
                throw new \Exception('Esta inscrição está encerrada e não pode mais sofrer alterações');
            }
            $evento = new Evento();
            $evento->setDataCadastro(new \DateTime());
            $evento->setPessoaCadastrou($this->getUser()->getPessoa());
            $dataOcorrencia = \DateTime::createFromFormat('d/m/Y', $this->getRequest()->request->get('dataOcorrencia'));
            $evento->setDataOcorrencia($dataOcorrencia);
            $evento->setDescricao(\trim($this->getRequest()->request->get('descricao')));
            $evento->setInscricao($inscricao);
            $inscricao->getHistorico()->add($evento);
            $this->getDoctrine()->getManager()->merge($inscricao);
            $this->getDoctrine()->getManager()->flush();
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->forward('FilaUnicaBundle:Inscricao:exibirHistorico', array('inscricao' => $inscricao));
    }
    
    public function excluirEventoAction(Evento $evento) {
        try {
            if((!$evento->getInscricao()->getAtivo() && !$this->get('security.context')->isGranted('ROLE_INFANTIL_MEMBRO')) 
                || $evento->getPessoaCadastrou()->getId() != $this->getUser()->getPessoa()->getId()) 
            {
                throw new \Exception($evento->getInscricao()->getAtivo()
                        ? 'Somente a pessoa que cadastrou o evento pode excluí-lo'
                        : 'Esta inscrição está encerrada e não pode mais sofrer alterações'
                    );
            }
            $this->getDoctrine()->getManager()->remove($evento);
            $this->getDoctrine()->getManager()->flush();
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->forward('FilaUnicaBundle:Inscricao:exibirHistorico', array('inscricao' => $evento->getInscricao()));
    }

    public function formOrdemJudicialAction() {
        return $this->render('FilaUnicaBundle:Inscricao:formOrdemJudicial.html.twig');
    }
    
    public function cadastrarOrdemJudicialAction() {
        try {
            $inscricao = $this->getDoctrine()->getRepository('FilaUnicaBundle:Inscricao')->findOneBy(
                array('protocolo' => $this->getRequest()->request->get('protocolo'), 'ativo' => true)
            );
            if($inscricao == null) {
                throw new \Exception('Não há nenhuma inscrição ativa com o protocolo informado');
            }
            if($inscricao->getStatus()->getId() === Status::EM_RESERVA) {
                $statusEmEspera = $this->getDoctrine()->getRepository('FilaUnicaBundle:Status')->find(Status::EM_ESPERA);
                $inscricao->setStatus($statusEmEspera);
            } 
            elseif($inscricao->getStatus()->getId() !== Status::EM_ESPERA) {
                throw new \Exception('Não é possível entrar com ordem judicial, a inscrição já está em chamada ou foi encerrada');
            }
            $inscricao->setNumeroOrdemJudicial($this->getRequest()->request->get('ordemJudicial'));
            $dataProcesso = \DateTime::createFromFormat('d/m/Y', $this->getRequest()->request->get('dataProcessoJudicial'));
            $inscricao->setDataProcessoJudicial($dataProcesso);
            $inscricao->setProcessoJudicial(true);
            $this->getDoctrine()->getManager()->merge($inscricao);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message','A inscrição de protocolo ' . $inscricao->getProtocolo() . ' foi convertida para uma ordem judicial');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->render('FilaUnicaBundle:Inscricao:formOrdemJudicial.html.twig');
    }
    
}
