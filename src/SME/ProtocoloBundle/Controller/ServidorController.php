<?php

namespace SME\ProtocoloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use SME\ProtocoloBundle\Reports\RequerimentoReportFactory;
use SME\ProtocoloBundle\Exception\SolicitacaoDesconhecidaException;
use SME\ProtocoloBundle\Entity\Protocolo;
use SME\ProtocoloBundle\Entity\InformacaoDocumento;
use SME\ProtocoloBundle\Entity\Solicitacao;
use SME\ProtocoloBundle\Entity\Categoria;

class ServidorController extends Controller {
    
    public function listarAction(Categoria $categoria) {
        $renderParams = array('categoria' => $categoria);
        $renderParams['protocolos'] = $this->getDoctrine()->getManager()->createQueryBuilder()
                    ->select('p')
                    ->from('ProtocoloBundle:Protocolo','p')
                    ->join('p.requerente', 'r')->join('p.solicitacao', 's')->join('s.categoria', 'c')
                    ->where('c.id = :categoria')->setParameter('categoria', $categoria->getId())
                    ->andWhere('r.id = :requerente')->setParameter('requerente', $this->getUser()->getPessoa()->getId())
                    ->orderBy('p.dataCadastro', 'DESC')->getQuery()->getResult();
        $renderParams['solicitacoes'] = $this->getDoctrine()->getRepository('ProtocoloBundle:Solicitacao')->findBy(
            array('ativo' => true, 'categoria' => $categoria),
            array('nome' => 'ASC')
        );
        return $this->render('ProtocoloBundle:Servidor:listaProtocolos.html.twig', $renderParams);
    }
    
    /** @PreAuthorize("#protocolo.getRequerente().getId() == user.getPessoa().getId()")  */
    public function visualizarAction(Protocolo $protocolo) {
        return $this->render('ProtocoloBundle:Protocolo:modalConsultaProtocolo.html.twig', array('protocolo' => $protocolo));
    }
    
    public function formSolicitacaoAction() {
        $pessoaFisica = $this->getUser()->getPessoa();
        $vinculos = $this->getDoctrine()->getRepository('DGPBundle:Vinculo')->findBy(array('servidor' => $pessoaFisica));
        $solicitacao = $this->getDoctrine()->getRepository('ProtocoloBundle:Solicitacao')->find($this->getRequest()->request->get('solicitacao'));
        $renderParams = array(
            'solicitacao' => $solicitacao,
            'pessoa' => $pessoaFisica,
            'vinculos' => $vinculos
        );
        if($solicitacao->getNomeIdentificacao() === 'Permuta') {
            $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
            $renderParams['unidades'] = $qb->select('e')->from('CommonsBundle:Entidade','e')->join('e.pessoaJuridica','p')->orderBy('p.nome')->getQuery()->getResult();
            $renderParams['cargos'] = $this->getDoctrine()->getRepository('DGPBundle:Cargo')->findAll(array(), array('nome' => 'ASC'));
        }
        return $this->render('ProtocoloBundle:Forms:' . $solicitacao->getNomeIdentificacao() . '.html.twig', $renderParams);
    }
    
    public function incluirSolicitacaoAction(Solicitacao $solicitacao) {
        $post = $this->getRequest()->request;
        $date = date_create_from_format("j/m/Y",$post->get('data_encerramento'));
        try {
            $protocolo = new Protocolo();
            $protocolo->setRequerente($this->getUser()->getPessoa());
            $protocolo->setSolicitacao($solicitacao);
            $protocolo->setSituacao($solicitacao->getSituacaoInicial());
            $protocolo->setDataCadastro(new \DateTime());
            $protocolo->setDataModificacao(new \DateTime());
            if ($post->has('data_encerramento')) {
                $protocolo->setDataEncerramento($date);
            } else {
                $protocolo->setDataEncerramento(null);
            }
            $protocolo->setAtivo(true);
            $observacao = $this->getDoctrine()->getRepository('ProtocoloBundle:ObservacaoPadrao')->findOneBy(
                array('solicitacao' => $protocolo->getSolicitacao(), 'situacao' => $protocolo->getSituacao())
            );
            $protocolo->setObservacao($observacao !== null ? $observacao->getTexto() : '');
            
            /* Caso necessite carregar dados de um vínculo */
            if($post->has('vinculo')) {
                $vinculo = $this->getDoctrine()->getRepository('DGPBundle:Vinculo')->find($post->get('vinculo'));
                $post->set('data_nomeacao', $vinculo->getDataNomeacao() ? $vinculo->getDataNomeacao()->format('d/m/Y') : '');
                $post->set('cargo_origem', $vinculo->getCargo()->getNome());
                $post->set('carga_horaria', $vinculo->getCargaHoraria());
                $post->set('vinculo', $vinculo->getTipoVinculo()->getNome());
                $post->set('vinculacao_classificacao', $vinculo->getInscricaoVinculacao() ? $vinculo->getInscricaoVinculacao()->getClassificacao() : '');
            }
            /* Caso necessite carregar dados de um local de trabalho */
            if($post->has('alocacao')) {
                if($post->getInt('alocacao') > 0) {
                    $alocacao = $this->getDoctrine()->getRepository('DGPBundle:Alocacao')->find($post->get('alocacao'));
                    $post->set('cargo_atual', ($alocacao->getFuncaoAtual()) ? $alocacao->getFuncaoAtual() : $alocacao->getVinculoServidor()->getCargo()->getNome());
                    $post->set('local_trabalho', $alocacao->getLocalTrabalho()->getPessoaJuridica()->getNome());
                } else {
                    $post->set('cargo_atual', '[não informado]');
                    $post->set('local_trabalho', '[não informado]');
                }
            }
            foreach($post->keys() as $key) {
                $param = new InformacaoDocumento();
                $param->setProtocolo($protocolo);
                $param->setNomeCampo($key);
                $param->setValor($post->get($key));
                $protocolo->getInformacoesDocumento()->add($param);
            }
            $this->getDoctrine()->getManager()->persist($protocolo);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message','Solicitação cadastrada com o protocolo ' . $protocolo->getProtocolo());
            return $this->redirect($this->generateUrl('protocolo_servidor_listar', array('categoria' => $solicitacao->getCategoria()->getId())));
        } catch(SolicitacaoDesconhecidaException $ex) {
            $this->get('session')->getFlashBag()->set('error','Erro: ' . $ex->getMessage());
            return $this->redirect($this->generateUrl('protocolo_servidor_listar', array('categoria' => $solicitacao->getCategoria()->getId())));
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error','Erro: ' . $ex->getMessage());
            $post->set('solicitacao', $solicitacao->getId());
            return $this->forward('ProtocoloBundle:Servidor:formSolicitacao');
        }
    }
    
    /** @PreAuthorize("#protocolo.getRequerente().getId() == user.getPessoa().getId()") */
    public function imprimirAction(Protocolo $protocolo) {
        if($protocolo->getRequerente()->getUsuario() != null && $protocolo->getRequerente()->getUsuario()->equals($this->getUser())) {
            $reportFactory = new RequerimentoReportFactory();
            $pdf = $reportFactory->makeRequerimentoReport($protocolo);
            return $pdf->render();
        }
        else {
            throw new \Exception('Você não é o requerente deste protocolo.');
        }
    }
    
}
