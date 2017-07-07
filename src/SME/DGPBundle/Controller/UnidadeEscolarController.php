<?php

namespace SME\DGPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use SME\DGPBundle\Entity\Vinculo;
use SME\DGPBundle\Entity\TipoVinculo;
use SME\DGPBundle\Entity\Alocacao;
use SME\DGPBundle\Entity\Cargo;
use SME\DGPBundle\Report\FichaPontoReport;
use Doctrine\Common\Collections\ArrayCollection;

class UnidadeEscolarController extends Controller {
    
    public function listarAlocacoesAction() {
        $unidade = $this->getRequest()->getSession()->get('unidade');
        $alocacoes = $this->get('dgp_alocacao')->findByLocalTrabalho($unidade);
        return $this->render('DGPBundle:UnidadeEscolar:listaAlocacoes.html.twig', array('alocacoes' => $alocacoes));
    }
    
    public function buscarEmailByUsuarioAction() {
        if ($this->getRequest()->request->get('nome')) {
            $pessoaFisica = $this->getDoctrine()->getRepository('CommonsBundle:PessoaFisica')->findOneByNome($this->getRequest()->request->get('nome'));
            return new Response($pessoaFisica->getEmail());
        } else {
            return '';
        }
    }
    
    public function salvarEmailServidorAction() {
        $pessoas = $this->getDoctrine()->getRepository('CommonsBundle:PessoaFisica')->findByEmail($this->getRequest()->request->get('email'));
        $totalPessoas = count($pessoas);
        
        if ($this->getRequest()->request->get('email') && $totalPessoas == 0) {
            if ($this->getRequest()->request->get('nome')) {
                $pessoaFisica = $this->getDoctrine()->getRepository('CommonsBundle:PessoaFisica')->findOneByNome($this->getRequest()->request->get('nome'));
                if ($pessoaFisica) {
                    $pessoaFisica->setEmail($this->getRequest()->request->get('email'));
                    try {
                        $this->get('cadastro_unico')->retain($pessoaFisica);
                        return new Response('true');
                    } catch (\Exception $e) {
                        return new Response($e->getMessage());
                    }
                } else {
                    return new Response('Sem Pessoa Física');
                }
            } else {
                return new Response('Atributo nome não recebido/enviado');
            }
        } else {
            return new Response('Atributo email não enviado ou já existente em outra conta.');
        }
    }
    
    public function formPesquisaVinculoAction() {
        return $this->render('DGPBundle:UnidadeEscolar:formPesquisaVinculo.html.twig', array(
            'cargos' => $this->getDoctrine()->getRepository('DGPBundle:Cargo')->findBy(array(), array('nome' => 'ASC'))
        ));
    }
    
    public function pesquisarVinculoAction() {
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
        $now = new \DateTime();
        $query = $qb->select('v')
                    ->from('DGPBundle:Vinculo','v')
                    ->join('v.cargo', 'c')
                    ->join('v.tipoVinculo', 't')
                    ->join('v.servidor', 's')
                    ->where('v.ativo = true')->andWhere('(t.id <> :ACT OR v.dataTermino > :dataHoje)')
                    ->setParameter('ACT', TipoVinculo::ACT)
                    ->setParameter('dataHoje', $now->format('Y-m-d'));
        if($this->getRequest()->request->get('nome')) {
            $query = $query->andWhere('s.nome LIKE :nome')
                           ->setParameter('nome', '%' . $this->getRequest()->request->get('nome') . '%');
        }
        if($this->getRequest()->request->get('cargo')) {
            $query = $query->andWhere('c.id = :cargo')
                           ->setParameter('cargo', $this->getRequest()->request->get('cargo'));
        }
        $vinculos = $query->orderBy('s.nome', 'ASC')->getQuery()->getResult();
        return $this->render('DGPBundle:UnidadeEscolar:listaVinculos.html.twig', array('vinculos' => $vinculos));
    }
    
    public function formAlocacaoAction(Vinculo $vinculo) {
        return $this->render('DGPBundle:UnidadeEscolar:formAlocacao.html.twig', array(
            'vinculo' => $vinculo,
            'unidade' => $this->getRequest()->getSession()->get('unidade')
        ));
    }
    
    public function alocarVinculoAction(Vinculo $vinculo) {
        $unidadeId = $this->getRequest()->getSession()->get('unidade')->getId();
        if($this->get('security.context')->isGranted('ROLE_UNIDADE_' . $unidadeId) === false) {
            throw new AccessDeniedException('Você não é administrador nesta unidade');
        }
        
        try {
            $chTotal = 0;
            foreach($vinculo->getAlocacoes() as $alocacao) {
                $chTotal += $alocacao->getCargaHoraria();
            }
            if($vinculo->getCargaHoraria() < $chTotal + $this->getRequest()->request->get('cargaHoraria')) {
                throw new \Exception('A soma das alocações não pode superar a carga horária máxima do vínculo');
            }
            $alocacao = new Alocacao();
            $entidade = $this->getDoctrine()->getRepository('CommonsBundle:Entidade')->find($unidadeId);
            $alocacao->setLocalTrabalho($entidade);
            $alocacao->setVinculoServidor($vinculo);
            $alocacao->setCargaHoraria($this->getRequest()->request->get('cargaHoraria'));
            $alocacao->setFuncaoAtual($this->getRequest()->request->get('funcaoAtual'));
            $alocacao->setAtivo(true);
            $alocacao->setOriginal(false);
            $vinculo->getAlocacoes()->add($alocacao);
            $this->getDoctrine()->getManager()->persist($alocacao);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message','Alocação realizada');
            return $this->redirect($this->generateUrl('dgp_unidade_listarAlocacoes'));
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', 'Erro: ' . $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('dgp_unidade_formAlocacao', array('vinculo' => $vinculo->getId())));
    }
    
    public function desalocarVinculoAction(Alocacao $alocacao) {
        $unidadeId = $this->getRequest()->getSession()->get('unidade')->getId();
        if($this->get('security.context')->isGranted('ROLE_UNIDADE_' . $unidadeId) === false) {
            throw new AccessDeniedException('Você não é administrador nesta unidade');
        }
        try {
            $this->get('dgp_alocacao')->remove($alocacao);
            $this->get('session')->getFlashBag()->set('message','O servidor foi desalocado da unidade');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error','Erro: ' . $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('dgp_unidade_listarAlocacoes'));
    }
    
    public function formPontoAction() {
        return $this->render('DGPBundle:UnidadeEscolar:modalFormPonto.html.twig');
    }
    
    public function imprimirPontoAction() {
        $session = $this->getRequest()->getSession();
        $alocacoes = $this->getDoctrine()->getRepository('DGPBundle:Alocacao')->findBy(array('localTrabalho' => $session->get('unidade'), 'ativo' => true));
        $collection = new ArrayCollection($alocacoes);
        //Tratamentos de vínculos comissionados e de duplicidades de servidor, para correta impressão da folha ponto
        foreach ($collection as $aloc) {
            if($aloc->getVinculoServidor()->getTipoVinculo()->getId() === TipoVinculo::COMISSIONADO) {
                $t = new TipoVinculo();
                $t->setNome($aloc->getVinculoServidor()->getVinculoOriginal() ? 'Comissionado/Efetivo' : 'Comissionado/ACT');
                $aloc->getVinculoServidor()->setTipoVinculo($t);
            }
            $firstItem = true;
            $pos = 0;
            foreach ($alocacoes as $x => $alocacao) {
                if ($alocacao->getVinculoServidor()->getServidor()->getId() == $aloc->getVinculoServidor()->getServidor()->getId() && $firstItem) {
                    $firstItem = false;
                    $pos = $x;
                } elseif($alocacao->getVinculoServidor()->getServidor()->getId() == $aloc->getVinculoServidor()->getServidor()->getId() && !$firstItem) {
                    $novoTipo = $aloc->getVinculoServidor()->getTipoVinculo()->getNome() . "/" . $alocacao->getVinculoServidor()->getTipoVinculo()->getNome();
                    $novoCargo = $aloc->getVinculoServidor()->getCargo()->getNome() . "/" . $alocacao->getVinculoServidor()->getCargo()->getNome();
                    $novaCargaHoraria = $aloc->getCargaHoraria() . "/" . $alocacao->getCargaHoraria();
                    $novaFuncao = $aloc->getFuncaoAtual() . "/" . $alocacao->getFuncaoAtual();
                    $c = new Cargo();
                    $c->setNome($novoCargo);
                    $t = new TipoVinculo();
                    $t->setNome($novoTipo);
                    $aloc->getVinculoServidor()->setCargo($c);
                    $aloc->getVinculoServidor()->setTipoVinculo($t);
                    $aloc->setCargaHoraria($novaCargaHoraria);
                    $aloc->setFuncaoAtual($novaFuncao);
                    unset($alocacoes[$x]);
                    $pos = $x;
                }
            }
        }      
        $doc = new FichaPontoReport();
        $doc->setAlocacoes($alocacoes);
        $doc->setMes($this->getRequest()->query->get('mes'));
        $doc->setAno($this->getRequest()->query->get('ano'));
        return $this->get('pdf')->render($doc);
    }
    
}
