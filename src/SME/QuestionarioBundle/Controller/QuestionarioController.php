<?php

namespace SME\QuestionarioBundle\Controller;

use SME\CommonsBundle\Entity\PessoaFisica;
use SME\QuestionarioBundle\Entity\Pergunta;
use SME\QuestionarioBundle\Entity\Questionario;
use SME\QuestionarioBundle\Entity\QuestionarioCategoria;
use SME\QuestionarioBundle\Entity\QuestionarioRespondido;
use SME\QuestionarioBundle\Entity\QuestionarioPergunta;
use SME\QuestionarioBundle\Forms\QuestionarioForm;
use SME\QuestionarioBundle\Forms\PerguntaForm;
use SME\QuestionarioBundle\Forms\CategoriaForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class QuestionarioController extends Controller {
    
    public function indexAction () {
        $questionario = new Questionario(); $errors = '';
        $form = $this->createForm(new QuestionarioForm(), $questionario);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager(); $em->persist($questionario); $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Questionário criado com sucesso.');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
        } else { $errors = $this->get('form_helper')->getFormErrors($form); } 
        $questionarios = $this->getDoctrine()->getRepository('QuestionarioBundle:Questionario')->findByAtivo(true);
        return $this->render('QuestionarioBundle:Questionario:lista.html.twig', array('questionarios' => $questionarios, 'form' => $form->createView(), 'erros' => $errors));
    }
    
    public function editarQuestionariosAction (Questionario $questionario) {
        $errors = ''; $form = $this->createForm(new QuestionarioForm(), $questionario); $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager(); $em->merge($questionario); $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Questionario modificado com sucesso.');
                return $this->redirect($this->generateUrl('questionario_index'));
            } catch (\Exception $ex) { $this->get('session')->getFlashBag()->set('error', $ex->getMessage()); }
        } else { $errors = $this->get('form_helper')->getFormErrors($form); }
        $categorias = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioCategoria')->findBy(array('ativo'=>true));
        
        $sql = "SELECT q.id, q.questionarioId, q.perguntaId, p.categoriaId FROM QuestionarioBundle:QuestionarioPergunta as q INNER JOIN QuestionarioBundle:Pergunta as p WITH p.id = q.perguntaId WHERE q.questionarioId = :questId ORDER BY p.categoriaId";
        $emanager = $this->getDoctrine()->getManager();
        $results = $emanager->createQuery($sql)->setParameter('questId',$questionario->getId())->getResult();
        $perguntas = array();
        if(!empty($results)) {
            foreach ($results as $item) {
                $pergunta = $this->getDoctrine()->getRepository('QuestionarioBundle:Pergunta')->findById($item['perguntaId']);
                if (!empty($pergunta)) { $item['perguntaId'] = $pergunta[0]; }
                $categoria = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioCategoria')->findById($item['categoriaId']);
                if (!empty($categoria)) { $item['categoriaId'] = $categoria[0]; }
                $perguntas[] = $item;
            }
        }
        return $this->render('QuestionarioBundle:Questionario:editarQuestionario.html.twig', array('categorias' => $categorias,'questionario' => $questionario, 'perguntas' => $perguntas, 'form' => $form->createView(), 'erros' => $errors));
    }
    
    public function adicionarPerguntasQuestionarioAction () {
        $qId = $this->getRequest()->request->get('questionarioId'); $pId = $this->getRequest()->request->get('perguntaId');
        $qPergunta = new QuestionarioPergunta();
        $qPergunta->setQuestionarioId($qId);
        $qPergunta->setPerguntaId($pId);
        try {
            $em = $this->getDoctrine()->getManager(); $em->persist($qPergunta); $em->flush();            
            $sql = "SELECT q.id, q.questionarioId, q.perguntaId, p.categoriaId FROM QuestionarioBundle:QuestionarioPergunta as q INNER JOIN QuestionarioBundle:Pergunta as p WITH p.id = q.perguntaId WHERE q.questionarioId = :questId ORDER BY p.categoriaId";
            $emanager = $this->getDoctrine()->getManager();
            $results = $emanager->createQuery($sql)->setParameter('questId',$questionario->getId())->getResult();
            $perguntas = array();
            if(!empty($results)) {
                foreach ($results as $item) {
                    $pergunta = $this->getDoctrine()->getRepository('QuestionarioBundle:Pergunta')->findById($item['perguntaId']);
                    if (!empty($pergunta)) { $item['perguntaId'] = $pergunta[0]; }
                    $categoria = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioCategoria')->findById($item['categoriaId']);
                    if (!empty($categoria)) { $item['categoriaId'] = $categoria[0]; }
                    $perguntas[] = $item;
                }
            }
            return $this->render('QuestionarioBundle:Questionario:editarQuestionarioAjax.html.twig', array('perguntas' => $perguntas));
        } catch (\Exception $ex) { return new Response('error'); }
    }
    
    public function removerPerguntasQuestionarioAction () {
        $id = $this->getRequest()->request->get('id'); $qId = $this->getRequest()->request->get('questionarioId');
        $arrayPergunta = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioPergunta')->findById($id);
        $pergunta = $arrayPergunta[0];
        try {
            $em = $this->getDoctrine()->getManager(); $em->remove($pergunta); $em->flush();
            $sql = "SELECT q.id, q.questionarioId, q.perguntaId, p.categoriaId FROM QuestionarioBundle:QuestionarioPergunta as q INNER JOIN QuestionarioBundle:Pergunta as p WITH p.id = q.perguntaId WHERE q.questionarioId = :questId ORDER BY p.categoriaId";
            $emanager = $this->getDoctrine()->getManager();
            $results = $emanager->createQuery($sql)->setParameter('questId',$questionario->getId())->getResult();
            $perguntas = array();
            if(!empty($results)) {
                foreach ($results as $item) {
                    $pergunta = $this->getDoctrine()->getRepository('QuestionarioBundle:Pergunta')->findById($item['perguntaId']);
                    if (!empty($pergunta)) { $item['perguntaId'] = $pergunta[0]; }
                    $categoria = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioCategoria')->findById($item['categoriaId']);
                    if (!empty($categoria)) { $item['categoriaId'] = $categoria[0]; }
                    $perguntas[] = $item;
                }
            }
            return $this->render('QuestionarioBundle:Questionario:editarQuestionarioAjax.html.twig', array('perguntas' => $perguntas));
        } catch (\Exception $ex) { return new Response('error'); }
    }
    
    public function quickAddPerguntasQuestionarioAction () {
        $per = $this->getRequest()->request->get('pergunta'); $qId = $this->getRequest()->request->get('questionarioId');
        $cId = $this->getRequest()->request->get('categoria');
        $pergunta = new Pergunta(); $pergunta->setPergunta($per); $pergunta->setTemResposta(false); $pergunta->setCategoriaId($cId);
         try {
            $em = $this->getDoctrine()->getManager(); $em->persist($pergunta); $em->flush();
            $perguntaQuestionario = new QuestionarioPergunta();
            $perguntaQuestionario->setPerguntaId($pergunta->getId());
            $perguntaQuestionario->setQuestionarioId($qId);
            $em2 = $this->getDoctrine()->getManager(); $em2->persist($perguntaQuestionario); $em2->flush();
            $sql = "SELECT q.id, q.questionarioId, q.perguntaId, p.categoriaId FROM QuestionarioBundle:QuestionarioPergunta as q INNER JOIN QuestionarioBundle:Pergunta as p WITH p.id = q.perguntaId WHERE q.questionarioId = :questId ORDER BY p.categoriaId";
            $emanager = $this->getDoctrine()->getManager();
            $results = $emanager->createQuery($sql)->setParameter('questId',$questionario->getId())->getResult();
            $perguntas = array();
            if(!empty($results)) {
                foreach ($results as $item) {
                    $pergunta = $this->getDoctrine()->getRepository('QuestionarioBundle:Pergunta')->findById($item['perguntaId']);
                    if (!empty($pergunta)) { $item['perguntaId'] = $pergunta[0]; }
                    $categoria = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioCategoria')->findById($item['categoriaId']);
                    if (!empty($categoria)) { $item['categoriaId'] = $categoria[0]; }
                    $perguntas[] = $item;
                }
            }
            return $this->render('QuestionarioBundle:Questionario:editarQuestionarioAjax.html.twig', array('perguntas' => $perguntas));
        } catch (\Exception $ex) { return new Response('error'); }
    }

    public function removerQuestionarioAction (Questionario $questionario) {
        $questionario->setAtivo(false);
        try {
            $em = $this->getDoctrine()->getManager(); $em->merge($questionario);$em->flush();
            $this->get('session')->getFlashBag()->set('message', 'Questionário removido com sucesso.');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('questionario_index'));
    }
    
    public function categoriasAction () {
        $categoria = new QuestionarioCategoria(); $errors = '';
        $form = $this->createForm(new CategoriaForm(), $categoria);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager(); $em->persist($categoria); $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Categoria criada com sucesso.');
                return $this->redirect($this->generateUrl('categorias_index'));
            } catch (\Exception $ex) { $this->get('session')->getFlashBag()->set('error', $ex->getMessage()); }
        } else { $errors = $this->get('form_helper')->getFormErrors($form); }
        $categorias = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioCategoria')->findByAtivo(true);
        return $this->render('QuestionarioBundle:Questionario:categorias.html.twig', array('form' => $form->createView(), 'erros' => $errors, 'categorias' => $categorias));
    }
    
    public function editarCategoriasAction (QuestionarioCategoria $categoria) {
        $errors = '';
        $form = $this->createForm(new CategoriaForm(), $categoria);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager(); $em->merge($categoria); $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Categoria criada com sucesso.');
                return $this->redirect($this->generateUrl('categorias_index'));
            } catch (\Exception $ex) { $this->get('session')->getFlashBag()->set('error', $ex->getMessage()); }
        } else { $errors = $this->get('form_helper')->getFormErrors($form); }
        return $this->render('QuestionarioBundle:Questionario:editarCategorias.html.twig', array('form' => $form->createView(), 'erros' => $errors));
    }
    
    public function removerCategoriasAction (QuestionarioCategoria $categoria) {
        $categoria->setAtivo(false);
        try {
            $em = $this->getDoctrine()->getManager(); $em->merge($categoria); $em->flush();
            $this->get('session')->getFlashBag()->set('message', 'Categoria removida com sucesso.');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('categorias_index'));
    }
    
    public function perguntasAction (QuestionarioCategoria $categoria) {
        $pergunta = new Pergunta(); $errors = ''; $resposta = '';
        $pergunta->setCategoriaId($categoria->getId());
        $form = $this->createForm(new PerguntaForm(), $pergunta);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $dados = $form->getData(); $hasResposta = $dados->getTemResposta();
            if (!$hasResposta) { $dados->setMultiResposta(false); $dados->setRespostas(null); }            
            try {
                $em = $this->getDoctrine()->getManager(); $em->persist($dados); $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Pergunta criada com sucesso.');
                return $this->redirect($this->generateUrl('perguntas_index', array('id'=>$categoria->getId())));
            } catch (\Exception $ex) { $this->get('session')->getFlashBag()->set('error', $ex->getMessage()); }
        } else { $errors = $this->get('form_helper')->getFormErrors($form); }
        $perguntas = $this->getDoctrine()->getRepository('QuestionarioBundle:Pergunta')->findBy(array('ativo'=>true, 'categoriaId'=>$categoria->getId()));
        return $this->render('QuestionarioBundle:Questionario:pergunta.html.twig', array('respostas' => $resposta, 'form' => $form->createView(), 'erros' => $errors, 'perguntas' => $perguntas, 'categoria' => $categoria));
    }
    
   /* public function cadastrarPerguntasAction () {
        $pergunta = new Pergunta(); $errors = '';
        $form = $this->createForm(new PerguntaForm(), $pergunta);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $dados = $form->getData(); $hasResposta = $dados->getTemResposta();
            if (!$hasResposta) { $dados->setMultiResposta(false); $dados->setRespostas(null); }            
            try {
                $em = $this->getDoctrine()->getManager(); $em->persist($dados); $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Pergunta criada com sucesso.');
                return $this->redirect($this->generateUrl('perguntas_index'));
            } catch (\Exception $ex) { $this->get('session')->getFlashBag()->set('error', $ex->getMessage()); }
        } else { $errors = $this->get('form_helper')->getFormErrors($form); }
        return $this->render('QuestionarioBundle:Questionario:cadastrarPergunta.html.twig', array('form' => $form->createView(), 'erros' => $errors));
    }*/
    
    public function editarPerguntasAction (Pergunta $pergunta) {
        $errors = ''; $hasResposta = $pergunta->getTemResposta();
        if ($hasResposta) { $respostas = $pergunta->getRespostas(); } else { $respostas = null; }
        $form = $this->createForm(new PerguntaForm(), $pergunta);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $dados = $form->getData(); $hasResposta = $dados->getTemResposta();
            if (!$hasResposta) { $dados->setMultiResposta(false); $dados->setRespostas(null); }
            try {
                $em = $this->getDoctrine()->getManager(); $em->merge($dados); $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Pergunta modificada com sucesso.');
                return $this->redirect($this->generateUrl('perguntas_index', array('id'=>$pergunta->getCategoriaId())));
            } catch (\Exception $ex) { $this->get('session')->getFlashBag()->set('error', $ex->getMessage()); }
        } else { $errors = $this->get('form_helper')->getFormErrors($form); }
        return $this->render('QuestionarioBundle:Questionario:editarPergunta.html.twig', array('respostas' => $respostas,'form' => $form->createView(), 'erros' => $errors, 'pergunta' => $pergunta));
    }
    
    public function removerPerguntasAction (Pergunta $pergunta) {
        $pergunta->setAtivo(false);
        try {
            $em = $this->getDoctrine()->getManager(); $em->merge($pergunta); $em->flush();
            $this->get('session')->getFlashBag()->set('message', 'Pergunta removida com sucesso.');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('perguntas_index', array('id'=>$pergunta->getCategoriaId())));
    }
    
    public function buscaPerguntasAction () {
        $categoria = $this->getRequest()->request->get('categoria');
        if (!empty($categoria)) {
            $results = $this->getDoctrine()->getRepository('QuestionarioBundle:Pergunta')->findBy(array('categoriaId' => $categoria, 'ativo' => true));
            $i = 0; $length = count($results); $jsonReturn = '[';
            foreach ($results as $result) {
                $jsonReturn .= '{ "id": ' . $result->getId() . ', ';
                $jsonReturn .= '"pergunta": "' . $result->getPergunta() . '" }';
                if ($i != $length - 1) { $jsonReturn .= ','; } $i++;
            }
            $jsonReturn .= ']';
            if (!empty($results)) { return new JsonResponse($jsonReturn); } else { return new JsonResponse('no_results'); }
        }
    }

    public function questionariosListadosAction () {
        $pessoa = $this->get('cadastro_unico')->createByUser($this->getUser());
        $vinculos = $this->getDoctrine()->getRepository('DGPBundle:Vinculo')->findBy(array('servidor' => $pessoa, 'ativo' => true, 'tipoVinculo' => 3));
        $msgRetorno = null;
        if (!empty($vinculos)) {
            $vin = null;
            foreach ($vinculos as $vinculo) {
                $aloc = $vinculo->getAlocacoes()->filter( function($a) { return $a->getAtivo(); } );
                if (count($aloc) > 0) { $vin = $vinculo; }
            }
            if (is_object($vin)) {
                $alocacoes = $vin->getAlocacoes()->filter( function($a) { return $a->getAtivo(); } );
                $al = null;
                foreach ($alocacoes as $alocacao) {
                    if (is_object($alocacao)) { $al = $alocacao; }
                }
                if (is_object($al)) {
                    $pessoaJuridica = $al->getLocalTrabalho()->getPessoaJuridica()->getId();
                    $questionarios = $this->getDoctrine()->getRepository('QuestionarioBundle:Questionario')->findBy(array('ativo' => true));
                    $questionariosHabilitados = array();
                    $questionariosRespondidos = array();
                    foreach ($questionarios as $questionario) {
                        $respostasMinhas = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioRespondido')->findBy(array('questionarioId' => $questionario->getId(), 'respondidoPor' => $pessoa->getId()));
                        if (empty($respostasMinhas)) {
                            $respostasEscola = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioRespondido')->findBy(array('questionarioId' => $questionario->getId(), 'vinculoPessoa' => $pessoaJuridica));
                            if (empty($respostasEscola)) { $questionariosHabilitados[] = $questionario; } else { $questionariosRespondidos[] = $questionario; }
                        } else { $questionariosRespondidos[] = $questionario; }
                    }
                } else {
                    $questionariosHabilitados = array();
                    $questionariosRespondidos = array();
                    $msgRetorno = 'Houve algum problema ao verificar e carregar suas alocações, por isso a tela de Questionários não será exibida, favor entrar em contato com o DGP para verificar seu cadastro.';
                }
            } else {
                $questionariosHabilitados = array();
                $questionariosRespondidos = array();
                $msgRetorno = 'Aparentemente seu vínculo no sistema não possui nenhuma alocação cadastrada, por isso a tela de Questionários não será exibida, favor entrar em contato com o DGP para verificar seu cadastro.';
            }
        } else {
            $questionariosHabilitados = array();
            $questionariosRespondidos = array();
            $msgRetorno = 'Não foi encontrado nenhum vínculo com esta permissão no sistema, por isso a tela de Questionários não será exibida, favor entrar em contato com o DGP para verificar seu cadastro.';
        }
        return $this->render('QuestionarioBundle:Questionario:listarQuestionarios.html.twig', array('respondidos' => $questionariosRespondidos,'questionarios' => $questionariosHabilitados,'msgRetorno' => $msgRetorno));
    }
    
    public function questionarioResponderAction (Questionario $questionario) {
        $pessoa = $this->get('cadastro_unico')->createByUser($this->getUser());
        $respostas = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioRespondido')->findBy(array('questionarioId' => $questionario->getId(), 'respondidoPor' => $pessoa->getId()));
        $i = 0; $length = count($respostas); $jsonReturn = '[';
        foreach ($respostas as $resposta) {
            $jsonReturn .= '{ "perguntaId": ' . $resposta->getPerguntaId() . ', ';
            $jsonReturn .= '"resposta": "' . $resposta->getResposta() . '" }';
            if ($i != $length - 1) { $jsonReturn .= ','; } $i++;
        }
        $jsonReturn .= ']';
        
        $sql = "SELECT q.id, q.questionarioId, q.perguntaId, p.categoriaId FROM QuestionarioBundle:QuestionarioPergunta as q INNER JOIN QuestionarioBundle:Pergunta as p WITH p.id = q.perguntaId WHERE q.questionarioId = :questId ORDER BY p.categoriaId";
        $emanager = $this->getDoctrine()->getManager();
        $results = $emanager->createQuery($sql)->setParameter('questId',$questionario->getId())->getResult();
        $perguntas = array();
        if(!empty($results)) {
            foreach ($results as $item) {
                $pergunta = $this->getDoctrine()->getRepository('QuestionarioBundle:Pergunta')->findById($item['perguntaId']);
                if (!empty($pergunta)) { $item['perguntaId'] = $pergunta[0]; }
                $categoria = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioCategoria')->findById($item['categoriaId']);
                if (!empty($categoria)) { $item['categoriaId'] = $categoria[0]; }
                $perguntas[] = $item;
            }
        }
        return $this->render('QuestionarioBundle:Questionario:responderQuestionario.html.twig', array('respostas' => $jsonReturn,'questionario' => $questionario, 'perguntas' => $perguntas));
    }
    
    public function questionarioSalvarRespostasAction (Questionario $questionario) {
        $perguntas = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioPergunta')->findBy(array('questionarioId'=>$questionario->getId()));
        $checks = $this->getRequest()->request->get('respostaQuestionario');
        $pessoa = $this->get('cadastro_unico')->createByUser($this->getUser());
        $vinculos = $this->getDoctrine()->getRepository('DGPBundle:Vinculo')->findBy(array('servidor' => $pessoa, 'ativo' => true, 'tipoVinculo' => 3));
        if (!empty($vinculos)) { 
            $vin = null;
            foreach ($vinculos as $vinculo) {
                $aloc = $vinculo->getAlocacoes()->filter( function($a) { return $a->getAtivo(); } );
                if (!empty($aloc)) { $vin = $vinculo; }
            }
            $alocacoes = $vin->getAlocacoes()->filter( function($a) { return $a->getAtivo(); } );
            $al = null;
            foreach ($alocacoes as $alocacao) {
                if (is_object($alocacao)) { $al = $alocacao; }
            }
            if ($al) {
                $pessoaJuridica = $al->getLocalTrabalho()->getPessoaJuridica()->getId();
            } else {
                $pessoaJuridica = null;
            }
        } else { $pessoaJuridica = null; }
        foreach ($perguntas as $pergunta) {
            $qRespondido = new QuestionarioRespondido();
            $qRespondido->setPerguntaId($pergunta->getPerguntaId());
            $qRespondido->setQuestionarioId($questionario->getId());
            $qRespondido->setRespondidoPor($pessoa->getId());
            $qRespondido->setVinculoPessoa($pessoaJuridica);
            if (in_array($pergunta->getPerguntaId(), $checks)) { $qRespondido->setResposta(true); } else { $qRespondido->setResposta(false); }            
            try {
                $em = $this->getDoctrine()->getManager(); $em->persist($qRespondido); $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Questionário respondido com sucesso.');
            } catch (\Exception $ex) { $this->get('session')->getFlashBag()->set('error', $ex->getMessage()); }
        }
        return $this->redirect($this->generateUrl('questionarios_listados'));
    }
    
    public function respostasPorQuestionarioAction () {
        $questionarios = $this->getDoctrine()->getRepository('QuestionarioBundle:Questionario')->findBy(array('ativo' => true));
        return $this->render('QuestionarioBundle:Questionario:listaRespondidos.html.twig', array('questionarios' => $questionarios));
    }
    
    public function buscarRespostasAction () {
        $questionario = $this->getRequest()->request->get('questionario');
        $usuarios = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioRespondido')->createQueryBuilder('q')
            ->select('q.questionarioId, q.respondidoPor, q.respondidoEm')
            ->where('q.questionarioId = :questionario')
            ->groupBy('q.respondidoPor')
            ->setParameter('questionario',$questionario)
            ->getQuery()->getResult();
        
        $i = 0; $length = count($usuarios); $jsonReturn = '[';
        foreach ($usuarios as $usuario) {
            $jsonReturn .= '{ "questionarioId": ' . $usuario['questionarioId'] . ', ';
            $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:PessoaFisica')->findBy(array('id' => $usuario['respondidoPor']));
            $jsonReturn .= '"nomePessoa": "' . $pessoa[0]->getNome() . '", ';
            $jsonReturn .= '"idPessoa": "' . $pessoa[0]->getId() . '", ';
            $jsonReturn .= '"dataResposta": "' . \date('d/m/Y H:i:s', strtotime($usuario['respondidoEm'])) . '" }';
            if ($i != $length - 1) { $jsonReturn .= ','; } $i++;
        }
        $jsonReturn .= ']';
        return new JsonResponse($jsonReturn);
    }
    
    public function mostrarRespostasAction ($id, $userId) {
        $questionario = $this->getDoctrine()->getRepository('QuestionarioBundle:Questionario')->findById($id);
        $pessoa = $this->getDoctrine()->getRepository('CommonsBundle:PessoaFisica')->findById($userId);
        $sql = "SELECT q.id, q.questionarioId, q.perguntaId, p.categoriaId FROM QuestionarioBundle:QuestionarioPergunta as q INNER JOIN QuestionarioBundle:Pergunta as p WITH p.id = q.perguntaId WHERE q.questionarioId = :questId ORDER BY p.categoriaId";
        $emanager = $this->getDoctrine()->getManager();
        $results = $emanager->createQuery($sql)->setParameter('questId',$questionario[0]->getId())->getResult();
        $perguntas = array();
        if(!empty($results)) {
            foreach ($results as $item) {
                $pergunta = $this->getDoctrine()->getRepository('QuestionarioBundle:Pergunta')->findById($item['perguntaId']);
                if (!empty($pergunta)) { $item['perguntaId'] = $pergunta[0]; }
                $categoria = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioCategoria')->findById($item['categoriaId']);
                if (!empty($categoria)) { $item['categoriaId'] = $categoria[0]; }
                $perguntas[] = $item;
            }
        }
        
        $respostas = $this->getDoctrine()->getRepository('QuestionarioBundle:QuestionarioRespondido')->findBy(array('questionarioId' => $questionario[0]->getId(), 'respondidoPor'=>$pessoa[0]->getId()));
        $i = 0; $length = count($respostas); $jsonReturn = '[';
        foreach ($respostas as $resposta) {
            $jsonReturn .= '{ "perguntaId": ' . $resposta->getPerguntaId() . ', ';
            $jsonReturn .= '"resposta": "' . $resposta->getResposta() . '" }';
            if ($i != $length - 1) { $jsonReturn .= ','; } $i++;
        }
        $jsonReturn .= ']';
        
        return $this->render('QuestionarioBundle:Questionario:respondidoQuestionario.html.twig', array('respostas' => $jsonReturn, 'pessoa' => $pessoa[0], 'questionario' => $questionario[0], 'perguntas' => $perguntas));
    }
    
    public function categoriasAdicionarAction () {
        $nome = $this->getRequest()->request->get('nome');
        $categoria = new QuestionarioCategoria();
        $categoria->setDescricao('');
        $categoria->setNome($nome);
        try {
            $em = $this->getDoctrine()->getManager(); $em->persist($categoria); $em->flush();
            return new Response('success');
        } catch (\Exception $ex) { return new Response('error'); }
    }
}