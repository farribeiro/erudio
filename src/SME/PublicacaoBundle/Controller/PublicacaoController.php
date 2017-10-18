<?php

namespace SME\PublicacaoBundle\Controller;

use SME\PublicacaoBundle\Entity\Grupo;
use SME\PublicacaoBundle\Entity\Categoria;
use SME\PublicacaoBundle\Entity\Documento;
use SME\PublicacaoBundle\Forms\GrupoForm;
use SME\PublicacaoBundle\Forms\CategoriaForm;
use SME\PublicacaoBundle\Forms\DocumentoForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class PublicacaoController extends Controller {
    
    public function indexAction () {
        return $this->render('PublicacaoBundle:Index:menu.html.twig');
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
    
    
    public function getBreadcrumb($categoria) {
        $categorias = array($categoria);
        while ($categoria->getCategoria() !== null) {
            $categoria = $categoria->getCategoria();
            $categorias[] = $categoria;
        }
        return $categorias;
    }
    
    public function stripString($nome) {
        return preg_replace("/[^a-zA-Z0-9._]/", "", strtr($nome, "áàãâéêíóôõúüçñÁÀÃÂÉÊÍÓÔÕÚÜÇÑ ", "aaaaeeiooouucnAAAAEEIOOOUUCN_"));
    }
    
     public function stripFileName($nome) {
        return preg_replace("/[^a-zA-Z0-9._]/", "", strtr($nome, "áàãâéêíóôõúüçñÁÀÃÂÉÊÍÓÔÕÚÜÇÑ ", "aaaaeeiooouucnAAAAEEIOOOUUCN_"));
    }
    
    /*
     * Grupos
     */
    
    public function listarGruposAction () {        
        $grupo = new Grupo();
        $errors = '';
        $form = $this->createForm(new GrupoForm(), $grupo);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $dados = $form->getData();
            $grupo->setNome($this->stripString($dados->getNomeExibicao()));
            $grupos = $this->getDoctrine()->getRepository('PublicacaoBundle:Grupo')->findBy(array('nome' => $dados->getNome(), 'ativo' => true));
            $temGrupo = $this->existeGrupo($grupos, $dados->getNome());
            
            if ($temGrupo) {
                $errors = '<div class="panel panel-danger" style="padding: 7px; width: 95%; margin-left: 2.5%; margin-bottom: 1%; margin-top: 2%;"><div class="panel-heading">Por favor, verifique os itens abaixo:</div><div class="panel-body"><ul style="font-size: 12px;"><li>Já existe um grupo com este nome, por favor, digite outro nome.</li></ul></div></div>';
            } else {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($grupo);
                    $em->flush();
                    $caminho = $this->getCaminho($grupo);
                    \mkdir($caminho, 0777);
                    $grupo = new Grupo();
                    $this->get('session')->getFlashBag()->set('message', 'Grupo criado com sucesso');
                    return $this->redirect($this->generateUrl('publicacao_grupos'));
                } catch (\Exception $ex) {
                    $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
                }
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
        
        $grupos = $this->getDoctrine()->getRepository('PublicacaoBundle:Grupo')->findBy(array('ativo' => true));
        return $this->render('PublicacaoBundle:Grupo:listar.html.twig', array('grupos' => $grupos, 'form' => $form->createView(), 'erros' => $errors));
    }
    
    public function existeGrupo ($grupos, $nome) {
        if (count($grupos > 0)) {
            foreach ($grupos as $grupo) {
                if ($grupo->getNome() === $nome) {
                    return true;
                }
            }
        }
        return false;
    }
    
    public function excluirGruposAction(Grupo $grupo) {
        try {
            $grupo->setAtivo(false);
            $this->getDoctrine()->getManager()->merge($grupo);
            $this->getDoctrine()->getManager()->flush();
            $caminho = $this->getCaminho($grupo);
            \rename($caminho, $caminho . '_' . date('dmYHis'));
            $this->get('session')->getFlashBag()->set('message', 'Grupo removido com sucesso');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('publicacao_grupos'));
    }
    
    public function editarGruposAction (Grupo $grupo) {
        $grupoAntigo = $grupo->getNome();
        $errors = '';
        $form = $this->createForm(new GrupoForm(), $grupo);
        $form->handleRequest($this->getRequest());
        
        if ($form->isValid()) {
            $dados = $form->getData();
            $grupo->setNome($this->stripString($dados->getNomeExibicao()));
            $grupos = $this->getDoctrine()->getRepository('PublicacaoBundle:Grupo')->findBy(array('nome' => $dados->getNome(), 'ativo' => true));
            $temGrupo = $this->existeGrupo($grupos, $dados->getNome());
            
            if ($temGrupo) {
                $errors = '<div class="panel panel-danger" style="padding: 7px; width: 95%; margin-left: 2.5%; margin-bottom: 1%; margin-top: 2%;"><div class="panel-heading">Por favor, verifique os itens abaixo:</div><div class="panel-body"><ul style="font-size: 12px;"><li>Já existe um grupo com este nome, por favor, digite outro nome.</li></ul></div></div>';
            } else {
                try {
                    $this->getDoctrine()->getManager()->merge($grupo);
                    $this->getDoctrine()->getManager()->flush();
                    $caminho = $this->getCaminho($grupo);
                    $caminhoAntigo = $this->getCaminho($grupo, $grupoAntigo);
                    \rename($caminhoAntigo, $caminho);
                    $this->get('session')->getFlashBag()->set('message', 'Grupo alterado com sucesso');
                    return $this->redirect($this->generateUrl('publicacao_grupos'));
                } catch (\Exception $ex) {
                    $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
                }
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
        
        return $this->render('PublicacaoBundle:Grupo:editar.html.twig', array('grupo' => $grupo, 'form' => $form->createView(), 'erros' => $errors));
    }
    
    public function listarGruposCategoriasAction () {        
        $grupos = $this->getDoctrine()->getRepository('PublicacaoBundle:Grupo')->findBy(array('ativo' => true));
        return $this->render('PublicacaoBundle:Categoria:listarGrupos.html.twig', array('grupos' => $grupos));
    }
    
    /*
     * Categorias
     */
    
    public function listarCategoriasAction (Grupo $grupo) {        
        $categoria = new Categoria();
        $categoria->setGrupo($grupo);
        $categoria->setAtivo(true);
        $errors = '';
        $form = $this->createForm(new CategoriaForm(), $categoria);
        $form->handleRequest($this->getRequest());
        
        if ($form->isValid()) {
            $dados = $form->getData();
            $categoria->setNome($this->stripString($dados->getNomeExibicao()));
            $categorias = $this->getDoctrine()->getRepository('PublicacaoBundle:Categoria')->findBy(array('nome' => $dados->getNome(), 'ativo' => true, 'grupo' => $grupo));
            $temCategoria = $this->existeCategoria($categorias, $dados->getNome());
            if ($temCategoria) {
                $errors = '<div class="panel panel-danger" style="padding: 7px; width: 95%; margin-left: 2.5%; margin-bottom: 1%; margin-top: 2%;"><div class="panel-heading">Por favor, verifique os itens abaixo:</div><div class="panel-body"><ul style="font-size: 12px;"><li>Já existe uma categoria com este nome, por favor, digite outro nome.</li></ul></div></div>';
            } else {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($categoria);
                    $em->flush();
                    $caminho = $this->getCaminho($categoria);
                    \mkdir($caminho, 0777);
                    $categoria = new Categoria();
                    $this->get('session')->getFlashBag()->set('message', 'Categoria criada com sucesso');
                    return $this->redirect($this->generateUrl('publicacao_categorias', array('id'=>$grupo->getId())));
                } catch (\Exception $ex) {
                    $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
                }
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	}

        $categorias = $this->getDoctrine()->getRepository('PublicacaoBundle:Categoria')->findBy(array('ativo' => true, 'grupo' => $grupo));
        return $this->render('PublicacaoBundle:Categoria:listar.html.twig', array('grupo' => $grupo, 'categorias' => $categorias, 'form' => $form->createView(), 'erros' => $errors));
    }
    
    public function existeCategoria ($categorias, $nome) {
        if (count($categorias > 0)) {
            foreach ($categorias as $categoria) {
                if ($categoria->getNome() === $nome) {
                    return true;
                }
            }
        }
        return false;
    }
    
    public function editarCategoriasAction (Categoria $categoria) {
        $categoriaAntiga = $categoria->getNome();
        $grupo = $categoria->getGrupo();
        $errors = '';
        $form = $this->createForm(new CategoriaForm(), $categoria);
        $form->handleRequest($this->getRequest());
        
        if ($form->isValid()) {
            $dados = $form->getData();
            $categoria->setNome($this->stripString($dados->getNomeExibicao()));
            $categorias = $this->getDoctrine()->getRepository('PublicacaoBundle:Categoria')->findBy(array('nome' => $dados->getNome(), 'ativo' => true, 'grupo' => $grupo));
            $temCategoria = $this->existeCategoria($categorias, $dados->getNome());
            
            if ($temCategoria) {
                $errors = '<div class="panel panel-danger" style="padding: 7px; width: 95%; margin-left: 2.5%; margin-bottom: 1%; margin-top: 2%;"><div class="panel-heading">Por favor, verifique os itens abaixo:</div><div class="panel-body"><ul style="font-size: 12px;"><li>Já existe uma categoria com este nome, por favor, digite outro nome.</li></ul></div></div>';
            } else {
                try {
                    $this->getDoctrine()->getManager()->merge($categoria);
                    $this->getDoctrine()->getManager()->flush();
                    $caminho = $this->getCaminho($categoria);
                    $caminhoAntigo = $this->getCaminho($categoria, $categoriaAntiga);
                    \rename($caminhoAntigo, $caminho);
                    $this->get('session')->getFlashBag()->set('message', 'Categoria alterada com sucesso');
                    return $this->redirect($this->generateUrl('publicacao_categorias', array('id'=>$grupo->getId())));
                } catch (\Exception $ex) {
                    $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
                }
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
        
        return $this->render('PublicacaoBundle:Categoria:editar.html.twig', array('categoria' => $categoria, 'grupo' => $grupo, 'form' => $form->createView(), 'erros' => $errors));
    }
    
    public function excluirCategoriasAction(Categoria $categoria) {
        try {
            $pathAntigo = $categoria->getNome();
            $grupo = $categoria->getGrupo();
            $categoria->setAtivo(false);
            
            $this->getDoctrine()->getManager()->merge($categoria);
            $this->getDoctrine()->getManager()->flush();
            $caminho = $this->getCaminho($categoria);
            \rename($caminho, $caminho . '_' . date('dmYHis'));
            $this->get('session')->getFlashBag()->set('message', 'Categoria removida com sucesso');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('publicacao_categorias', array('id'=>$grupo->getId())));
    }
    
    /*
     * Sub-Categorias
     */
    
    public function listarSubCategoriasAction (Categoria $categoriaPai) {        
        $categoria = new Categoria();
        $categoria->setCategoria($categoriaPai);
        $categoria->setAtivo(true);
        $errors = '';
        $form = $this->createForm(new CategoriaForm(), $categoria);
        $form->handleRequest($this->getRequest());
        
        if ($form->isValid()) {
            $dados = $form->getData();
            $categoria->setNome($this->stripString($dados->getNomeExibicao()));
            $categorias = $this->getDoctrine()->getRepository('PublicacaoBundle:Categoria')->findBy(array('nome' => $dados->getNome(), 'ativo' => true, 'categoria' => $categoriaPai));
            $temCategoria = $this->existeCategoria($categorias, $dados->getNome());
            if ($temCategoria) {
                $errors = '<div class="panel panel-danger" style="padding: 7px; width: 95%; margin-left: 2.5%; margin-bottom: 1%; margin-top: 2%;"><div class="panel-heading">Por favor, verifique os itens abaixo:</div><div class="panel-body"><ul style="font-size: 12px;"><li>Já existe uma categoria com este nome, por favor, digite outro nome.</li></ul></div></div>';
            } else {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($categoria);
                    $em->flush();
                    $caminho = $this->getCaminho($categoria);
                    \mkdir($caminho, 0777);
                    $categoria = new Categoria();
                    $this->get('session')->getFlashBag()->set('message', 'Categoria criada com sucesso');
                    return $this->redirect($this->generateUrl('publicacao_subcategorias', array('id'=>$categoriaPai->getId())));
                } catch (\Exception $ex) {
                    $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
                }
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
        
        
        $breadcrumbs = $this->getBreadcrumb($categoriaPai);
        $breadcrumbs = array_reverse($breadcrumbs);
        
        $categorias = $this->getDoctrine()->getRepository('PublicacaoBundle:Categoria')->findBy(array('ativo' => true, 'categoria' => $categoriaPai));
        return $this->render('PublicacaoBundle:Subcategoria:listar.html.twig', array('breadcrumbs' => $breadcrumbs, 'categoria' => $categoriaPai, 'categorias' => $categorias, 'form' => $form->createView(), 'erros' => $errors));
    }
    
    public function editarSubCategoriasAction (Categoria $categoria) {
        $categoriaAntiga = $categoria->getNome();
        $categoriaPai = $categoria->getCategoria();
        $errors = '';
        $form = $this->createForm(new CategoriaForm(), $categoria);
        $form->handleRequest($this->getRequest());
        
        if ($form->isValid()) {
            $dados = $form->getData();
            $categoria->setNome($this->stripString($dados->getNomeExibicao()));
            $categorias = $this->getDoctrine()->getRepository('PublicacaoBundle:Categoria')->findBy(array('nome' => $dados->getNome(), 'ativo' => true, 'categoria' => $categoriaPai));
            $temCategoria = $this->existeCategoria($categorias, $dados->getNome());
            
            if ($temCategoria) {
                $errors = '<div class="panel panel-danger" style="padding: 7px; width: 95%; margin-left: 2.5%; margin-bottom: 1%; margin-top: 2%;"><div class="panel-heading">Por favor, verifique os itens abaixo:</div><div class="panel-body"><ul style="font-size: 12px;"><li>Já existe uma categoria com este nome, por favor, digite outro nome.</li></ul></div></div>';
            } else {
                try {
                    $this->getDoctrine()->getManager()->merge($categoria);
                    $this->getDoctrine()->getManager()->flush();
                    $caminho = $this->getCaminho($categoria);
                    $caminhoAntigo = $this->getCaminho($categoria, $categoriaAntiga);
                    \rename($caminhoAntigo, $caminho);
                    $this->get('session')->getFlashBag()->set('message', 'Categoria alterada com sucesso');
                    return $this->redirect($this->generateUrl('publicacao_subcategorias', array('id'=>$categoriaPai->getId())));
                } catch (\Exception $ex) {
                    $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
                }
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	} 
        
        return $this->render('PublicacaoBundle:Subcategoria:editar.html.twig', array('categoria' => $categoria, 'categoriaPai' => $categoriaPai, 'form' => $form->createView(), 'erros' => $errors));
    }
    
    public function excluirSubCategoriasAction(Categoria $categoria) {
        try {
            $categoriaPai = $categoria->getCategoria();
            $categoria->setAtivo(false);
            $this->getDoctrine()->getManager()->merge($categoria);
            $this->getDoctrine()->getManager()->flush();
            $caminho = $this->getCaminho($categoria);
            \rename($caminho, $caminho . '_' . date('dmYHis'));
            $this->get('session')->getFlashBag()->set('message', 'Categoria removida com sucesso');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($this->generateUrl('publicacao_subcategorias', array('id'=>$categoriaPai->getId())));
    }
    
    /*
     * Publicação
     */
    
    public function getMimeTypes () {
        /*
         * Arquivos Permitidos:
         * AVI, MPEG, BMP, JPEG, PNG, MP3, DOC, PDF, PPS, PPT, ODF, ODT, ODS
         */
        
        $mimeImages = array('image/bmp', 'image/x-windows-bmp', 'image/jpeg', 'image/pjpeg', 'image/png');
        $mimeText = array('application/msword', 'application/vnd.oasis.opendocument.text');
        $mimeSheet = array('application/vnd.oasis.opendocument.spreadsheet', 'application/vnd.ms-excel');
        $mimePresentation = array('application/mspowerpoint', 'application/vnd.ms-powerpoint', 'application/mspowerpoint', 'application/powerpoint', 'application/vnd.ms-powerpoint', 'application/x-mspowerpoint', 'application/vnd.oasis.opendocument.presentation');
        $mimeMovie = array('application/x-troff-msvideo', 'video/avi', 'video/msvideo', 'video/x-msvideo', 'video/mpeg', 'video/x-mpeg');
        $mimePdf = array('application/pdf');
        $mimeMusic = array('audio/mpeg3', 'audio/x-mpeg-3');
        $mimeTypes = array('imagens' => $mimeImages, 'textos' => $mimeText, 'planilhas' => $mimeSheet, 'apresentacoes' => $mimePresentation, 'filmes' => $mimeMovie, 'pdf' => $mimePdf, 'musicas' => $mimeMusic);
        
        return $mimeTypes;
    }
    
    public function isTipoPermitido($arquivo) {
        $mimeTypes = $this->getMimeTypes();
        //var_dump($arquivo->getMimeType()); die;
        foreach ($mimeTypes as $type) {
            if (in_array($arquivo->getMimeType(),$type)) {
                return true;
            }
        }
        return false;
    }
    
    public function publicacaoRaizAction() {
        $grupos = $this->getDoctrine()->getRepository('PublicacaoBundle:Grupo')->findBy(array('ativo' => true));
        return $this->render('PublicacaoBundle:Publicacao:raiz.html.twig', array('grupos' => $grupos));
    }
    
    public function publicacaoPastaAction(Grupo $grupo) {
        $errors = '';
        $documento = new Documento();
        $documento->setGrupo($grupo);
        $form = $this->createForm(new DocumentoForm(), $documento);
        $form->handleRequest($this->getRequest());
        
        if ($form->isValid()) {
            $arquivo = $documento->getArquivo();
            if ($this->isTipoPermitido($arquivo)) {
                $nomearquivo = $this->stripFileName($arquivo->getClientOriginalName());
                $documento->setArquivo($nomearquivo);
                $documento->setMimeType($arquivo->getMimeType());
                $documento->setAtivo(true);
                $documento->setPublicacao(\DateTime::createFromFormat('d/m/Y H:i', $documento->getPublicacao()));
                $caminho = $this->getCaminho($grupo);
                try {
                    $arquivo->move($caminho, $nomearquivo);
                    $em = $this->getDoctrine()->getManager();
                    $docId = $documento->getId();
                    if (!$docId) {
                        $em->persist($documento);
                    } else {
                        $em->merge($documento);
                    }
                    $em->flush();
                    $documento = new Documento();
                    $this->get('session')->getFlashBag()->set('message', 'Documento enviado com sucesso');
                    return $this->redirect($this->generateUrl('publicacao_pasta', array('id'=>$grupo->getId())));
                } catch (\Exception $ex) {
                    $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
                }
            } else {
                $errors = $this->get('form_helper')->showCustomErrors(array('Este tipo de arquivo não está autorizado a ser publicado, caso necessário, entre em contato com a DiTec para maiores informações.'));
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	}
        
        $mimeTypes = $this->getMimeTypes();
        $documentos = $this->getDoctrine()->getRepository('PublicacaoBundle:Documento')->findBy(array('ativo' => true, 'grupo' => $grupo));
        $categorias = $this->getDoctrine()->getRepository('PublicacaoBundle:Categoria')->findBy(array('ativo' => true, 'grupo' => $grupo));
        return $this->render('PublicacaoBundle:Publicacao:pasta.html.twig', array('erros' => $errors, 'mimeTypes' => $mimeTypes, 'documentos' => $documentos,'grupo' => $grupo, 'categorias' => $categorias, 'form' => $form->createView()));
    }
    
    public function publicacaoSubPastaAction(Categoria $categoriaPai) {
        $errors = '';
        $documento = new Documento();
        $documento->setCategoria($categoriaPai);
        $form = $this->createForm(new DocumentoForm(), $documento);
        $form->handleRequest($this->getRequest());
        
        if ($form->isValid()) {
            $arquivo = $documento->getArquivo();
            if ($this->isTipoPermitido($arquivo)) {
                $nomearquivo = $this->stripFileName($arquivo->getClientOriginalName());
                $documento->setArquivo($nomearquivo);
                $documento->setMimeType($arquivo->getMimeType());
                $documento->setAtivo(true);
                $documento->setPublicacao(\DateTime::createFromFormat('d/m/Y H:i', $documento->getPublicacao()));
                $caminho = $this->getCaminho($categoriaPai);
                try {
                    $arquivo->move($caminho, $nomearquivo);
                    $em = $this->getDoctrine()->getManager();
                    $docId = $documento->getId();
                    if (!$docId) {
                        $em->persist($documento);
                    } else {
                        $em->merge($documento);
                    }
                    $em->flush();
                    $documento = new Documento();
                    $this->get('session')->getFlashBag()->set('message', 'Documento enviado com sucesso');
                    return $this->redirect($this->generateUrl('publicacao_subpasta', array('id'=>$categoriaPai->getId())));
                } catch (\Exception $ex) {
                    $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
                }
            } else {
                $errors = $this->get('form_helper')->showCustomErrors(array('Este tipo de arquivo não está autorizado a ser publicado, caso necessário, entre em contato com a DiTec para maiores informações.'));
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	}
        
        $mimeTypes = $this->getMimeTypes();
        $documentos = $this->getDoctrine()->getRepository('PublicacaoBundle:Documento')->findBy(array('ativo' => true, 'categoria' => $categoriaPai));
        $categorias = $this->getDoctrine()->getRepository('PublicacaoBundle:Categoria')->findBy(array('ativo' => true, 'categoria' => $categoriaPai));
        return $this->render('PublicacaoBundle:Publicacao:subpasta.html.twig', array('erros' => $errors, 'mimeTypes' => $mimeTypes, 'documentos' => $documentos, 'categoria' => $categoriaPai, 'categorias' => $categorias, 'form' => $form->createView()));
    }
    
    public function editarArquivoAction(Documento $arquivo) {
        $data = $arquivo->getPublicacao();
        $array = array($arquivo->getId(), $arquivo->getNomeExibicao(), $arquivo->getArquivo(), $data->format('d/m/Y H:i'), $arquivo->getVisibilidade()->getId());
        return new JsonResponse($array);
    }
    
    public function baixarArquivoAction(Documento $arquivo) {
        $caminho = '';
        $grupo = $arquivo->getGrupo();
        if ($grupo !== null) {
            $caminho = $this->getCaminho($grupo);
        } else {
            $caminho = $this->getCaminho($arquivo->getCategoria());
        }
        $caminho .= '/' . $arquivo->getArquivo();
        
        if (!file_exists($caminho)) {
            throw $this->createNotFoundException();
        }
        
        $response = new BinaryFileResponse($caminho);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $arquivo->getArquivo(), iconv('UTF-8', 'ASCII//TRANSLIT', $arquivo->getArquivo()));
        return $response;
    }
    
    public function excluirArquivoAction(Documento $arquivo) {
        try {
            $arquivo->setAtivo(false);
            $arquivo->setDeletedAt(new \DateTime());
            
            if ($arquivo->getGrupo() !== null) {
                $url = $this->generateUrl('publicacao_pasta', array('id'=>$arquivo->getGrupo()->getId()));
                $caminho = $this->getCaminho($arquivo->getGrupo());
                $caminho.= '/' . $arquivo->getArquivo();
            } else {
                $url = $this->generateUrl('publicacao_subpasta', array('id'=>$arquivo->getCategoria()->getId()));
                $caminho = $this->getCaminho($arquivo->getCategoria());
                $caminho.= '/' . $arquivo->getArquivo();
            }
            $this->getDoctrine()->getManager()->merge($arquivo);
            $this->getDoctrine()->getManager()->flush();
            \rename($caminho, $caminho . '_' . date('dmYHis'));
            $this->get('session')->getFlashBag()->set('message', 'Arquivo removido com sucesso');
        } catch (\Exception $ex) {
            die($ex->getMessage());
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
        }
        return $this->redirect($url);
    }
}