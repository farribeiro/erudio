<?php

namespace SME\DGPFormacaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use SME\CommonsBundle\Entity\PessoaFisica;
use SME\DGPFormacaoBundle\Entity\Formacao;
use SME\DGPFormacaoBundle\Entity\Matricula;

class MatriculaController extends Controller {

    const LIMITE_PAGINA = 100;
    
    function listarPorServidorAction() {
        $formacoesPessoa = $this->getDoctrine()->getRepository('DGPFormacaoBundle:Matricula')->findBy(
            array('pessoa' => $this->getUser()->getPessoa(), 'ativo' => true)
        );
        $now = new \DateTime();
        $formacoes = $this->getDoctrine()->getRepository('DGPFormacaoBundle:Formacao')->createQueryBuilder('f')
            ->where('f.ativo = true')->andWhere(':dataAtual BETWEEN f.dataInicioInscricao AND f.dataTerminoInscricao')
            ->setParameter('dataAtual', $now->format('Y-m-d H:i:s'))->getQuery()->getResult();
        foreach ($formacoesPessoa as $matricula) {
            foreach ($formacoes as $y => $formacao) {
                if ($matricula->getFormacao()->getId() == $formacao->getId()) {
                    unset($formacoes[$y]);
                }
            }
        }
        return $this->render('DGPFormacaoBundle:Matricula:matriculasServidor.html.twig', array(
            'formacoes' => $formacoes, 
            'formacoesPessoa' => $formacoesPessoa
        ));
    }
    
    function listarPorFormacaoAction(Formacao $formacao, $page) {
        $matriculas = $this->loadMatriculasByPage($formacao, $page);
        $totalMatriculas = count($formacao->getMatriculas());
        $totalPaginas = ceil($totalMatriculas / self::LIMITE_PAGINA);
        return $this->render('DGPFormacaoBundle:Matricula:matriculasFormacao.html.twig', array('formacao' => $formacao, 'matriculas' => $matriculas, 'totalPaginas' => $totalPaginas, 'page' => $page));
    }
    
    private function loadMatriculasByPage($formacao, $page) {
        return $this->getDoctrine()->getRepository('DGPFormacaoBundle:Matricula')
            ->createQueryBuilder('m')
            ->join('m.formacao', 'f')
            ->join('m.pessoa', 'p')
            ->where('m.ativo = true')
            ->andWhere('f.id = :formacao')
            ->setParameter('formacao', $formacao->getId())
            ->setMaxResults(self::LIMITE_PAGINA)->orderBy('p.nome', 'ASC')
            ->setFirstResult(($page - 1) * self::LIMITE_PAGINA)
            ->getQuery()->getResult();
    }
    
    function cadastrarAction(Formacao $formacao, PessoaFisica $pessoa) {     
        try {
            if($formacao->getVagasDisponiveis() <= 0) {
                throw new \Exception('Infelizmente todas as vagas para esta formação já foram preenchidas');
            }
            if(!$formacao->getAberto() && (!$this->getUser() || $this->getUser()->getPessoa()->getVinculosTrabalho()->isEmpty())) {
                throw new \Exception('Esta formação só está disponível para os servidores da Educação');
            }
            $matricula = $this->getDoctrine()->getRepository('DGPFormacaoBundle:Matricula')->findOneBy(array(
                'formacao' => $formacao, 'pessoa' => $pessoa
            ));
            if ($matricula && $matricula->getAtivo()) {
                $msg = 'Você já está inscrito nesta formação';
                throw new \Exception($msg);
            } elseif($matricula) {
                $matricula->setAtivo(true);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message', 'Inscrição reativada com sucesso');
            } else {
                $matricula = new Matricula();
                $matricula->setFormacao($formacao);
                $matricula->setPessoa($pessoa);
                $matricula->setAprovado(false);
                $matricula->setDataCadastro(new \DateTime());
                $this->getDoctrine()->getManager()->persist($matricula);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->set('message', 'Inscrição realizada com sucesso');
            }
        } catch (\Exception $e) {
            if($this->getUser()) {
                $this->get('session')->getFlashBag()->set('error', $e->getMessage());
            } else {
                return new JsonResponse($e->getMessage());
            }
        }
        return $this->redirect($this->generateUrl('dgp_servidor_listarFormacoes'));
    }

    function cadastrarPorCpfAction(Formacao $formacao) {
        $pessoa = $this->get('cadastro_unico')->createByCpf($this->getRequest()->request->get('cpf'));
        if($pessoa->getId()) {
            $r = $this->forward('DGPFormacaoBundle:Matricula:cadastrar', array(
                'formacao' => $formacao->getId(), 'pessoa' => $pessoa->getId()
            ));
        } else {
            $this->get('session')->getFlashBag()->set('error', 'Não foi encontrado um cadastro existente para este CPF');
        }
        return $this->redirect($this->generateUrl('dgp_formacao_listarMatriculas', array('formacao' => $formacao->getId())));
    }
    
    function cancelarAction(Matricula $matricula) {
        if($this->getUser()->getPessoa()->getId() != $matricula->getPessoa()->getId()) {
            throw new \Exception('Você não possui autorização para excluir a inscrição de outra pessoa');
        }
        try {
            $now = new \DateTime();
            if($matricula->getFormacao()->getDataTerminoInscricao()->getTimestamp() - $now->getTimestamp() <= 0) {
                throw new \Exception('O período de inscrições encerrou-se, sua inscrição não pode mais ser cancelada');
            }
            $matricula->setAtivo(false);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'Matrícula cancelada com sucesso');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->set('error', $e->getMessage());
        }
        return $this->redirect($this->generateUrl('dgp_servidor_listarFormacoes'));
    }
    
    function imprimirCertificadoAction(Matricula $matricula) {
        if($matricula->getAprovado() && $matricula->getFormacao()->getPublicado()) {
            $modelo = '\\SME\\DGPFormacaoBundle\\Report\\Certificado\\' 
                . $matricula->getFormacao()->getModeloCertificado();
            $certificado = new $modelo();
            $certificado->setMatricula($matricula);
            return $this->get('pdf')->render($certificado);
        } else {
            $msg = $matricula->getAprovado() 
                    ? 'Você só possui acesso aos próprios certificados' 
                    : 'O servidor não concluiu esta formação ou o certificado ainda não foi publicado';
            throw new \Exception($msg);
        }
    }
    
    function atualizarTodasAction(Formacao $formacao, $page) {
        $post = $this->getRequest()->request;
        $matriculas = $this->loadMatriculasByPage($formacao, $page);
        try {
            foreach($matriculas as $matricula) {
                $matricula->setAprovado(
                    $post->get('aprovado') && is_numeric(array_search($matricula->getId(), $post->get('aprovado')))
                );
                $this->getDoctrine()->getManager()->merge($matricula);
            }
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'Matrículas atualizadas');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('dgp_formacao_listarMatriculas', array('formacao' => $formacao->getId(), 'page' => $page)));
    }
    
    function excluirTodasAction(Formacao $formacao, $page) {
        $post = $this->getRequest()->request;
        try {
            foreach($post->get('selecionados') as $id) {
                $matricula = $this->getDoctrine()->getRepository('DGPFormacaoBundle:Matricula')->find($id);
                $matricula->setAtivo(false);
                $this->getDoctrine()->getManager()->merge($matricula);
            }
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'Matrículas excluídas');
        } catch(\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('dgp_formacao_listarMatriculas', array('formacao' => $formacao->getId(), 'page' => $page)));
    }
    
}
