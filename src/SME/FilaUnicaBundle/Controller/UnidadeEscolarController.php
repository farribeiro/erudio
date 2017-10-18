<?php

namespace SME\FilaUnicaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use SME\FilaUnicaBundle\Entity\UnidadeEscolar;

class UnidadeEscolarController extends Controller {
    
    public function listarAction() {
        $renderParams = array();
        $renderParams['unidades'] = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('u')->from('FilaUnicaBundle:UnidadeEscolar', 'u')
                ->join('u.zoneamento', 'z')->join('u.entidade', 'e')->join('e.pessoaJuridica', 'p')
                ->where('u.ativo = true')->orderBy('z.nome','ASC')->getQuery()->getResult();
        return $this->render('FilaUnicaBundle:UnidadeEscolar:listaUnidades.html.twig', $renderParams);
    }
    
    /**
        * @PreAuthorize("hasRole('ROLE_SUPER_ADMIN')")
        */
    public function formAlteracaoAction(UnidadeEscolar $unidade) {
        return $this->render('FilaUnicaBundle:UnidadeEscolar:modalFormAlteracao.html.twig', array(
            'unidade' => $unidade,
            'zoneamentos' => $this->getDoctrine()->getRepository('FilaUnicaBundle:Zoneamento')->findAll()
        ));
    }
    
    /**
        * @PreAuthorize("hasRole('ROLE_SUPER_ADMIN')")
        */
    public function alterarAction(UnidadeEscolar $unidade) {
        try {
            $zoneamento = $this->getDoctrine()->getRepository('FilaUnicaBundle:Zoneamento')->find($this->getRequest()->request->get('zoneamento'));
            $unidade->setZoneamento($zoneamento);
            $this->getDoctrine()->getManager()->merge($unidade);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->set('message', 'Cadastro atualizado com sucesso');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('fu_unidade_escolar_listar'));
    }
    
}
