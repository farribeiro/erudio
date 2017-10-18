<?php

namespace SME\DGPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\DGPBundle\Forms\Servidor\VinculoSimpleType;
use SME\DGPBundle\Entity\Vinculo;
use SME\DGPBundle\Entity\TipoVinculo;

/**
 * Controles de ações do servidor sobre seus próprios vínculos.
 */
class ServidorController extends Controller {
    
    public function listarVinculosAction() {
        $pessoaFisica = $this->get('cadastro_unico')->createByUser($this->getUser());
        $renderParams['vinculos'] = $this->getDoctrine()->getRepository('DGPBundle:Vinculo')->findBy(
            array('servidor' => $pessoaFisica, 'ativo' => true)
        );
        return $this->render('DGPBundle:Servidor:index.html.twig', $renderParams);
    }
    
    public function consultarVinculoAction(Vinculo $vinculo) {
        if(!$vinculo->getServidor()->getUsuario()->equals($this->getUser())) {
            throw new \Exception('Você só possui acesso aos seus próprios vínculos');
        }
        return $this->render('DGPBundle:Servidor:consultaVinculo.html.twig', array('vinculo' => $vinculo));
    }

}
