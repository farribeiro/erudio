<?php

namespace SME\SuporteTecnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;
use SME\SuporteTecnicoBundle\Form\ChamadoBasicType;
use SME\SuporteTecnicoBundle\Form\ChamadoCompleteType;
use SME\SuporteTecnicoBundle\Form\ChamadoPesquisaBasicType;
use SME\SuporteTecnicoBundle\Form\ChamadoPesquisaCompleteType;
use SME\SuporteTecnicoBundle\Entity\Chamado;
use SME\SuporteTecnicoBundle\Entity\Status;
use SME\SuporteTecnicoBundle\Entity\Prioridade;
use SME\SuporteTecnicoBundle\Report\ChamadoReport;
use SME\SuporteTecnicoBundle\Report\ListaChamadosReport;


class ChamadoController extends Controller {
    
    const CHAMADOS_POR_PAGINA = 50;
    
    const VIEW_LISTA = 'lista';
    const VIEW_MAPA = 'mapa';
    const VIEW_PDF = 'pdf';
    
    public function pesquisarAction(Request $request) {
        $admin = $this->get('security.context')->isGranted('ROLE_SUPORTE_ADMIN');
        $unidade = $request->getSession()->has('unidade') ? $request->getSession()->get('unidade') : null;
        if($admin) {
            $qb = $this->getDoctrine()->getRepository('SuporteTecnicoBundle:Categoria')->createQueryBuilder('c');
            $categorias = $qb->where('c.ativo = true')->getQuery()->getResult();
            usort($categorias, function ($a, $b) {
                return strcmp($a->getNomeHierarquico(), $b->getNomeHierarquico());
            });
            $form = $this->createForm(new ChamadoPesquisaCompleteType(), null, array('categorias' => $categorias));
        } else {
            $form = $this->createForm(new ChamadoPesquisaBasicType());
        }
        $form->handleRequest($request);
        if($form->isValid()) {
            try {
                $params = $form->getData();
                //injeção de parâmetros na busca de acordo com permissões
                if(!$admin) {
                    $params['solicitante'] = $unidade ? null : $this->getUser()->getPessoa();
                    $params['local'] = $unidade ? $unidade : null;
                }
                if($params['periodoCadastroInicio'] && $params['periodoCadastroFim']) {
                    $params['periodoCadastro'] = array(
                        0 => $params['periodoCadastroInicio'], 
                        1 => $params['periodoCadastroFim']
                    );
                }
                $view = $request->query->get('view');
                if($view == self::VIEW_PDF) {
                    $chamados = $this->pesquisar($params, false);
                    $pdf = new ListaChamadosReport();
                    $pdf->setChamados($chamados);
                    return $this->get('pdf')->render($pdf);
                } else {
                    $chamados = $this->pesquisar($params, true, $params['pagina']);
                    return $view == self::VIEW_MAPA 
                        ? $this->gerarMapa($chamados)
                        : $this->render('SuporteTecnicoBundle:Chamado:chamados.html.twig', array(
                            'chamados' => $chamados,
                            'pagina' => $params['pagina']
                          ));
                }
            } catch(\Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', $ex->getMessage());
            }
        }
        return $this->render(
            'SuporteTecnicoBundle:Chamado:' . ($admin ? 'formPesquisaAdmin.html.twig' : 'formPesquisa.html.twig'),
            array('form' => $form->createView())
        );
    }
    
    private function pesquisar(array $params, $paginar = true, $pagina = 0) {
        $qb = $this->getDoctrine()->getRepository('SuporteTecnicoBundle:Chamado')
            ->createQueryBuilder('c')->join('c.categoria', 'categoria')
            ->join('c.status', 'status')->join('c.local', 'local')
            ->where('c.ativo = true');
        foreach($params as $k => $v) {
            if($v !== null && key_exists($k, $this->parameterMap())) {
                $f = $this->parameterMap()[$k];
                $f($qb, $v);
            }
        }
        if($paginar) {
            $qb = $qb->setMaxResults(self::CHAMADOS_POR_PAGINA)
                     ->setFirstResult(self::CHAMADOS_POR_PAGINA * $pagina);
        }
        return $qb->getQuery()->getResult();
    }
    
    private function parameterMap() {
        return array (
            'equipe' => function(QueryBuilder $qb, $value) {
                $qb->join('categoria.equipe', 'equipe')
                   ->andWhere('equipe.id = :equipe')->setParameter('equipe', $value);
            },
            'categoria' => function(QueryBuilder $qb, $value) {
                $categorias = $value->getAllSubcategorias();
                $ids = array($value->getId());
                foreach($categorias as $c) {
                    $ids[] = $c->getId();
                }
                $qb->andWhere('categoria.id IN (:categorias)')->setParameter('categorias', $ids);
            },
            'local' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('local.id = :local')->setParameter('local', $value);
            },
            'solicitante' => function(QueryBuilder $qb, $value) {
                $qb->join('c.pessoaCadastrou', 'solicitante')
                   ->andWhere('solicitante.id = :solicitante')->setParameter('solicitante', $value);
            },
            'status' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('status.id = :status')->setParameter('status', $value);
            },
            'aberto' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('status.terminal = :aberto')->setParameter('aberto', !$value);
            },
            'numero' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('c.id = :numero')->setParameter('numero', $value);
            },
            'bairro' => function(QueryBuilder $qb, $value) {
                $qb->join('local.pessoaJuridica', 'pj')->join('pj.endereco', 'endereco')
                   ->andWhere('endereco.bairro LIKE :bairro')->setParameter('bairro', '%' . $value . '%');
            },
            'periodoCadastro' => function(QueryBuilder $qb, $value) {
                $dataInicio = \DateTime::createFromFormat('d/m/Y', $value[0]);
                $dataTermino = \DateTime::createFromFormat('d/m/Y', $value[1]);
                $qb->andWhere($qb->expr()->between('c.dataCadastro', ':inicio', ':termino'))
                    ->setParameter('inicio', $dataInicio->format('Y-m-d'))
                    ->setParameter('termino', $dataTermino->format('Y-m-d'));  
            },
            'ordenacao' => function(QueryBuilder $qb, $value) {
                switch($value) {
                    case ChamadoPesquisaBasicType::ORDENACAO_PRIORIDADE:
                        $qb->join('c.prioridade', 'prioridade')->orderBy('prioridade.valor', 'DESC');
                        break;
                    case ChamadoPesquisaBasicType::ORDENACAO_DATA_CADASTRO_ASC:
                        $qb->orderBy('c.dataCadastro', 'ASC');
                        break;
                    case ChamadoPesquisaBasicType::ORDENACAO_DATA_CADASTRO_DESC:
                        $qb->orderBy('c.dataCadastro', 'DESC'); 
                        break;
                }
            }
        );
    }
    
    public function cadastrarAction(Request $request) {
        $admin = $this->get('security.context')->isGranted('ROLE_SUPORTE_ADMIN');
        if(!$admin) {
            $alocacoes = $this->get('dgp_alocacao')->findByPessoa($this->getUser()->getPessoa());
            $locais = array();
            foreach($alocacoes as $alocacao) {
                $locais[] = $alocacao->getLocalTrabalho();
            }
        } else {
            $locais = $this->getDoctrine()->getRepository('CommonsBundle:Entidade')->createQueryBuilder('e')
                           ->join('e.pessoaJuridica', 'pj')->orderBy('pj.nome', 'ASC')->getQuery()->getResult();
        }
        $chamado = new Chamado();
        $chamado->setPessoaCadastrou($this->getUser()->getPessoa());
        $chamado->setAtivo(true);
        $chamado->setStatus($this->getDoctrine()->getRepository('SuporteTecnicoBundle:Status')->find(Status::NOVO));
        $form = $this->createForm(new ChamadoBasicType(), $chamado, array('locais' => $locais));
        $form->handleRequest($request);
        if($form->isValid()) {
            $this->definirPrioridade($chamado);
            $this->getDoctrine()->getManager()->persist($chamado);
            $this->getDoctrine()->getManager()->flush();
            return $this->render('SuporteTecnicoBundle:Chamado:confirmacaoCadastro.html.twig', array(
                'chamado' => $chamado
            ));
        }
        return $this->render('SuporteTecnicoBundle:Chamado:formCadastro.html.twig', array(
            'form' => $form->createView(),
            'erros' => $this->get('form_helper')->getFormErrors($form),
            'categoriasRaiz' => $this->getDoctrine()->getRepository('SuporteTecnicoBundle:Categoria')->findBy(
                array('categoriaPai' => null, 'ativo' => true), array('nome' => 'ASC')
            )
        ));
    }
    
    private function definirPrioridade(Chamado $chamado) {
        $chamado->setPrioridade($chamado->getCategoria()->getPrioridade()); 
        $chamado->getTags()->forAll(function($k, $t) use ($chamado) {
            if($chamado->getPrioridade()->getValor() < $t->getPrioridade()->getValor()) {
                $chamado->setPrioridade($t->getPrioridade());
            }
        });
    }
    
    public function gerenciarAction(Chamado $chamado, Request $request) {
        return $this->get('security.context')->isGranted('ROLE_SUPORTE_ADMIN') //&& !$chamado->getEncerrado()
            ? $this->atualizar($chamado, $request) 
            : $this->exibir($chamado, $request);
    }
    
    private function exibir(Chamado $chamado, Request $request) {
        if(!$this->get('security.context')->isGranted('ROLE_SUPORTE_ADMIN') && 
           !$request->getSession()->has('unidade') &&
           $chamado->getPessoaCadastrou()->getId() != $this->getUser()->getPessoa()->getId())
        {
            throw new \Exception('Acesso não autorizado');
        }
        return $this->render('SuporteTecnicoBundle:Chamado:chamado.html.twig', array('chamado' => $chamado));
    }
    
    private function atualizar(Chamado $chamado, Request $request) {
        $qb = $this->getDoctrine()->getRepository('SuporteTecnicoBundle:Categoria')->createQueryBuilder('c');
        $categorias = $qb->where('c.ativo = true')->getQuery()->getResult();
        usort($categorias, function ($a, $b) {
            return strcmp($a->getNomeHierarquico(), $b->getNomeHierarquico());
        });
        $form = $this->createForm(new ChamadoCompleteType(), $chamado, array('categorias' => $categorias));
        $form->handleRequest($request);
        if($form->isValid()) {
            try {
                if($chamado->getStatus()->getTerminal()) {
                    $this->encerrar($chamado);
                }
                $this->getDoctrine()->getManager()->merge($chamado);
                $this->getDoctrine()->getManager()->flush();
                $this->get('notification')->send(
                    'Seu chamado técnico de nº ' . $chamado->getId() . ' sofreu atualizações, <a href="' . $this->generateUrl('suporte_chamado_gerenciar', array('chamado' => $chamado->getId())) . '">clique aqui</a> para visualizar', 
                    $chamado->getPessoaCadastrou()->getUsuario(), 'info'
                );
                $this->get('session')->getFlashBag()->set('message', $chamado->getStatus()->getTerminal() 
                        ? 'O chamado foi encerrado' : 'O chamado foi atualizado');
                return $this->redirect($this->generateUrl('suporte_chamado_gerenciar', array('chamado' => $chamado->getId())));
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
        }
        return $this->render('SuporteTecnicoBundle:Chamado:formEdicao.html.twig', array(
            'chamado' => $chamado,
            'erros' => $this->get('form_helper')->getFormErrors($form),
            'form' => $form->createView()
        ));
    }
    
    private function encerrar(Chamado $chamado) {
        if(!$chamado->getSolucao()) {
            throw new \Exception('Solução deve ser informada ao encerrar um chamado');
        }
        $chamado->setDataEncerramento(new \DateTime());
        $chamado->setPessoaEncerrou($this->getUser()->getPessoa());
    }
    
    /** teste com google maps */
    
    private function gerarMapa($chamados) {
        usort($chamados, function($a, $b) {
            return strcmp($a->getLocal()->getNome(), $b->getLocal()->getNome());
        });
        $marcadores = array();
        foreach($chamados as $c) {
            if($c->getLocal()->getPessoaJuridica()->getEndereco()) {                
                $icone = null;
                $endereco = null;
                
                switch ($c->getPrioridade()->getId()) {
                    case Prioridade::BAIXA:
                        $icone = 'http://maps.google.com/mapfiles/ms/icons/green.png';
                        break;
                    case Prioridade::ALTA:
                        $icone = 'http://maps.google.com/mapfiles/ms/icons/orange.png';
                        break;
                    case Prioridade::URGENTE:
                        $icone = 'http://maps.google.com/mapfiles/ms/icons/red.png';
                        break;
                    default:
                        $icone = 'http://maps.google.com/mapfiles/ms/icons/blue.png';
                        break;
                }
                
                $latitude = $c->getLocal()->getPessoaJuridica()->getEndereco()->getLatitude();
                if (empty($latitude)) {
                    $endereco = 'Rua ' . $c->getLocal()->getPessoaJuridica()->getEndereco()->getLogradouro() . ',' . $c->getLocal()->getPessoaJuridica()->getEndereco()->getNumero()
                    . ',' . $c->getLocal()->getPessoaJuridica()->getEndereco()->getBairro() . ',' . $c->getLocal()->getPessoaJuridica()->getEndereco()->getCidade()->getNome() 
                    . ','. $c->getLocal()->getPessoaJuridica()->getEndereco()->getCidade()->getEstado()->getNome() . ', Brasil';
                    $retorno = json_decode(file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $endereco . '&sensor=false'));
                    if (!empty($retorno->results)) {
                        $c->getLocal()->getPessoaJuridica()->getEndereco()->setLatitude($retorno->results[0]->geometry->location->lat);
                        $c->getLocal()->getPessoaJuridica()->getEndereco()->setLongitude($retorno->results[0]->geometry->location->lng);
                    }
                }
                
                $marcadores[] = array(
                    'id' => $c->getId(),
                    'local' => $c->getLocal()->getNome(),
                    'latitude' => $c->getLocal()->getPessoaJuridica()->getEndereco()->getLatitude(),
                    'longitude' => $c->getLocal()->getPessoaJuridica()->getEndereco()->getLongitude(),
                    'endereco' => $endereco,
                    'href' => $this->generateUrl('suporte_chamado_gerenciar', array('chamado' => $c->getId())),
                    'icone' => $icone,
                    'categoria' => $c->getCategoria()->getNomeHierarquico(),
                    'prioridade' => $c->getPrioridade()->getId(),
                    'status' => $c->getStatus()->getNome()
                );
            }
        }
        return $this->render('SuporteTecnicoBundle:Chamado:mapa.html.twig', array('marcadores' => json_encode($marcadores)));
    }
    
    public function imprimirAction(Chamado $chamado) {
        $pdf = new ChamadoReport();
        $pdf->setChamados(array($chamado));
        return $this->get('pdf')->render($pdf);
    }
    
}
