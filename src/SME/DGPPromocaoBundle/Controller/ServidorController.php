<?php

namespace SME\DGPPromocaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\DGPBundle\Entity\Vinculo;

class ServidorController extends Controller {
    
    public function listarAction(Vinculo $vinculo) {
        if($vinculo->getServidor()->getId() != $this->getUser()->getPessoa()->getId()) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Você não pode solicitar promoções para outro servidor');
        }
        $promocoesHorizontais = $this->getDoctrine()->getRepository('DGPPromocaoBundle:PromocaoHorizontal')->findBy(array('vinculo' => $vinculo), array('dataCadastro' => 'DESC'));
        $promocoesVerticais = $this->getDoctrine()->getRepository('DGPPromocaoBundle:PromocaoVertical')->findBy(array('vinculo' => $vinculo), array('dataCadastro' => 'DESC'));
        return $this->render('DGPPromocaoBundle:Servidor:promocoes.html.twig', array(
            'vinculo' => $vinculo, 
            'promocoesHorizontais' => $promocoesHorizontais,
            'promocoesVerticais' => $promocoesVerticais
        ));
    }
    
}
