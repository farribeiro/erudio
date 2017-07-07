<?php

namespace SME\ProtocoloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use SME\ProtocoloBundle\Reports\RequerimentoReportFactory;
use SME\ProtocoloBundle\Entity\Protocolo;
use SME\ProtocoloBundle\Entity\Categoria;
use SME\ProtocoloBundle\Entity\Encaminhamento;

class ProtocoloController extends Controller {
    
    const ARQUIVADO = 0;
    const ATIVO = 1;
    const NOVO = 2;
    const EM_ATIVIDADE = 3;
    
    public function formPesquisaAction(Categoria $categoria) {
        $renderParams = array(
            'categoria' => $categoria,
            'solicitacoes' => $this->getDoctrine()->getRepository('ProtocoloBundle:Solicitacao')->findBy(
                array('categoria' => $categoria), array('nome' => 'ASC')
            )
        );
        return $this->render('ProtocoloBundle:Protocolo:formPesquisaProtocolo.html.twig', $renderParams);
    }
    
    /**
        * Action de pesquisa de protocolos. Os protocolos que estejam sob responsabilidade de alguém
        * só serão retornados se tal responsável for o usuário atual ou se o usuário for gerente.
        */
    public function pesquisarAction(Categoria $categoria) {
        //Constrói a base da query de busca
        $query = $this->getDoctrine()->getManager()->createQueryBuilder()
                    ->select('p')
                    ->from('ProtocoloBundle:Protocolo','p')
                    ->join('p.requerente', 'r')->join('p.solicitacao', 's')->join('s.categoria', 'c')
                    ->leftJoin('p.responsavelAtual', 'ra')
                    ->where('c.id = :categoria')->setParameter('categoria', $categoria->getId())
                    ->andWhere('p.ativo = :ativo');
        //Define o modo de busca e restringe os resultados baseando-se na role do usuário
        switch($this->getRequest()->request->get('estado')) {
            case self::ATIVO:
                $query = $this->get('security.context')->isGranted('ROLE_PROTOCOLO_GERENTE')
                    ? $query->setParameter('ativo', true)
                    : $query->setParameter('ativo', true)
                            ->andWhere('ra.id = :responsavel OR ra IS NULL')
                            ->setParameter('responsavel', $this->getUser()->getPessoa()->getId());
                break;
            case self::EM_ATIVIDADE:
                $query = $this->get('security.context')->isGranted('ROLE_PROTOCOLO_GERENTE')
                    ? $query->setParameter('ativo', true)->andWhere('ra IS NOT NULL')
                    : $query->setParameter('ativo', true)
                            ->andWhere('ra.id = :responsavel')
                            ->setParameter('responsavel', $this->getUser()->getPessoa()->getId());
                break;
            case self::NOVO:
                $query = $query->setParameter('ativo', true)->andWhere('ra IS NULL');
                break;
            case self::ARQUIVADO:
            default:
                $query = $query->setParameter('ativo', false);
                break;
        }
        //Define os filtros utilizados na busca
        if($this->getRequest()->request->get('requerente')) {
            $query = $query->andWhere('r.nome LIKE :requerente')
                           ->setParameter('requerente', '%' . $this->getRequest()->request->get('requerente') . '%');
        }
        if($this->getRequest()->request->get('numero')) {
            $query = $query->andWhere('p.id = :numero')
                           ->setParameter('numero', $this->getRequest()->request->get('numero'));
        }
        if($this->getRequest()->request->get('solicitacao')) {
            $query = $query->andWhere('s.id LIKE :solicitacao')
                           ->setParameter('solicitacao', $this->getRequest()->request->get('solicitacao'));
        }
        $renderParams = array(
            'protocolos' => $query->orderBy('p.dataCadastro', 'DESC')->getQuery()->getResult()
        );
        return $this->render('ProtocoloBundle:Protocolo:listaProtocolos.html.twig', $renderParams);
    }
    
    public function consultarRequerenteAction(Protocolo $protocolo) {
        return $this->render('ProtocoloBundle:Protocolo:modalConsultaRequerente.html.twig', array('requerente' => $protocolo->getRequerente()));
    }
    
    public function visualizarAction(Protocolo $protocolo) {
        return $this->render('ProtocoloBundle:Protocolo:modalConsultaProtocolo.html.twig', array('protocolo' => $protocolo));
    }
    
    /**
     * Action que permite tomar posse de um protocolo caso o mesmo ainda não possua responsável.
     */
    public function receberAction(Protocolo $protocolo) {
        try {
            if($protocolo->getResponsavelAtual()) {
                throw new \Exception('Este protocolo já foi recebido por outra pessoa');
            }
            $protocolo->setResponsavelAtual($this->getUser()->getPessoa());
            $protocolo->setDataModificacao(new \DateTime());
            $this->getDoctrine()->getManager()->merge($protocolo);
            $this->getDoctrine()->getManager()->flush();
            if ($protocolo->getRequerente()->getUsuario()) {
                $this->get('notification')->send(
                    'Seu requerimento de nº ' . $protocolo->getProtocolo() . ' foi recebido por ' . $protocolo->getResponsavelAtual()->getNome(), 
                    $protocolo->getRequerente()->getUsuario(), 'info'
                );
            }
            $this->get('session')->getFlashBag()->set('message','Protocolo ' . $protocolo->getProtocolo() . ' recebido');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error','Erro: ' . $ex->getMessage());
        }
        return $this->forward('ProtocoloBundle:Protocolo:pesquisar', array('categoria' => $protocolo->getSolicitacao()->getCategoria()));
    }

    /**
        * Action exclusiva do gerente, permite-o tornar-se o responsável por um protocolo. Caso exista
        * um responsável atual, a transição da posse será registrada como um encaminhamento concluído.
        * @PreAuthorize("hasRole('ROLE_PROTOCOLO_GERENTE')")
        */
    public function tomarPosseAction(Protocolo $protocolo) {
        try {
            $encaminhamento = new Encaminhamento();
            $encaminhamento->setProtocolo($protocolo);
            $encaminhamento->setPessoaEncaminha($protocolo->getResponsavelAtual());
            $encaminhamento->setPessoaRecebe($this->getUser()->getPessoa());
            $encaminhamento->setDataCadastro(new \DateTime());
            $encaminhamento->setObservacao('Transferência de posse executada pelo(a) gerente ' . $this->getUser()->getPessoa()->getNome());
            $encaminhamento->setRecebido(true);
            $encaminhamento->setDataRecebimento(new \DateTime());
            $protocolo->getEncaminhamentos()->add($encaminhamento);
            $protocolo->setResponsavelAtual($this->getUser()->getPessoa());
            $this->getDoctrine()->getManager()->merge($protocolo);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message','O protocolo ' . $protocolo->getProtocolo() . ' foi transferido para você');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error','Erro: ' . $ex->getMessage());
        }
        return $this->forward('ProtocoloBundle:Protocolo:pesquisar', array('categoria' => $protocolo->getSolicitacao()->getCategoria()));
    }
    
    /**
     * @PreAuthorize("#protocolo.getResponsavelAtual().getId() == user.getPessoa().getId()")
     */
    public function formAtualizacaoAction(Protocolo $protocolo) {
        $renderParams = array(
            'protocolo' => $protocolo
        );
        $renderParams['situacoes'] = $this->getDoctrine()->getRepository('ProtocoloBundle:Situacao')->findBy(
            array('categoriaAplicavel' => $renderParams['protocolo']->getSolicitacao()->getCategoria()), array('nome' => 'ASC')
        );
        return $this->render('ProtocoloBundle:Protocolo:modalFormAtualizacao.html.twig', $renderParams);
    }
    
    /**
     * @PreAuthorize("#protocolo.getResponsavelAtual().getId() == user.getPessoa().getId()")
     */
    public function atualizarAction(Protocolo $protocolo) {
        $situacao = $this->getDoctrine()->getRepository('ProtocoloBundle:Situacao')->find($this->getRequest()->request->get('situacao'));
        try {
            if($protocolo->getSolicitacao()->getCategoria()->getId() != $situacao->getCategoriaAplicavel()->getId()) {
                throw new \Exception('A situação informada não é aplicável a este tipo de solicitação');
            }
            $protocolo->setSituacao($situacao);
            $protocolo->setDataModificacao(new \DateTime());
            $protocolo->setObservacao(\trim($this->getRequest()->request->get('observacao')));
            if($this->getRequest()->request->get('encerrar')) {
                if($protocolo->getSituacao()->getTerminal()) {
                    $protocolo->setAtivo(false);
                    $protocolo->setDataEncerramento(new \DateTime());
                    if ($protocolo->getRequerente()->getUsuario()) {
                        $this->get('notification')->send(
                            'Seu requerimento de nº ' . $protocolo->getProtocolo() . ' foi encerrado com a situação ' . $protocolo->getSituacao()->getNome(), 
                            $protocolo->getRequerente()->getUsuario(), 'info'
                        );
                    }
                } else {
                    throw new \Exception('O protocolo não pode ser encerrado em sua situação atual');
                }
            } else if ($protocolo->getRequerente()->getUsuario()) {
                $this->get('notification')->send(
                    'Seu requerimento de nº ' . $protocolo->getProtocolo() . ' teve sua situação alterada para ' . $protocolo->getSituacao()->getNome(), 
                    $protocolo->getRequerente()->getUsuario(), 'info'
                );
            }
            $this->getDoctrine()->getManager()->merge($protocolo);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message','As informações do protocolo ' . $protocolo->getProtocolo() . ' foram atualizadas');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error','Erro: ' . $ex->getMessage());
        }
        return $this->forward('ProtocoloBundle:Protocolo:pesquisar', array('categoria' => $protocolo->getSolicitacao()->getCategoria()));
    }
    
    /**
        * @PreAuthorize("#protocolo.getResponsavelAtual().getId() == user.getPessoa().getId()")
        */
    public function formEncaminhamentoAction(Protocolo $protocolo) {
        $renderParams = array('protocolo' => $protocolo);
        $renderParams['situacoes'] = $this->getDoctrine()->getRepository('ProtocoloBundle:Situacao')->findBy(
            array('categoriaAplicavel' => $renderParams['protocolo']->getSolicitacao()->getCategoria()), array('nome' => 'ASC')
        );
        $renderParams['motivos'] = $this->getDoctrine()->getRepository('ProtocoloBundle:MotivoEncaminhamento')->findBy(
            array('categoriaAplicavel' => $renderParams['protocolo']->getSolicitacao()->getCategoria()), array('descricao' => 'ASC')
        );
        $role = $this->getDoctrine()->getRepository('IntranetBundle:Role')->findOneBy(
            array('role' => 'ROLE_PROTOCOLO_' . $protocolo->getSolicitacao()->getCategoria()->getId())
        );
        $renderParams['usuarios'] = $role->getUsuarios();
        return $this->render('ProtocoloBundle:Protocolo:modalFormEncaminhamento.html.twig', $renderParams);
    }
    
    /**
        * @PreAuthorize("#protocolo.getResponsavelAtual().getId() == user.getPessoa().getId()")
        */
    public function encaminharAction(Protocolo $protocolo) {
        try {
            $situacao = $this->getDoctrine()->getRepository('ProtocoloBundle:Situacao')->find($this->getRequest()->request->get('situacao'));
            $pessoaRecebe = $this->getDoctrine()->getRepository('CommonsBundle:PessoaFisica')->find($this->getRequest()->request->get('pessoaRecebe'));
            $encaminhamento = new Encaminhamento();
            $encaminhamento->setProtocolo($protocolo);
            $encaminhamento->setPessoaEncaminha($protocolo->getResponsavelAtual());
            $encaminhamento->setPessoaRecebe($pessoaRecebe);
            $encaminhamento->setDataCadastro(new \DateTime());
            $encaminhamento->setObservacao($this->getRequest()->request->get('observacao'));
            $encaminhamento->setRecebido(false);
            if($this->getRequest()->request->get('motivo')) {
                $motivo = $this->getDoctrine()->getRepository('ProtocoloBundle:MotivoEncaminhamento')->find($this->getRequest()->request->get('motivo'));
                $encaminhamento->setMotivo($motivo);
            }
            $protocolo->getEncaminhamentos()->add($encaminhamento);
            $protocolo->setSituacao($situacao);
            $protocolo->setDataModificacao(new \DateTime());
            $this->getDoctrine()->getManager()->merge($protocolo);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message','Protocolo ' . $protocolo->getProtocolo() . ' encaminhado para ' . $pessoaRecebe->getNome());
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error','Erro: ' . $ex->getMessage());
        }
        return $this->forward('ProtocoloBundle:Protocolo:pesquisar', array('categoria' => $protocolo->getSolicitacao()->getCategoria()));
    }
    
    /**
        * @PreAuthorize("hasRole('ROLE_PROTOCOLO_GERENTE') or #protocolo.getResponsavelAtual().getId() == user.getPessoa().getId()")
        */
    public function cancelarEncaminhamentoAction(Protocolo $protocolo) {
        try {
            $encaminhamento = $protocolo->getEncaminhamentos()->last();
            if($encaminhamento->getRecebido()) {
                throw new \Exception('Não existem encaminhamentos em aberto para este protocolo');
            }
            $this->getDoctrine()->getManager()->remove($encaminhamento);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message','O encaminhamento do ' . $protocolo->getProtocolo() . ' foi cancelado');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error','Erro: ' . $ex->getMessage());
        }
        return $this->forward('ProtocoloBundle:Protocolo:pesquisar', array('categoria' => $protocolo->getSolicitacao()->getCategoria()));
    }
    
    public function imprimirAction(Protocolo $protocolo) {
        $reportFactory = new RequerimentoReportFactory();
        $pdf = $reportFactory->makeRequerimentoReport($protocolo);
        return $pdf->render();
    }
    
}
