<?php

namespace SME\PublicacaoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use SME\PublicacaoBundle\Entity\Grupo;
use SME\PublicacaoBundle\Entity\Categoria;
use SME\PublicacaoBundle\Entity\Visibilidade;
use SME\PublicacaoBundle\Entity\Documento;

class PublicController extends Controller {
    
    public function getGruposAction() {
        $grupos = $this->getDoctrine()->getRepository('PublicacaoBundle:Grupo')->findBy(
            array('ativo' => true)
        );
        return $this->render('PublicacaoBundle:Public:publicacaoRaiz.html.twig', array('grupos' => $grupos));
    }
    
    public function getCategoriasAction(Grupo $grupo, Request $request) {
        $publico = $this->getDoctrine()->getRepository('PublicacaoBundle:Visibilidade')->find(Visibilidade::PUBLICO);
        $categoriaPai = $request->query->has('categoriaPai') 
            ? $this->getDoctrine()->getRepository('PublicacaoBundle:Categoria')->find($request->query->getInt('categoriaPai'))
            : null;
        //var_dump($categoriaPai); die;
        $categorias = $this->getDoctrine()->getRepository('PublicacaoBundle:Categoria')->findBy(
            array('ativo' => true, 'categoria' => $categoriaPai, 'grupo' => $grupo)
        );
        $documentos = $this->getDoctrine()->getRepository('PublicacaoBundle:Documento')->findBy(
            array('ativo' => true, 'categoria' => $categoriaPai, 'grupo' => $grupo, 'visibilidade' => $publico)
        );
        return $this->render('PublicacaoBundle:Public:publicacaoPasta.html.twig', array('grupo' => $grupo, 'categorias' => $categorias, 'documentos' => $documentos, 'categoriaPai' => $categoriaPai));
    }
    
    public function getDocumentoAction(Documento $documento) {
        if($documento->getVisibilidade()->getId() != Visibilidade::PUBLICO) {
            throw new \Exception('Acesso não autorizado');
        }
        $caminho = '';
        $grupo = $documento->getGrupo();
        if ($grupo !== null) {
            $caminho = $this->getCaminho($grupo);
        } else {
            $caminho = $this->getCaminho($documento->getCategoria());
        }
        $caminho .= '/' . $documento->getArquivo();
        if (!file_exists($caminho)) {
            throw $this->createNotFoundException();
        }
        $response = new BinaryFileResponse($caminho);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE, 
            $documento->getArquivo(), 
            iconv('UTF-8', 'ASCII//TRANSLIT', $documento->getArquivo())
        );
        return $response;
    }
    
    private function getCaminho($objeto, $nomeAntigo = null) {
        $file_config = $this->container->getParameter('file_store');
        $caminho = $file_config['path'];
        
        if (substr_count(get_class($objeto), "SME\PublicacaoBundle\Entity\Grupo")) {
            if ($nomeAntigo !== null) {
                $caminho .= $nomeAntigo;
            } else {
                $caminho .= $objeto->getNome();
            }
        } else {
            $grupo = $objeto->getGrupo();
            if ($grupo !== null) {
                if ($nomeAntigo !== null) { 
                    $caminho .= $grupo->getNome() . '/' . $nomeAntigo;
                } else {
                    $caminho .= $grupo->getNome() . '/' . $objeto->getNome();
                }
            } else {
                $categoria = $objeto->getCategoria();
                $caminho_subcategorias = '';
                if ($nomeAntigo !== null) {
                    $caminho_subcategorias = $categoria->getNome() . '/' . $nomeAntigo;
                } else {
                    $caminho_subcategorias = $categoria->getNome() . '/' . $objeto->getNome();
                }
                while ($categoria->getCategoria() !== null) {
                    $categoria = $categoria->getCategoria();
                    $caminho_subcategorias = $categoria->getNome() . '/' . $caminho_subcategorias;
                }
                $grupo = $categoria->getGrupo();
                $caminho_subcategorias = $grupo->getNome() . '/' . $caminho_subcategorias;
                $caminho .= $caminho_subcategorias;
            }
        }
        return $caminho;
    }
    
}
