<?php

namespace SME\DGPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SME\CommonsBundle\Entity\PessoaFisica;
use SME\CommonsBundle\Entity\Endereco;
use SME\CommonsBundle\Entity\Estado;
use SME\DGPBundle\Forms\Servidor\PesquisaPessoaForm;
use SME\DGPBundle\Forms\Servidor\PessoaFisicaForm;
use SME\DGPBundle\Forms\Servidor\NovaPessoaFisicaForm;
use SME\DGPBundle\Forms\Servidor\EnderecoForm;
use SME\IntranetBundle\Entity\PortalUser;
use SME\MoodleBundle\Entity\MoodleUser;
use SME\ErudioBundle\Entity\ErudioUser;
use SME\ErudioBundle\Entity\ErudioPessoa;
use SME\ErudioBundle\Entity\ErudioPessoaFisica;

class PessoaController extends Controller {

    public function pesquisarAction(Request $request) {
    	$search = array();
    	$form = $this->createForm(new PesquisaPessoaForm(), $search);
    	
    	$form->handleRequest($request);
    	
    	if ($form->isValid()) {
    		
            $post = $request->request->all();    		

            $select = "SELECT p.id AS id, p.nome AS nome, p.cpfCnpj AS cpf, p.dataNascimento AS dataNascimento, p.email AS email, p.ativo AS ativo FROM CommonsBundle:Pessoa p";
            $wheres = " WHERE";
            $parameters = array();
            $cargo_ok = false;
            $afastado_ok = true;
            $first_ok = false;

            foreach ($post as $x => $results) {
                if (is_array($results)) {    				
                    foreach ($results as $y => $result) {
                        if (is_array($result))
                        {
                            foreach ($result as $z => $data)
                            {    							
                                if ($data) {
                                    switch ($z) {
                                        case 'nome':
                                            $wheres .= " AND p.nome LIKE :nome";
                                            $parameters['nome'] = "%" . $data . "%";    										
                                            break;
                                        case 'cargo':
                                            $select .= " INNER JOIN DGPBundle:Vinculo v WITH v.servidor = p.id";
                                            $select .= " INNER JOIN DGPBundle:Cargo c WITH v.cargo = c.id";
                                            $wheres .= " AND c.nome LIKE :cargo";
                                            $parameters['cargo'] = "%" . $data . "%";
                                            $cargo_ok = true;
                                            break;
                                        case 'email':
                                            $wheres .= " AND p.email = :email";
                                            $parameters['email'] = $data;
                                            break;
                                        case 'cpfCnpj':
                                            $wheres .= " AND p.cpfCnpj = :cpfCnpj";
                                            $parameters['cpfCnpj'] = $data;
                                            break;
                                        case 'tipoVinculo':
                                            if (!$cargo_ok)
                                            {
                                                $select .= " INNER JOIN DGPBundle:Vinculo v WITH v.servidor = p.id";
                                            }
                                            $select .= " INNER JOIN DGPBundle:TipoVinculo t WITH v.tipoVinculo = t.id";
                                            $wheres .= " AND v.tipoVinculo = :tipoVinculo";
                                            $parameters['tipoVinculo'] = $data;
                                            break;
                                        case 'afastado':    		
                                            if ($data == 'y') {
                                                $wheres .= " AND p.ativo IS NULL";
                                            } else {
                                                $wheres .= " AND p.ativo = 1";
                                            }			
                                            $afastado_ok = false;
                                            break;
                                    }

                                    if ($first_ok == false) {
                                        $wheres = str_replace(" AND", "", $wheres);
                                        $first_ok = true;
                                    }
                                }
                            }
                        }	
                    }
                }
            }

            if ($afastado_ok && $first_ok == false) {
                $wheres .= " p.ativo = 1";
            } else {
                $wheres .= " AND p.ativo = 1";
            }

            $wheres .= " GROUP BY p.nome ORDER BY p.id DESC";

            $query = $select . $wheres;
            $limite = 10;
            $pagina = 1;

            if ($this->getRequest()->query->get('page')) {
                $pagina = $this->getRequest()->query->get('page');
            }

            $registroInicial = ($pagina * $limite) - $limite;

            $em = $this->getDoctrine()->getManager();
            $qs = $em->createQuery($query);
            $qsLimited = $em->createQuery($query)->setMaxResults($limite)->setFirstResult($registroInicial);

            foreach ($parameters as $z => $parameter)
            {
                if ($z != 'afastado') {
                    $qs->setParameter($z,$parameter);
                    $qsLimited->setParameter($z,$parameter);
                }
            }
            try {   			
                $res = $qsLimited->getResult();
                $totalRes = $qs->getResult();
                $totalRegistros = count($totalRes);
                $pages = ceil($totalRegistros/$limite);
                $nextPage = $pagina + 1;
                $prevPage = $pagina - 1;
                return $this->render('DGPBundle:Pessoa:pessoas.html.twig', array('results' => $res, 'proximaPagina' => $nextPage, 'error' => false, 'pages' => $pages, 'paginaAnterior' => $prevPage, 'page' => $pagina));
            } catch (\Doctrine\Orm\NoResultException $ex) {

            }
    	}
    	   	
    	return $this->render('DGPBundle:Pessoa:formPesquisa.html.twig', array('form' => $form->createView()));
    }
    
    public function cadastrarAction() {
    	$pessoaFisica = new PessoaFisica();
    	$errors = "";  	
    	$form = $this->createForm(new NovaPessoaFisicaForm(), $pessoaFisica);
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            $pessoaFisica->setAtivo(true);
            try {
                $this->get('cadastro_unico')->retain($pessoaFisica);
                $portalUser = new PortalUser();
                $portalUser->setUsername($pessoaFisica->getCpfCnpj());
                $str_password = explode(' ', $pessoaFisica->getNome());
                $index_password = count($str_password) - 1;
                $str_password = $str_password[$index_password] . substr($pessoaFisica->getCpfCnpj(), 0, -7);
                $password = $this->get('md5_encoder')->encodePassword($str_password, null);
                $portalUser->setPassword($password);
                $portalUser->setNomeExibicao($pessoaFisica->getNome()); 			
                $em = $this->getDoctrine()->getManager();
                $em->persist($portalUser);
                $em->flush();
                
                $pessoaFisica->setUsuario($portalUser);
                $this->get('cadastro_unico')->retain($pessoaFisica);
                
                $name = explode(' ', $pessoaFisica->getNome());
                $firstname = $name[0];
                unset($name[0]);
                $lastname = '';
                foreach ($name as $namePart) {
                    $lastname .= $namePart . ' ';
                }
                $lastname = trim($lastname);
                
                $this->createErudioUser($portalUser, $pessoaFisica);
                
                $moodleUser = new MoodleUser();
                $moodleUser->setUsername($pessoaFisica->getCpfCnpj());
                $moodleUser->setAuth('manual');
                $moodleUser->setFirstname($firstname);
                $moodleUser->setLastname($lastname);
                $moodleUser->setEmail($pessoaFisica->getEmail());
                $moodleUser->setPassword($this->get('md5_encoder')->encodeMoodlePassword($str_password));
                $pessoaFisica->setUsuario($portalUser);
                $em->flush();
                $emMoodle = $this->getDoctrine()->getManager('moodle');
                $emMoodle->persist($moodleUser);
                $emMoodle->flush();
                
                return $this->redirect($this->generateUrl('dgp_pessoa_alterar', array('pessoa' => $pessoaFisica->getId())));
            } catch (\Exception $e) {
                return new Response($e->getMessage());
            }
    	} else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
    	return $this->render('DGPBundle:Pessoa:formCadastro.html.twig', array('form' => $form->createView(), 'erros' => $errors));
    }
    
    public function createErudioUser($user, $pessoaFisica) {        
        $erudioUser = new ErudioUser();
        $erudioUser->setNomeExibicao($pessoaFisica->getNome());
        $erudioUser->setUsername($pessoaFisica->getCpfCnpj());
        $erudioUser->setPassword($user->getPassword());
        $emErudio = $this->getDoctrine()->getManager('erudio');
        $emErudio->persist($erudioUser);
        $emErudio->flush();
        
        $erudioPessoa = new ErudioPessoa();
        $erudioPessoa->setNome($pessoaFisica->getNome());
        $erudioPessoa->setCpfCnpj($pessoaFisica->getCpfCnpj());
        $erudioPessoa->setDataNascimento($pessoaFisica->getDataNascimento());
        $erudioPessoa->setUsuarioId($erudioUser->getId());
        $emErudio = $this->getDoctrine()->getManager('erudio');
        $emErudio->persist($erudioPessoa);
        $emErudio->flush();
        
        $erudioPessoaFisica = new ErudioPessoaFisica();
        $erudioPessoaFisica->setId($erudioPessoa->getId());
        $emErudio = $this->getDoctrine()->getManager('erudio');
        $emErudio->persist($erudioPessoaFisica);
        $emErudio->flush();
    } 
    
    public function alterarAction(PessoaFisica $pessoa) {
    	$errors = "";
    	$form = $this->createForm(new PessoaFisicaForm(), $pessoa);
    	$form->handleRequest($this->getRequest());
    	if ($form->isValid()) {
            try {
                $this->get('cadastro_unico')->retain($pessoa);
                $this->get('session')->getFlashBag()->set('message', 'Dados pessoais atualizados com sucesso');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
    	} else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	}
    	return $this->render('DGPBundle:Pessoa:formAlteracao.html.twig', array(
            'form' => $form->createView(), 
            'pessoa' => $pessoa, 
            'erros' => $errors
        ));
    }
    
    public function alterarEnderecoAction(PessoaFisica $pessoa) {
	$endereco = $pessoa->getEndereco();	
        if (!$endereco) {
            $endereco = new Endereco();
        }
    	$form = $this->createForm(new EnderecoForm(), $endereco);
    	$form->handleRequest($this->getRequest());  	
    	if ($form->isValid()) {
            $endereco = $form->getData();
            $pessoa->setEndereco($endereco);
            try {
                $this->get('cadastro_unico')->retain($pessoa);
                $this->get('session')->getFlashBag()->set('message', 'Endereço atualizado com sucesso');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
    	}
    	$cidades = $this->getDoctrine()->getRepository('CommonsBundle:Cidade')->findByEstado(Estado::SC);
    	return $this->render('DGPBundle:Pessoa:formEndereco.html.twig', array(
            'form' => $form->createView(), 
            'pessoa' => $pessoa, 
            'cidades' => $cidades)
        );
    }
    
}
