<?php

namespace SME\DGPFormacaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use SME\DGPFormacaoBundle\Entity\Formacao;

class PublicController extends Controller {
    
    public function getFormacoesAction() {
        $now = new \DateTime();
        $formacoes = $this->getDoctrine()->getRepository('DGPFormacaoBundle:Formacao')->createQueryBuilder('f')
            ->where('f.ativo = true')->andWhere('f.aberto = true')->andWhere('f.publicado = true')
            ->andWhere(':dataAtual BETWEEN f.dataInicioInscricao AND f.dataTerminoInscricao')
            ->setParameter('dataAtual', $now->format('Y-m-d H:i:s'))->getQuery()->getResult();
        return new JsonResponse($formacoes);
    }
    
    public function getFormacaoAction(Formacao $formacao) {
        return $this->render('PublicBundle:Public:modalFormacao.html.twig', array('formacao' => $formacao));
    }
    
    public function postMatriculaAction(Formacao $formacao) {
        try {
            $json = json_decode($this->getRequest()->getContent());
            $pessoa = $this->get('cadastro_unico')->createByCpf($json->cpf);
            if(!$pessoa->getId()) {
                $pessoa->setNome($json->nome);
                $pessoa->setCpfCnpj($json->cpf);
                $pessoa->setEmail($json->email);
                $pessoa->setProfissao($json->profissao);
                $this->get('cadastro_unico')->retain($pessoa);
            }
            $r = $this->forward('DGPFormacaoBundle:Matricula:cadastrar', array(
                'formacao' => $formacao->getId(), 'pessoa' => $pessoa->getId()
            ));
            if($r instanceof JsonResponse) {
                throw new \Exception(json_decode($r->getContent()));
            }
            return new JsonResponse(array('result' => 'success', 'message' => 'Inscrição realizada'));
        } catch(\Exception $ex) {
            return new JsonResponse(array('result' => 'error', 'message' => $ex->getMessage()));
        }
    }
    
}
