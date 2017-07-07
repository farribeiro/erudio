<?php

namespace SME\ProtocoloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use SME\ProtocoloBundle\Reports\RequerimentoReportFactory;
use SME\ProtocoloBundle\Entity\Solicitacao;
use SME\ProtocoloBundle\Entity\Protocolo;
use SME\ProtocoloBundle\Entity\InformacaoDocumento;

class PublicController extends Controller
{
    public function getRequerimentosAction() {
        $requerimentos = $this->getDoctrine()->getRepository('ProtocoloBundle:Solicitacao')->findBy(
            array('externo' => true), array('nome' => 'ASC')
        );
        return new JsonResponse($requerimentos);
    }
    
    public function postProtocoloAction(Solicitacao $solicitacao) {
        $post = $this->getRequest()->request;
        try {            
            $pessoa = $this->get('cadastro_unico')->createByCpf($post->get('cpf'));
            /* if($pessoa->getUsuario()) {
                            throw new \Exception('Este requerimento é apenas para pessoas que não possuem vínculo atual com a Secretaria');
                        } */
            $pessoa->setCpfCnpj($post->get('cpf'));
            $pessoa->setNome($post->get('nome'));
            $pessoa->setNumeroRg($post->get('rg'));
            $pessoa->setEmail($post->get('email'));
            $pessoa->getTelefone()->setNumero($post->get('telefone'));
            $pessoa->getCelular()->setNumero($post->get('celular'));
            $this->get('cadastro_unico')->retain($pessoa);
            $protocolo = new Protocolo();
            $protocolo->setRequerente($pessoa);
            $protocolo->setSolicitacao($solicitacao);
            $protocolo->setSituacao($solicitacao->getSituacaoInicial());
            foreach($post as $id=>$val) {
                $info = new InformacaoDocumento();
                $info->setProtocolo($protocolo);
                $info->setNomeCampo($id);
                $info->setValor($val);
                $protocolo->getInformacoesDocumento()->add($info);
            }
            $protocolo->setDataCadastro(new \DateTime());
            $protocolo->setAtivo(true);
            $this->getDoctrine()->getManager()->persist($protocolo);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(array('success' => true, 'protocolo' => $protocolo->getProtocolo(), 'id' => $protocolo->getId()));
        } catch (\Exception $ex) {
            return new JsonResponse(array('success' => false, 'error' => $ex->getMessage()));
        }
    }
    
    public function getDocumentoAction(Protocolo $protocolo) {
        if($protocolo->getSolicitacao()->getExterno()) {
            $reportFactory = new RequerimentoReportFactory();
            $pdf = $reportFactory->makeRequerimentoReport($protocolo);
            return $pdf->render();
        }
        else {
            throw new \Exception('Acesso não autorizado');
        }
    }
    
}
