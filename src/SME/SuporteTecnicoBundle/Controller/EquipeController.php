<?php

namespace SME\SuporteTecnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SME\SuporteTecnicoBundle\Form\IntegranteEquipeType;
use SME\SuporteTecnicoBundle\Form\EquipeBasicType;
use SME\SuporteTecnicoBundle\Entity\Equipe;
use SME\CommonsBundle\Entity\PessoaFisica;
use SME\IntranetBundle\Entity\PortalUser;

class EquipeController extends Controller {
    
    public function pesquisarAction() {
    	$equipes = $this->getDoctrine()->getRepository('SuporteTecnicoBundle:Equipe')->findBy(array('ativo'=>true),array('nome'=>'ASC'));
    	$form = $this->createForm(new EquipeBasicType());
    	return $this->render('SuporteTecnicoBundle:Equipe:equipes.html.twig', array('form' => $form->createView(), 'equipes' => $equipes));
    }
    
    public function cadastrarAction(Request $request) {
    	$form = $this->createForm(new EquipeBasicType());
    	$form->handleRequest($request);
    	
    	if ($form->isValid()) {
            $equipe = new Equipe();
            $data = $form->getData();
            $equipe->setNome($data['nome']);
            $equipe->setDepartamento($data['departamento']);

            try {
                $this->getDoctrine()->getManager()->persist($equipe);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message','Equipe criada com sucesso');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage() );
            }
    	}
    	return $this->redirect($this->generateUrl('suporte_equipe_pesquisar'));
    }
    
    public function atualizarAction(Equipe $equipe) {
    	
    	$form = $this->createForm(new EquipeBasicType());
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            $data = $form->getData();
            $equipe->setNome($data['nome']);
            $equipe->setDepartamento($data['departamento']);
            try {
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message','Equipe editada com sucesso.');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage() );
            }
    	}
    	
    	return $this->redirect($this->generateUrl('suporte_equipe_adicionarIntegrante',array('equipe'=>$equipe->getId())));
    }
    
    public function atualizarAjaxAction(Equipe $equipe) {
    	if ($this->getRequest()->request->get('nome')) {
            $equipe->setNome($this->getRequest()->request->get('nome'));
            $this->getDoctrine()->getManager()->flush();
            return new Response('success');
    	}
    	return new Response('Houve um problema ao renomear a equipe. Caso o problema persista, contate o administrador do sistema.');
    }
    
    public function adicionarIntegranteAction(Equipe $equipe) {
    	$form = $this->createForm(new IntegranteEquipeType());
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            $values = $form->getData();
            if ($this->getRequest()->request->get('cpf')) {
                $values['cpf'] = $this->getRequest()->request->get('cpf');
                $values['dataNascimento'] = $this->getRequest()->request->get('dataNascimento');
                $values['email'] = $this->getRequest()->request->get('email'); 
            }
            try {
                $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:PessoaFisica')->findOneBy(
                    array('nome' => $values['nome'], 'ativo' => true), array('id' => 'DESC')
                );
                if (!$pessoa) { $pessoa = $this->criarNovoMembro($values); }
                $integrantes = $equipe->getIntegrantes();
                $integrantes[] = $pessoa;
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message','Integrante adicionado com sucesso.');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage() );
            }
    	}
    	$formTeam = $this->createForm(new EquipeBasicType(), $equipe);
    	return $this->render('SuporteTecnicoBundle:Equipe:adicionarIntegrante.html.twig', array('form' => $form->createView(), 'equipe' => $equipe, 'form_equipe' => $formTeam->createView()));
    }
    
    private function criarNovoMembro (array $values) {
        $pessoa = $this->get('cadastro_unico')->createByCpf($values['cpf']);
        if($pessoa->getId()) {
            throw new \Exception('Já existe uma pessoa cadastrada com este CPF, verifique se o nome foi digitado corretamente e procure novamente');
        }
        $pessoa->setNome($values['nome']);
        $pessoa->setDataNascimento(\DateTime::createFromFormat('d/m/Y', $values['dataNascimento']));
        $pessoa->setEmail($values['email']);
        $pessoa->setAtivo(true);
        $user = new PortalUser();
        $user->setUsername($pessoa->getCpfCnpj());
        $str_password = explode(' ', $pessoa->getNome());
        $index_password = count($str_password) - 1;
        $str_password = $str_password[$index_password] . substr($pessoa->getCpfCnpj(), 0, -7);
        $password = $this->get('md5_encoder')->encodePassword($str_password, null);
        $user->setPassword($password);
        $user->setNomeExibicao($pessoa->getNome());
        $pessoa->setUsuario($user);
        $this->get('cadastro_unico')->retain($pessoa);
        return $pessoa;
    }
    
    public function buscarPessoaAction() {
    	$repository = $this->getDoctrine()->getRepository('CommonsBundle:PessoaFisica');
    	$pessoas = $repository->createQueryBuilder('p')
            ->where('p.ativo = :ativo')
            ->andWhere('p.nome LIKE :nome')
            ->orWhere('p.cpfCnpj LIKE :cpf')
            ->setParameter('ativo', true)
            ->setParameter('nome', '%'.$this->getRequest()->request->get('query').'%')
            ->setParameter('cpf', '%'.$this->getRequest()->request->get('query').'%')
            ->groupBy('p.nome')
            ->orderBy('p.id', 'DESC')
            ->orderBy('p.nome', 'ASC')
            ->getQuery()->getResult();
    	$response = new Response(json_encode($pessoas));
    	$response->headers->set('Content-type', 'application/json');
    	return $response;
    }
    
    public function excluirIntegranteAction(Equipe $equipe, PessoaFisica $integrante) {
        $integrantes = $equipe->getIntegrantes();
        $integrantes->removeElement($integrante);
        try {        	
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message','Integrante excluído com sucesso.');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage() );
        }
        return $this->redirect($this->generateUrl('suporte_equipe_adicionarIntegrante',array('equipe'=>$equipe->getId())));
    }
    
    public function excluirAction(Equipe $equipe) {
    	$equipe->setAtivo(false);
        //excluir categorias subordinadas também
    	try {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message','Equipe excluída com sucesso.');
    	} catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage() );
    	}
    	return $this->redirect($this->generateUrl('suporte_equipe_pesquisar'));
    }
}
