<?php
namespace SME\EstagioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SME\EstagioBundle\Entity\Vaga;
use SME\EstagioBundle\Entity\PedidoUsuario;
use SME\EstagioBundle\Entity\Inscricao;
use SME\EstagioBundle\Forms\VagaForm;
use SME\EstagioBundle\Forms\PedidoUsuarioForm;
use SME\CommonsBundle\Entity\PessoaFisica;
use SME\IntranetBundle\Entity\PortalUser;

class EstagioController extends Controller {
    
    public function consultaEstagioAction () {
        if ($this->getRequest()->isXmlHttpRequest()) {
            if ($this->getRequest()->isMethod('POST')) {
                $nome = $this->getRequest()->request->get('nome');
                if (!empty($nome)) {
                    $inscricoes = array();
                    $sql = "SELECT inscricao FROM EstagioBundle:Inscricao as inscricao WHERE inscricao.estagiario LIKE '%$nome%' AND inscricao.ativo = 1 AND (inscricao.fim > :now OR inscricao.fimObservacao > :now) ORDER BY inscricao.id DESC";
                    $emanager = $this->getDoctrine()->getManager();
                    $inscricoesByNome = $emanager->createQuery($sql)->setParameter('now', new \DateTime('now'))->getResult();
                    if (!empty($inscricoesByNome)) { foreach ($inscricoesByNome as $inscricaoByNome) { $inscricoes[] = $inscricaoByNome; } }
                    
                    $repositorio = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa');
                    $results = $repositorio->createQueryBuilder('p')->where('p.nome LIKE :nome')->setParameter('nome', '%'.$nome.'%')->getQuery()->getResult();
                    if (!empty($results)) {
                        foreach ($results as $result) {
                            $inscricoesPorPessoa = $this->getDoctrine()->getRepository('EstagioBundle:Inscricao')->findBy(array('ativo' => 1, 'estagiario' => $result->getId()));
                            if (!empty($inscricoesPorPessoa)) {
                                foreach ($inscricoesPorPessoa as $inscrito) { $inscricoes[] = $inscrito; }
                            }
                        }
                    }
                    
                    if (!empty($inscricoes)) {
                        foreach ($inscricoes as $x => $inscricao) {
                            $pessoaId = $inscricao->getEstagiario();
                            if (is_numeric($pessoaId)) {
                                $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findBy(array('id' => $inscricao->getEstagiario()));
                                $pessoa = $pessoa[0];
                                $inscricao->setEstagiario($pessoa);
                            } else {
                                if (is_object($pessoaId)) {
                                    $inscricao->setEstagiario($pessoaId);
                                } else {
                                    $pessoaFisica = \unserialize($pessoaId);
                                    $obj = new \stdClass();
                                    $obj->nome = $pessoaFisica['nomeEstagiario'];
                                    $obj->dataNascimento = $pessoaFisica['dataNascimento'];
                                    $obj->email = $pessoaFisica['email'];
                                    $obj->cpfCnpj = $pessoaFisica['cpfCnpj'];
                                    $inscricao->setEstagiario($obj);
                                }
                            }

                            $vaga = $this->getDoctrine()->getRepository('EstagioBundle:Vaga')->findBy(array('id' => $inscricao->getVaga()));
                            if (!empty($vaga)) {
                                $vaga = $vaga[0];
                                $inscricao->setVaga($vaga);
                                $unidade = $this->getDoctrine()->getRepository('CommonsBundle:PessoaJuridica')->findBy(array('id' => $vaga->getUnidade()));
                                $unidade = $unidade[0];
                                $vaga->setUnidade($unidade);
                                $inscricoes[$x] = $inscricao;
                            } else {
                                unset($inscricoes[$x]);
                            }
                        }
                    }
                    
                    $status = array('Indeferido', 'Em análise', 'Deferido');
        
                    $turnos = array();
                    $turnosObj = $this->getDoctrine()->getRepository('EstagioBundle:Turno')->findAll();
                    foreach ($turnosObj as $x => $turno) { $turnos[$x+1] = $turno->getNome(); }
                    return $this->render('EstagioBundle:Public:consultaResultados.html.twig', array('inscricoes' => $inscricoes, 'status' => $status, 'turnos' => $turnos));
                } else {
                    
                }
            }
        }
        return $this->render('EstagioBundle:Public:consulta.html.twig');
    }
    
    public function solicitarEstagioAction () {
        $secretaria = $this->getDoctrine()->getRepository('CommonsBundle:PessoaJuridica')->findBy(array('nome' => 'Secretaria Municipal de Educação', 'ativo' => true));
        $sme = $secretaria[0];
        $turnos = $this->getDoctrine()->getRepository('EstagioBundle:Turno')->findAll();
        //$entidades = $this->getDoctrine()->getRepository('CommonsBundle:Entidade')->findBy(array('entidadePai' => $sme->getId()));
        $qb = $this->getDoctrine()->getRepository('CommonsBundle:Entidade')->createQueryBuilder('e');
        $entidades = $qb->join('e.pessoaJuridica', 'p')->where('p.ativo = true')->andWhere('e.entidadePai = :sme')->orderBy('p.nome')->setParameter('sme', $sme->getId())->getQuery()->getResult();
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            if ($this->getRequest()->isMethod('POST')) {
                $unidade = $this->getRequest()->request->get('unidade_vagas');
                $turno = $this->getRequest()->request->get('turno_vagas');
                $options = array('ativo' => true);
                if (!empty($unidade)) { $options['unidade'] = $unidade; }
                if (!empty($turno)) { $options['turno'] = $turno; }
                $vagas = $this->getDoctrine()->getRepository('EstagioBundle:Vaga')->findBy($options);
                
                $sql = "SELECT vaga.id, inscricao.id as inscricaoId FROM EstagioBundle:Inscricao as inscricao INNER JOIN EstagioBundle:Vaga as vaga WITH vaga.id = inscricao.vaga WHERE vaga.ativo = 1 AND inscricao.ativo = 1 AND inscricao.aprovado = :aprovado AND ( :now BETWEEN inscricao.inicio AND inscricao.fim OR :now BETWEEN inscricao.inicioObservacao AND inscricao.fimObservacao ) ORDER BY vaga.id";
                $emanager = $this->getDoctrine()->getManager();
                $resultsInscricoes = $emanager->createQuery($sql)->setParameter('aprovado',2)->setParameter('now', new \DateTime('now'))->getResult();

                $aux = 0;
                $counter = 0;
                $results = array();
                foreach ($resultsInscricoes as $resInscricao) {
                    if ($resInscricao['id'] != $aux) {
                        if ($aux == 0) {
                            $aux = $resInscricao['id'];
                            $counter++;
                        } else {
                            $arrayTotal = array('id' => $aux, 'total' => $counter);
                            $results[] = $arrayTotal;
                            $aux = $resInscricao['id'];
                            $counter = 1;
                        }
                    } else {
                        $counter++;
                    }
                }
                $arrayTotal = array('id' => $aux, 'total' => $counter);
                $results[] = $arrayTotal;
                $excluidos = array();
                
                foreach ($vagas as $y => $vaga) {                   
                    $unidade = $this->getDoctrine()->getRepository('CommonsBundle:PessoaJuridica')->findById($vaga->getUnidade());
                    $turno = $this->getDoctrine()->getRepository('EstagioBundle:Turno')->findById($vaga->getTurno());
                    $vaga->setUnidade($unidade[0]);
                    $vaga->setTurno($turno[0]);
                    
                    foreach ($results as $x => $result) {
                        $id = $vaga->getId();
                        if ($id == $result['id']) {
                            $totalVagas = $vaga->getTotalVagas() - $result['total'];
                            if ($totalVagas > 0) {
                                $vaga->setTotalVagas($totalVagas);
                            } else {
                                $excluidos[] = $y;
                            }
                        }
                    }
                }
                
                foreach ($excluidos as $excluido) { unset($vagas[$excluido]); }
                return $this->render('EstagioBundle:Public:vagas.html.twig', array('vagas' => $vagas));
            }
        } else {
            return $this->render('EstagioBundle:Public:lista.html.twig', array('entidades' => $entidades, 'turnos' => $turnos));
        }
    }
    
    public function solicitarUsuarioAction () {
        $pedido = new PedidoUsuario(); $errors = '';
        $form = $this->createForm(new PedidoUsuarioForm(), $pedido);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $data = $form->getData();
            $dataNasc = date('Y-m-d', strtotime($data->getDataNascimento()));
            $data->setDataNascimento($dataNasc);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($pedido);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Pedido de Usuário criado com sucesso.');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
        } else { 
            $errors = $this->get('form_helper')->getFormErrors($form);
        }
        
        return $this->render('EstagioBundle:Public:pedirUsuario.html.twig', array('form' => $form->createView(), 'erros' => $errors));
    }
    
    public function listarVagasAction () {        
        $secretaria = $this->getDoctrine()->getRepository('CommonsBundle:PessoaJuridica')->findBy(array('nome' => 'Secretaria Municipal de Educação', 'ativo' => true));
        $sme = $secretaria[0];
        $qb = $this->getDoctrine()->getRepository('CommonsBundle:Entidade')->createQueryBuilder('e');
        $entidades = $qb->join('e.pessoaJuridica', 'p')->where('p.ativo = true')->andWhere('e.entidadePai = :sme')->orderBy('p.nome')->setParameter('sme', $sme->getId())->getQuery()->getResult();
        //$entidades = $this->getDoctrine()->getRepository('CommonsBundle:Entidade')->findBy(array('entidadePai' => $sme->getId()));
        $turnos = $this->getDoctrine()->getRepository('EstagioBundle:Turno')->findAll();
        return $this->render('EstagioBundle:Vaga:listaVaga.html.twig', array('entidades' => $entidades, 'turnos' => $turnos));
    }
    
    public function addVagasAction () {
        $vaga = new Vaga(); $errors = '';
        $form = $this->createForm(new VagaForm(), $vaga);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $data = $form->getData();
            $uId = $this->getRequest()->request->get('VagaForm_unidade');
            $data->setUnidade($uId);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($vaga);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Vaga criada com sucesso.');
                return $this->redirect($this->generateUrl('vagas_estagio'));
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
        } else { 
            $errors = $this->get('form_helper')->getFormErrors($form);
        }
        
        $unidades = array();
        $entidades = array();
        $roles = $this->getDoctrine()->getRepository('IntranetBundle:Role')->findBy(array(), array('nomeExibicao' => 'ASC'));
        foreach($roles as $role) {
            if(\strstr($role->getRole(),'ROLE_UNIDADE_')) { $entidades[] = $this->getDoctrine()->getRepository('CommonsBundle:Entidade')->find(\substr($role->getRole(), 13)); }
        }
        
        foreach ($entidades as $entidade) { if (is_object($entidade)) { $unidades[] = $entidade->getPessoaJuridica(); } }
        return $this->render('EstagioBundle:Vaga:addVaga.html.twig', array('form' => $form->createView(), 'erros' => $errors, 'unidades' => $unidades));
    }
    
    public function editarVagasAction (Vaga $vaga) {
        $errors = '';
        $form = $this->createForm(new VagaForm(), $vaga);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $data = $form->getData();
            $uId = $this->getRequest()->request->get('VagaForm_unidade');
            $data->setUnidade($uId);
            try {
                $em = $this->getDoctrine()->getManager();
                $em->merge($vaga);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Vaga editada com sucesso.');
                return $this->redirect($this->generateUrl('vagas_estagio'));
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
        } else { 
            $errors = $this->get('form_helper')->getFormErrors($form);
        }
        
        $unidades = array();
        $entidades = array();
        $roles = $this->getDoctrine()->getRepository('IntranetBundle:Role')->findBy(array(), array('nomeExibicao' => 'ASC'));
        foreach($roles as $role) {
            if(\strstr($role->getRole(),'ROLE_UNIDADE_')) { $entidades[] = $this->getDoctrine()->getRepository('CommonsBundle:Entidade')->find(\substr($role->getRole(), 13)); }
        }
        
        foreach ($entidades as $entidade) { if (is_object($entidade)) { $unidades[] = $entidade->getPessoaJuridica(); } }
        return $this->render('EstagioBundle:Vaga:editVaga.html.twig', array('vaga' => $vaga, 'form' => $form->createView(), 'erros' => $errors, 'unidades' => $unidades));
    }
    
    public function removerVagasAction (Vaga $vaga) {
        $vaga->setAtivo(false);
        $em = $this->getDoctrine()->getManager();
        $em->merge($vaga);
        $em->flush();
        $this->get('session')->getFlashBag()->set('message', 'Vaga removida com sucesso.');
        return $this->redirect($this->generateUrl('vagas_estagio'));
    }
    
    public function orientadoresAction () {
        $pedidos = $this->getDoctrine()->getRepository('EstagioBundle:PedidoUsuario')->findBy(array('ativo' => true, 'status' => 1));
        $instituicoes = array('UNIVALI','UNOPAR','UNIFIL','UNIDERP','UNIASSELVI','UDESC','SOCIESC','Universidade do Contestado Func','Nilton Kucker','SINERGIA','IFES','UNINTER','AVANTIS','SENEC - EAD','UNICESUMAR', 'IFC');
        $deferidos = $this->getDoctrine()->getRepository('EstagioBundle:PedidoUsuario')->findBy(array('ativo' => true, 'status' => 2));
        
        return $this->render('EstagioBundle:Orientador:orientadores.html.twig', array('pedidos' => $pedidos, 'instituicoes' => $instituicoes, 'deferidos' => $deferidos));
    }
    
    public function indeferirUsuarioAction(PedidoUsuario $pedido) {
        $pedido->setStatus(0);
        $em = $this->getDoctrine()->getManager();
        $em->merge($pedido);
        $em->flush();
        $this->get('session')->getFlashBag()->set('message', 'Pedido indeferido com sucesso.');
        return $this->redirect($this->generateUrl('listar_orientadores'));
    }
    
    public function desativarUsuarioAction(PedidoUsuario $pedido) {
        try {
            $cpf = $pedido->getCpf();
            $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findBy(array('cpfCnpj' => $cpf, 'ativo' => true));
            $pessoa = $pessoa[0];
            $user = $pessoa->getUsuario();
            if (!empty($user)) {
                $usuario = $this->getDoctrine()->getRepository('IntranetBundle:PortalUser')->findBy(array('username' => $user->getUsername()));
                if (!empty($usuario)) { $usuario = $usuario[0]; }
                $roles = $usuario->getRolesAtribuidas();
            }
            try{
                if (!empty($user)) {
                    if (!empty($usuario)) {
                        foreach ($roles as $role) {
                            $nome = $role->getRole();
                            if ($nome == 'ROLE_ORIENTADOR_ESTAGIO') {
                                $usuario->getRolesAtribuidas()->removeElement($role);
                                $this->getDoctrine()->getManager()->merge($usuario);
                                $this->getDoctrine()->getManager()->flush();
                            }
                        }
                    }
                }
                
                $pedido->setAtivo(false);
                $this->getDoctrine()->getManager()->merge($pedido);
                $this->getDoctrine()->getManager()->flush();
                
                $this->get('session')->getFlashBag()->set('message', 'Orientador desativado com sucesso.');
            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', 'Orientador não encontrado no sistema.');
            }
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }  
        return $this->redirect($this->generateUrl('listar_orientadores'));   
    }
    
    public function deferirUsuarioAction(PedidoUsuario $pedido) {        
        try {
            $pedido->setStatus(2);
            $em = $this->getDoctrine()->getManager();
            $em->merge($pedido);
            $em->flush();
            
            $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findBy(array('ativo' => true, 'cpfCnpj' => $pedido->getCpf()));
            
            if (!empty($pessoa)) {
                $pessoaFisica = $pessoa[0];
                $portalUser = $pessoaFisica->getUsuario();
                $str_password = "A senha atual utilizada na intranet.";
            } else {
                $dataNasc = date('Y-m-d H:i:s', strtotime($pedido->getDataNascimento()));
                $objDate = new \DateTime($dataNasc);
                $pessoaFisica = new PessoaFisica();
                $pessoaFisica->setCpfCnpj($pedido->getCpf());
                $pessoaFisica->setNome($pedido->getNome());
                $pessoaFisica->setDataNascimento($objDate);
                $pessoaFisica->setEmail($pedido->getEmail());
                $pessoaFisica->setAtivo(true);

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
            }
            
            $role = $this->getDoctrine()->getRepository('IntranetBundle:Role')->findBy(array('role' => 'ROLE_ORIENTADOR_ESTAGIO'));
            $portalUser->getRolesAtribuidas()->add($role[0]);
            $this->getDoctrine()->getManager()->merge($portalUser);
            $this->getDoctrine()->getManager()->flush();
            
            $emailResponse = '';
            try {
                $message = \Swift_Message::newInstance()
                ->setContentType('text/html')
                ->setSubject('Pedido de Usuário - Orientação de Estágio')
                ->setFrom('naoresponda@itajai.sc.gov.br')
                ->setTo($pedido->getEmail())
                ->setBody(
                    $this->renderView(
                        'EstagioBundle:Orientador:email.html.twig',
                        array('cpf' => $pessoaFisica->getCpfCnpj(), 'senha' => $str_password, 'name' => $pessoaFisica->getNome() )
                    )
                );
                $this->get('mailer')->send($message);
            } catch (\Swift_RfcComplianceException $ex) {
                $emailResponse .= 'Email não enviado para o orientador, email inválido. ';
            }
            
            try {
                $emailSupervisor = $pedido->getEmailSupervisor();
                if (!empty($emailSupervisor)) {
                    $message->setSubject('Encaminhamento para Supervisor: Pedido de Usuário - Orientação de Estágio')->setTo($emailSupervisor);
                    $this->get('mailer')->send($message);
                }
            } catch (\Swift_RfcComplianceException $ex) {
                $emailResponse .= 'Email não enviado para o supervisor, email inválido. ';
            }
            
            $emailResponse .= 'Pedido deferido com sucesso.';
            $this->get('session')->getFlashBag()->set('message', $emailResponse);
        } catch (Exception $ex) {
            return new Response($ex->getMessage());
        }
        return $this->redirect($this->generateUrl('listar_orientadores'));
    }
    
    public function inscreverEstagiarioAction () {        
        if ($this->getRequest()->isMethod('POST')) {
            try {
                $nome = $this->getRequest()->request->get('nomeEstagio');
                $email = $this->getRequest()->request->get('emailEstagio');
                
                if (!empty($nome) && !empty($email)) {
                    $cpf = $this->getRequest()->request->get('cpfEstagio');
                    $data = $this->getRequest()->request->get('dataEstagio');
                    $vaga = $this->getRequest()->request->get('vagaEstagio');
                    $inicio = $this->getRequest()->request->get('inicioEstagio');
                    $final = $this->getRequest()->request->get('finalEstagio');
                    $inicioObs = $this->getRequest()->request->get('inicioObsEstagio');
                    $finalObs = $this->getRequest()->request->get('finalObsEstagio');
                    $telefone = $this->getRequest()->request->get('telefoneEstagio');

                    if (!empty($data)) {
                        $strDate = \DateTime::createFromFormat('d/m/Y',$data)->format('Y-d-m');
                        $objDate = \DateTime::createFromFormat('Y-d-m',$strDate);
                    } else { $objDate = $data; }
                    if (empty($inicio)) { $dataIni = $inicio; } else { $dataIni = \DateTime::createFromFormat('d/m/Y',$inicio)->format('Y-m-d'); }
                    if (empty($final)) { $dataFinal = $final; } else { $dataFinal = \DateTime::createFromFormat('d/m/Y',$final)->format('Y-m-d'); }
                    if (empty($inicioObs)) { $dataObsIni = $inicioObs; } else { $dataObsIni = \DateTime::createFromFormat('d/m/Y',$inicioObs)->format('Y-m-d'); }
                    if (empty($finalObs)) { $dataObsFinal = $finalObs; } else { $dataObsFinal = \DateTime::createFromFormat('d/m/Y',$finalObs)->format('Y-m-d'); }

                    $pessoaFisica = array('nomeEstagiario' => $nome, 'dataNascimento' => $objDate, 'email' => $email, 'cpfCnpj' => $cpf);
                    $pessoaSerialized = \serialize($pessoaFisica);

                    /*
                    $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findBy(array('ativo' => true, 'cpfCnpj' => $cpf));

                    if (!empty($pessoa)) {
                        $pessoaFisica = $pessoa[0];
                        $portalUser = $pessoaFisica->getUsuario();
                    } else {
                        $pessoaFisica = new PessoaFisica();
                        $pessoaFisica->setCpfCnpj($cpf);
                        $pessoaFisica->setNome($nome);
                        $pessoaFisica->setDataNascimento($objDate);
                        $pessoaFisica->setEmail($email);
                        $pessoaFisica->setAtivo(true);
                        $this->get('cadastro_unico')->retain($pessoaFisica);
                    }*/

                    $inscricao = new Inscricao();
                    $inscricao->setVaga($vaga);
                    $inscricao->setEstagiario($pessoaSerialized);
                    $inscricao->setOrientador($this->getUser()->getId());
                    $inscricao->setAprovado(1);
                    $inscricao->setInicio($dataIni);
                    $inscricao->setFim($dataFinal);
                    $inscricao->setInicioObservacao($dataObsIni);
                    $inscricao->setFimObservacao($dataObsFinal);
                    $inscricao->setTelefone($telefone);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($inscricao);
                    $em->flush();

                    $vagaObj = $this->getDoctrine()->getRepository('EstagioBundle:Vaga')->findById($vaga);
                    $vagaObj = $vagaObj[0];
                    $unidade = $this->getDoctrine()->getRepository('CommonsBundle:PessoaJuridica')->findBy(array('id' => $vagaObj->getUnidade()));
                    $unidade = $unidade[0];
                    $vagaStr = $vagaObj->getTitulo() . ' - ' . $vagaObj->getDisciplina();

                    $turnos = $this->getDoctrine()->getRepository('EstagioBundle:Turno')->findAll();
                    $turnoId = $vagaObj->getTurno();
                    $turno = $turnos[$turnoId-1];
                        
                    $message = \Swift_Message::newInstance()
                    ->setContentType('text/html')
                    ->setSubject('Inscrição de Estágio')
                    ->setFrom('naoresponda@itajai.sc.gov.br')
                    ->setTo('estagios.educ@itajai.sc.gov.br')
                    ->setCc('estagiositajai@gmail.com')
                    ->setBody(
                        $this->renderView(
                            'EstagioBundle:Orientador:emailAdmin.html.twig',
                            array('turno' => $turno->getNome() ,'vaga_id' => $vaga, 'name' => $pessoaFisica['nomeEstagiario'], 'unidade' => $unidade->getNome(), 'inicio' => $inscricao->getInicio(), 'fim' => $inscricao->getFim(), 'email' => $pessoaFisica['email'], 'inicioObs' => $inscricao->getInicioObservacao(), 'fimObs' => $inscricao->getFimObservacao(), 'telefone' => $inscricao->getTelefone(), 'vaga' => $vagaStr)
                        )
                    );
                    $this->get('mailer')->send($message);

                    $this->get('session')->getFlashBag()->set('message', 'Estágiario inscrito com sucesso.');
                } else {
                    $this->get('session')->getFlashBag()->set('message', 'O campo NOME e E-MAIL são obrigatórios.');
                }
            } catch (Exception $ex) {
                return new Response($ex->getMessage());
            }
        }
        
        $secretaria = $this->getDoctrine()->getRepository('CommonsBundle:PessoaJuridica')->findBy(array('nome' => 'Secretaria Municipal de Educação', 'ativo' => true));
        $sme = $secretaria[0];
        $qb = $this->getDoctrine()->getRepository('CommonsBundle:Entidade')->createQueryBuilder('e');
        $entidades = $qb->join('e.pessoaJuridica', 'p')->where('p.ativo = true')->andWhere('e.entidadePai = :sme')->orderBy('p.nome')->setParameter('sme', $sme->getId())->getQuery()->getResult();
        //$entidades = $this->getDoctrine()->getRepository('CommonsBundle:Entidade')->findBy(array('entidadePai' => $sme->getId()), array('pessoaJuridica' => 'ASC'));
        $turnos = $this->getDoctrine()->getRepository('EstagioBundle:Turno')->findAll();
        $vagas = $this->getDoctrine()->getRepository('EstagioBundle:Vaga')->findByAtivo(true);
        
        return $this->render('EstagioBundle:Orientador:inscrever.html.twig', array('vagas' => $vagas, 'entidades' => $entidades, 'turnos' => $turnos));
    }
    
    public function listarInscritosAction(Vaga $vaga) {
        $arrInscricoes = $this->getDoctrine()->getRepository('EstagioBundle:Inscricao')->findBy(array('aprovado' => 1, 'ativo' => true, 'vaga' => $vaga->getId()));
        $inscricoes = array();
        
        $sql = "SELECT i FROM EstagioBundle:Inscricao as i WHERE i.ativo = 1 AND i.vaga = :vagaId AND i.aprovado >= :aprovado AND i.fim > :now AND i.fim != :empty ORDER BY i.inicio";
        $emanager = $this->getDoctrine()->getManager();
        $resultsIntervencao = $emanager->createQuery($sql)->setParameter('aprovado',2)->setParameter('vagaId', $vaga->getId())->setParameter('now', new \DateTime('now'))->setParameter('empty', '0000-00-00')->getResult();
        
        $sql = "SELECT i FROM EstagioBundle:Inscricao as i WHERE i.ativo = 1 AND i.vaga = :vagaId AND i.aprovado >= :aprovado AND i.fimObservacao > :now AND i.fimObservacao != :empty ORDER BY i.inicioObservacao";
        $emanager = $this->getDoctrine()->getManager();
        $resultsObservacao = $emanager->createQuery($sql)->setParameter('aprovado',2)->setParameter('vagaId', $vaga->getId())->setParameter('now', new \DateTime('now'))->setParameter('empty', '0000-00-00')->getResult();
        
        $deferidos = [];
        foreach ($resultsIntervencao as $intervencao) { $deferidos[] = $intervencao; }
        foreach ($resultsObservacao as $observacao) {
            $observacaoId = $observacao->getId();
            $contador = 0;
            foreach ($deferidos as $deferido) {
                $deferidoId = $deferido->getId();
                if ($deferidoId == $observacaoId) { $contador++; }
            }
            if ($contador == 0) { $deferidos[] = $observacao; }
        }
        
        foreach ($arrInscricoes as $x => $inscricao) {
            $pessoaId = $inscricao->getEstagiario();
            if (is_numeric($pessoaId)) {
                $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findBy(array('id' => $inscricao->getEstagiario()));
                $pessoa = $pessoa[0];
                $inscricao->setEstagiario($pessoa);
            } else {
                $pessoaFisica = \unserialize($pessoaId);
                $obj = new \stdClass();
                $obj->nome = $pessoaFisica['nomeEstagiario'];
                $obj->dataNascimento = $pessoaFisica['dataNascimento'];
                $obj->email = $pessoaFisica['email'];
                $obj->cpfCnpj = $pessoaFisica['cpfCnpj'];
                $inscricao->setEstagiario($obj);
            }
            $inscricoes[$x] = $inscricao;
        }
        
        foreach ($deferidos as $y => $deferido) {
            $pessoaId = $deferido->getEstagiario();
            if (is_numeric($pessoaId)) {
                $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findBy(array('id' => $deferido->getEstagiario()));
                $pessoa = $pessoa[0];
                $deferido->setEstagiario($pessoa);
            } else {
                if (is_object($pessoaId)) {
                    $deferido->setEstagiario($pessoaId);
                } else {
                    $pessoaFisica = \unserialize($pessoaId);
                    $obj = new \stdClass();
                    $obj->nome = $pessoaFisica['nomeEstagiario'];
                    $obj->dataNascimento = $pessoaFisica['dataNascimento'];
                    $obj->email = $pessoaFisica['email'];
                    $obj->cpfCnpj = $pessoaFisica['cpfCnpj'];
                    $deferido->setEstagiario($obj);
                }
                
            }
            $deferidos[$y] = $deferido;
        }
        
        $unidade = $this->getDoctrine()->getRepository('CommonsBundle:PessoaJuridica')->findBy(array('id' => $vaga->getUnidade()));
        $unidade = $unidade[0];
        $vaga->setUnidade($unidade);
        
        $turnos = array();
        $turnosObj = $this->getDoctrine()->getRepository('EstagioBundle:Turno')->findAll();
        foreach ($turnosObj as $x => $turno) { $turnos[$x+1] = $turno->getNome(); }
        
        return $this->render('EstagioBundle:Vaga:inscrever.html.twig', array('inscricoes' => $inscricoes, 'deferidos' => $deferidos, 'vaga' => $vaga, 'turnos' => $turnos));
    }
    
    public function indeferirEstagioAction(Inscricao $inscricao) {
        $vaga = $inscricao->getVaga();
        $inscricao->setAprovado(0);
        $em = $this->getDoctrine()->getManager();
        $em->merge($inscricao);
        $em->flush();
        $this->get('session')->getFlashBag()->set('message', 'Estágio indeferido com sucesso.');
        return $this->redirect($this->generateUrl('listar_inscritos', array('id' => $vaga)));
    }
    
    public function deferirEstagioAction(Inscricao $inscricao) {
        $vagaId = $inscricao->getVaga();
        $vaga = $this->getDoctrine()->getRepository('EstagioBundle:Vaga')->findBy(array('id' => $vagaId));
        $vaga = $vaga[0];
        
        $totalVagas = $vaga->getTotalVagas();
        
        $sql = "SELECT v.id, count(v.id) as total FROM EstagioBundle:Vaga as v INNER JOIN EstagioBundle:Inscricao as i WITH i.vaga = v.id WHERE v.ativo = 1 AND i.ativo = 1 AND i.vaga = :vagaId AND i.aprovado >= :aprovado AND ( :now BETWEEN i.inicio AND i.fim OR :now BETWEEN i.inicioObservacao AND i.fimObservacao ) ORDER BY v.id";
        $emanager = $this->getDoctrine()->getManager();
        $results = $emanager->createQuery($sql)->setParameter('aprovado',2)->setParameter('vagaId', $vaga->getId())->setParameter('now', new \DateTime('now'))->getResult();
        $totalResults = 0;
        
        foreach ($results as $result) {
            $totalResults = $result['total'];
        }
        $vagasAbertas = $totalVagas - $totalResults;
        
        if ($vagasAbertas > 0) {
            $unidade = $this->getDoctrine()->getRepository('CommonsBundle:PessoaJuridica')->findBy(array('id' => $vaga->getUnidade()));
            $unidade = $unidade[0];
            
            $pessoa = null;
            $pessoaId = $inscricao->getEstagiario();
            if (is_numeric($pessoaId)) {
                $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findBy(array('id' => $inscricao->getEstagiario()));
                $pessoa = $pessoa[0];
            } else {
                $pessoaFisica = \unserialize($pessoaId);
                $obj = new \stdClass();
                $obj->nome = $pessoaFisica['nomeEstagiario'];
                $obj->dataNascimento = $pessoaFisica['dataNascimento'];
                $obj->email = $pessoaFisica['email'];
                $obj->cpfCnpj = $pessoaFisica['cpfCnpj'];
                $pessoa = $obj;
            }
            
            $orientadorUser = $this->getDoctrine()->getRepository('IntranetBundle:PortalUser')->findBy(array('id' => $inscricao->getOrientador()));
            $orientadorUser = $orientadorUser[0];
            $orientador = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findBy(array('cpfCnpj' => $orientadorUser->getUsername(), 'usuario' => $orientadorUser->getId(), 'nome' => $orientadorUser->getNomeExibicao()));
            $orientador = $orientador[0];
            $pedido = $this->getDoctrine()->getRepository('EstagioBundle:PedidoUsuario')->findBy(array('cpf' => $orientador->getCpfCnpj()));
            if (!empty($pedido)) { $pedido = $pedido[0]; }

            $turnos = $this->getDoctrine()->getRepository('EstagioBundle:Turno')->findAll();
            $turnoId = $vaga->getTurno();
            $turnoNome = '';
            foreach ($turnos as $turno) {
                $id = $turno->getId();
                if ($turnoId == $id) {
                    $turnoNome = $turno->getNome();
                }
            }
            
            if (is_numeric($pessoaId)) {
                $nomeEstagiario = $pessoa->getNome();
                $emailEstagiario = $pessoa->getEmail();
            } else {
                $nomeEstagiario = $pessoa->nome;
                $emailEstagiario = $pessoa->email;
            }
            
            $vagaStr = $vaga->getTitulo() . ' - ' . $vaga->getDisciplina();
            $emailResponse = '';
            
            try  {
                $message = \Swift_Message::newInstance()
                ->setContentType('text/html')
                ->setSubject('Pedido de Estágio')
                ->setFrom('naoresponda@itajai.sc.gov.br');
                if (is_numeric($pessoaId)) {
                    $message->setTo($pessoa->getEmail());
                } else {
                    $message->setTo($pessoa->email);
                }
                $message->setBody(
                    $this->renderView(
                        'EstagioBundle:Vaga:email.html.twig',
                        array('vagaStr' => $vagaStr, 'turno' => $turnoNome, 'name' => $nomeEstagiario, 'unidade' => $unidade->getNome(), 'inicio' => $inscricao->getInicio(), 'fim' => $inscricao->getFim(), 'inicioObs' => $inscricao->getInicioObservacao(), 'fimObs' => $inscricao->getFimObservacao())
                    )
                );
                $this->get('mailer')->send($message);
            } catch (\Swift_RfcComplianceException $ex) {
                $emailResponse .= 'Email não enviado para o aluno, email inválido. ';
            }
            
            if (!empty($pedido)) {
                try {
                    $message->setTo($pedido->getEmail());
                    $message->setSubject('Resultado - Pedido de Estágio');
                    $this->get('mailer')->send($message);
                } catch (\Swift_RfcComplianceException $ex) {
                    $emailResponse .= 'Email não enviado para o orientador, email inválido. ';
                }
                
                try {
                    if ($pedido->getEmailSupervisor()) {
                        $message->setTo($pedido->getEmailSupervisor());
                        $message->setSubject('Resultado - Pedido de Estágio - Supervisão de Estágio');
                        $this->get('mailer')->send($message);
                    }
                } catch (\Swift_RfcComplianceException $ex) {
                    $emailResponse .= 'Email não enviado para o supervisor de orientação, email inválido. ';
                }
            }
            
            try {
                $message = \Swift_Message::newInstance()
                ->setContentType('text/html')
                ->setSubject('Pedido de Estágio')
                ->setFrom('naoresponda@itajai.sc.gov.br')
                ->setTo($unidade->getEmail())
                ->setBody(
                    $this->renderView(
                        'EstagioBundle:Vaga:emailUnidade.html.twig',
                        array('vagaStr' => $vagaStr, 'turno' => $turnoNome, 'name' => $unidade->getNome(), 'estagiario' => $nomeEstagiario, 'email' => $emailEstagiario, 'inicio' => $inscricao->getInicio(), 'fim' => $inscricao->getFim(), 'inicioObs' => $inscricao->getInicioObservacao(), 'fimObs' => $inscricao->getFimObservacao())
                    )
                );

                $this->get('mailer')->send($message);
            } catch (\Swift_RfcComplianceException $ex) {
                $emailResponse .= 'Email não enviado para a unidade, email inválido. ';
            }
            
            $inscricao->setAprovado(2);
            $em = $this->getDoctrine()->getManager();
            $em->merge($inscricao);
            $em->flush();
            $emailResponse .= 'Estágio deferido com sucesso.';
            $this->get('session')->getFlashBag()->set('message', $emailResponse);
        } else {
            $this->get('session')->getFlashBag()->set('error', 'Todas as vagas deste estágio já foram preenchidas.');
        }
        return $this->redirect($this->generateUrl('listar_inscritos', array('id' => $vagaId)));
    }
    
    public function buscarVagasAction () {
        $unidade = $this->getRequest()->request->get('unidade_vagas');
        $turno = $this->getRequest()->request->get('turno_vagas');
        $options = array('ativo' => true);
        if (!empty($unidade)) { $options['unidade'] = $unidade; }
        if (!empty($turno)) { $options['turno'] = $turno; }
        $vagas = $this->getDoctrine()->getRepository('EstagioBundle:Vaga')->findBy($options);

        $sql = "SELECT vaga.id, inscricao.id as inscricaoId FROM EstagioBundle:Inscricao as inscricao INNER JOIN EstagioBundle:Vaga as vaga WITH vaga.id = inscricao.vaga WHERE vaga.ativo = 1 AND inscricao.ativo = 1 AND inscricao.aprovado = :aprovado AND ( :now BETWEEN inscricao.inicio AND inscricao.fim OR :now BETWEEN inscricao.inicioObservacao AND inscricao.fimObservacao ) ORDER BY vaga.id";
        $emanager = $this->getDoctrine()->getManager();
        $resultsInscricoes = $emanager->createQuery($sql)->setParameter('aprovado',2)->setParameter('now', new \DateTime('now'))->getResult();
        
        $aux = 0;
        $counter = 0;
        $results = array();
        foreach ($resultsInscricoes as $resInscricao) {
            if ($resInscricao['id'] != $aux) {
                if ($aux == 0) {
                    $aux = $resInscricao['id'];
                    $counter++;
                } else {
                    $arrayTotal = array('id' => $aux, 'total' => $counter);
                    $results[] = $arrayTotal;
                    $aux = $resInscricao['id'];
                    $counter = 1;
                }
            } else {
                $counter++;
            }
        }
        $arrayTotal = array('id' => $aux, 'total' => $counter);
        $results[] = $arrayTotal;
        $excluidos = array();

        foreach ($vagas as $y => $vaga) {                   
            $unidade = $this->getDoctrine()->getRepository('CommonsBundle:PessoaJuridica')->findById($vaga->getUnidade());
            $turno = $this->getDoctrine()->getRepository('EstagioBundle:Turno')->findById($vaga->getTurno());
            $vaga->setUnidade($unidade[0]);
            $vaga->setTurno($turno[0]);

            foreach ($results as $x => $result) {
                $id = $vaga->getId();
                if ($id == $result['id']) {
                    $totalVagas = $vaga->getTotalVagas() - $result['total'];
                    if ($totalVagas > 0) {
                        $vaga->setTotalVagas($totalVagas);
                    } else {
                        $excluidos[] = $y;
                    }
                }
            }
        }

        foreach ($excluidos as $excluido) { unset($vagas[$excluido]); }
        return $this->render('EstagioBundle:Vaga:listarVagas.html.twig', array('vagas' => $vagas));
    }
    
    public function buscarVagasGeraisAction () {
        $unidade = $this->getRequest()->request->get('unidade_vagas');
        $turno = $this->getRequest()->request->get('turno_vagas');
        $options = array('ativo' => true);
        if (!empty($unidade)) { $options['unidade'] = $unidade; }
        if (!empty($turno)) { $options['turno'] = $turno; }
        $vagas = $this->getDoctrine()->getRepository('EstagioBundle:Vaga')->findBy($options);
        
        $sql = "SELECT vaga.id, inscricao.id as inscricaoId FROM EstagioBundle:Inscricao as inscricao INNER JOIN EstagioBundle:Vaga as vaga WITH vaga.id = inscricao.vaga WHERE vaga.ativo = 1 AND inscricao.ativo = 1 AND inscricao.aprovado = :aprovado AND ( :now BETWEEN inscricao.inicio AND inscricao.fim OR :now BETWEEN inscricao.inicioObservacao AND inscricao.fimObservacao ) ORDER BY vaga.id";
        $emanager = $this->getDoctrine()->getManager();
        $resultsInscricoes = $emanager->createQuery($sql)->setParameter('aprovado',2)->setParameter('now', new \DateTime('now'))->getResult();
        
        $aux = 0;
        $counter = 0;
        $results = array();
        foreach ($resultsInscricoes as $resInscricao) {
            if ($resInscricao['id'] != $aux) {
                if ($aux == 0) {
                    $aux = $resInscricao['id'];
                    $counter++;
                } else {
                    $arrayTotal = array('id' => $aux, 'total' => $counter);
                    $results[] = $arrayTotal;
                    $aux = $resInscricao['id'];
                    $counter = 1;
                }
            } else {
                $counter++;
            }
        }
        $arrayTotal = array('id' => $aux, 'total' => $counter);
        $results[] = $arrayTotal;
        $restantes = array();
        $restantesSize = 0;
        $novoRestantesSize = 0;
        
        foreach ($vagas as $vaga) {
            $unidade = $this->getDoctrine()->getRepository('CommonsBundle:PessoaJuridica')->findById($vaga->getUnidade());
            $turno = $this->getDoctrine()->getRepository('EstagioBundle:Turno')->findById($vaga->getTurno());
            $vaga->setUnidade($unidade[0]);
            $vaga->setTurno($turno[0]);
            
            foreach ($results as $x => $result) {
                $id = $vaga->getId();
                $vagasRestantes = $vaga->getTotalVagas();
                if ($id == $result['id']) {
                    $totalVagas = $vaga->getTotalVagas() - $result['total'];
                    if ($totalVagas > 0) {
                        $vagasRestantes = $totalVagas;
                        $restantes[] = $vagasRestantes;
                        $novoRestantesSize = count($restantes);
                    } else {
                        $restantes[] = 'Sem vagas';
                        $novoRestantesSize = count($restantes);
                    }
                }
            }
            
            if ($novoRestantesSize == $restantesSize) {
                $restantes[] = $vaga->getTotalVagas();
            } else {
                $restantesSize = $novoRestantesSize;
            }
        }
        return $this->render('EstagioBundle:Vaga:listarVagasTable.html.twig', array('vagas' => $vagas, 'vagasRestantes' => $restantes));
    }
    
    public function listarPendentesAction () {
        $inscricoes = $this->getDoctrine()->getRepository('EstagioBundle:Inscricao')->findBy(array('aprovado' => 1, 'ativo' => true));
        
        foreach ($inscricoes as $x => $inscricao) {
            $pessoaId = $inscricao->getEstagiario();
            if (is_numeric($pessoaId)) {
                $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findBy(array('id' => $inscricao->getEstagiario()));
                $pessoa = $pessoa[0];
                $inscricao->setEstagiario($pessoa);
            } else {
                $pessoaFisica = \unserialize($pessoaId);
                $obj = new \stdClass();
                $obj->nome = $pessoaFisica['nomeEstagiario'];
                $obj->dataNascimento = $pessoaFisica['dataNascimento'];
                $obj->email = $pessoaFisica['email'];
                $obj->cpfCnpj = $pessoaFisica['cpfCnpj'];
                $inscricao->setEstagiario($obj);
            }
            
            $vaga = $this->getDoctrine()->getRepository('EstagioBundle:Vaga')->findBy(array('id' => $inscricao->getVaga()));
            if (!empty($vaga)) {
                $vaga = $vaga[0];
                $unidade = $this->getDoctrine()->getRepository('CommonsBundle:Entidade')->findBy(array('id' => $vaga->getUnidade()));
                $vaga->setUnidade($unidade[0]);
                $inscricao->setVaga($vaga);
                $inscricoes[$x] = $inscricao;
            } else {
                unset($inscricoes[$x]);
            }
        }
        
        $turnos = array();
        $turnosObj = $this->getDoctrine()->getRepository('EstagioBundle:Turno')->findAll();
        foreach ($turnosObj as $x => $turno) { $turnos[$x+1] = $turno->getNome(); }
        
        return $this->render('EstagioBundle:Vaga:listarPendentes.html.twig', array('inscricoes' => $inscricoes, 'turnos' => $turnos,));
    }
    
    public function deferidosPorEscolaAction () {
        $unidade = $this->getRequest()->getSession()->get('unidade');
        $vagas = $this->getDoctrine()->getRepository('EstagioBundle:Vaga')->findBy(array('unidade' => $unidade->getId(), 'ativo' => true));
        $todasInscricoes = array();
        $instituicoes = array('UNIVALI','UNOPAR','UNIFIL','UNIDERP','UNIASSELVI','UDESC','SOCIESC','Universidade do Contestado Func','Nilton Kucker','SINERGIA','IFES','UNINTER','AVANTIS','SENEC - EAD','UNICESUMAR', 'IFC');
        
        if (!empty($vagas)) {
            foreach ($vagas as $vaga) {
                $sql = "SELECT inscricao FROM EstagioBundle:Inscricao as inscricao WHERE inscricao.vaga = :vaga AND inscricao.ativo = 1 AND inscricao.aprovado = :aprovado AND ( inscricao.inicio > :now OR inscricao.inicioObservacao > :now ) ORDER BY inscricao.id DESC";
                $emanager = $this->getDoctrine()->getManager();
                $inscricoes = $emanager->createQuery($sql)->setParameter('vaga', $vaga->getId())->setParameter('aprovado',2)->setParameter('now', new \DateTime('now'))->getResult();
                
                //$inscricoes = $this->getDoctrine()->getRepository('EstagioBundle:Inscricao')->findBy(array('vaga' => $vaga->getId(), 'aprovado' => 2, 'ativo' => true));
                $unidade = $this->getDoctrine()->getRepository('CommonsBundle:PessoaJuridica')->findBy(array('id' => $vaga->getUnidade()));
                $unidade = $unidade[0];
                $vaga->setUnidade($unidade);
                
                $turnos = array();
                $turnosObj = $this->getDoctrine()->getRepository('EstagioBundle:Turno')->findAll();
                foreach ($turnosObj as $x => $turno) { $turnos[$x+1] = $turno->getNome(); }
                
                if (!empty($inscricoes)) {
                    foreach ($inscricoes as $inscricao) {
                        $pessoaId = $inscricao->getEstagiario();
                        if (is_numeric($pessoaId)) {
                            $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findBy(array('id' => $inscricao->getEstagiario()));
                            $pessoa = $pessoa[0];
                            $inscricao->setEstagiario($pessoa);
                        } else {
                            if (is_object($pessoaId)) {
                                $inscricao->setEstagiario($pessoaId);
                            } else {
                                $pessoaFisica = \unserialize($pessoaId);
                                $obj = new \stdClass();
                                $obj->nome = $pessoaFisica['nomeEstagiario'];
                                $obj->dataNascimento = $pessoaFisica['dataNascimento'];
                                $obj->email = $pessoaFisica['email'];
                                $obj->cpfCnpj = $pessoaFisica['cpfCnpj'];
                                $inscricao->setEstagiario($obj);
                            }
                        }
                        $inscricao->setVaga($vaga);
                        
                        $orientadorUser = $this->getDoctrine()->getRepository('IntranetBundle:PortalUser')->findBy(array('id' => $inscricao->getOrientador()));
                        if (!empty($orientadorUser)) {
                            $orientadorUser = $orientadorUser[0];
                            $orientador = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findBy(array('cpfCnpj' => $orientadorUser->getUsername(), 'usuario' => $orientadorUser->getId(), 'nome' => $orientadorUser->getNomeExibicao()));
                            $orientador = $orientador[0];
                            $pedido = $this->getDoctrine()->getRepository('EstagioBundle:PedidoUsuario')->findBy(array('cpf' => $orientador->getCpfCnpj()));
                            if (!empty($pedido)) { $pedido = $pedido[0]; } else { $pedido = $orientador; }
                            $inscricao->setOrientador($pedido);
                            $todasInscricoes[] = $inscricao;
                        }
                    }
                }
            }
        }
        return $this->render('EstagioBundle:Vaga:listarDeferidosPorEscola.html.twig', array('turnos' => $turnos, 'inscricoes' => $todasInscricoes, 'instituicoes' => $instituicoes));
    }
    
    public function inscritosPorOrientadorAction () {
        $orientadorExId = $this->getRequest()->query->get('id');
        if (!empty($orientadorExId)) {
            $orientadorId = $orientadorExId;
        } else {
            $orientadorId = $this->getUser()->getId();
            $orientadorCpf = $this->getUser()->getUsername();
        }
        
        $sql = "SELECT inscricao FROM EstagioBundle:Inscricao as inscricao WHERE inscricao.orientador = $orientadorId AND inscricao.ativo = 1 AND (inscricao.fim > :now OR inscricao.fimObservacao > :now) ORDER BY inscricao.id DESC";
        $emanager = $this->getDoctrine()->getManager();
        $inscricoes = $emanager->createQuery($sql)->setParameter('now', new \DateTime('now'))->getResult();
        $status = array('Indeferido', 'Em análise', 'Deferido');
        
        $turnos = array();
        $turnosObj = $this->getDoctrine()->getRepository('EstagioBundle:Turno')->findAll();
        foreach ($turnosObj as $x => $turno) { $turnos[$x+1] = $turno->getNome(); }
        
        if (!empty($inscricoes)) {
            foreach ($inscricoes as $inscricao) {
                $pessoaId = $inscricao->getEstagiario();
                if (is_numeric($pessoaId)) {
                    $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:Pessoa')->findBy(array('id' => $inscricao->getEstagiario()));
                    $pessoa = $pessoa[0];
                    $inscricao->setEstagiario($pessoa);
                } else {
                    if (is_object($pessoaId)) {
                        $inscricao->setEstagiario($pessoaId);
                    } else {
                        $pessoaFisica = \unserialize($pessoaId);
                        $obj = new \stdClass();
                        $obj->nome = $pessoaFisica['nomeEstagiario'];
                        $obj->dataNascimento = $pessoaFisica['dataNascimento'];
                        $obj->email = $pessoaFisica['email'];
                        $obj->cpfCnpj = $pessoaFisica['cpfCnpj'];
                        $inscricao->setEstagiario($obj);
                    }
                }
                $vaga = $this->getDoctrine()->getRepository('EstagioBundle:Vaga')->findBy(array('id' => $inscricao->getVaga(), 'ativo' => true));
                $vaga = $vaga[0];
                $unidade = $this->getDoctrine()->getRepository('CommonsBundle:PessoaJuridica')->findBy(array('id' => $vaga->getUnidade()));
                $unidade = $unidade[0];
                $vaga->setUnidade($unidade);
                $inscricao->setVaga($vaga);

                $pedido = $this->getDoctrine()->getRepository('EstagioBundle:PedidoUsuario')->findBy(array('cpf' => $orientadorCpf));
                $pedido = $pedido[0];
                $inscricao->setOrientador($pedido);
            }
        }
        
        return $this->render('EstagioBundle:Orientador:listarDeferidos.html.twig', array('turnos' => $turnos, 'inscricoes' => $inscricoes, 'status' => $status));
    }
    
    public function removerEstagioAction(Inscricao $inscricao) {
        $vaga = $inscricao->getVaga();
        $inscricao->setAtivo(0);
        $em = $this->getDoctrine()->getManager();
        $em->merge($inscricao);
        $em->flush();
        $this->get('session')->getFlashBag()->set('message', 'Estágio removido com sucesso.');
        return $this->redirect($this->generateUrl('listar_inscritos', array('id' => $vaga)));
    }
}
