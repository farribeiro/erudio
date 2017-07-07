<?php

namespace SME\PresencaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SME\PresencaBundle\Forms\PresencaForm;
use SME\PresencaBundle\Forms\BuscaPresencaForm;
use SME\PresencaBundle\Forms\PresencaRelatorioForm;
use SME\PresencaBundle\Entity\Presenca;
use Symfony\Component\HttpFoundation\Request;

class PresencaController extends Controller {
    
    public function indexAction(Request $request) {
        $unidade = $request->getSession()->get('unidade');
        $presenca = new Presenca();
        $errors = '';
        $form = $this->createForm(new PresencaForm(), $presenca);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            try {
                $dados = $form->getData();
                $data = \DateTime::createFromFormat('d/m/Y', $dados->getDataCadastro());
                $data = $data->format('Y-m-d');
                $u = $this->getDoctrine()->getRepository('CommonsBundle:Entidade')->find($unidade->getId());
                $presenca->setEntidade($u);
                $presenca->setDataCadastro($data);
                $em = $this->getDoctrine()->getManager();
                $em->persist($presenca);
                $em->flush();
                $this->get('session')->getFlashBag()->set('message', 'Presença cadastrada com sucesso');
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	}
        
        $formRelatorio = $this->createForm(new PresencaRelatorioForm());
        $formRelatorio->handleRequest($this->getRequest());
        $presencas = null;
        if ($formRelatorio->isValid()) {
            $dados = $formRelatorio->getData();
            $presencas = $this->buscarPresencas($dados, $request);
        } else {
            $presencas = $this->buscarPresencas(array('turma'=>null,'turno'=>null,'dataInicial'=>null,'dataFinal'=>null), $request);
        }
        return $this->render('PresencaBundle:Index:index.html.twig', array('formRelatorio' => $formRelatorio->createView(), 'form' => $form->createView(), 'erros' => $errors, 'presencas' => $presencas));
    }
    
    protected function buscarPresencas ($dados, $request) {
        $unidade = $request->getSession()->get('unidade');
        $turma = $dados['turma'];
        $turno = $dados['turno'];
        $dataInicio = $dados['dataInicial'];
        $dataTermino = $dados['dataFinal'];
        $presencas = null;
        
        $qb = $this->getDoctrine()->getRepository('PresencaBundle:Presenca')->createQueryBuilder('p');
        $qb->join('p.entidade', 'e')
                ->where('e.id = :entidade')
                ->setParameter('entidade',$unidade->getId());
        if (!empty($turma)) { $qb->andWhere('p.turma = :turma')->setParameter('turma', $turma); }
        if (!empty($turno)) { $qb->andWhere('p.turno = :turno')->setParameter('turno', $turno); }
        if (empty($dataInicio) || empty($dataTermino)) { 
            $mes = date('m'); 
            $qb->andWhere('p.dataCadastro LIKE :mes')->setParameter('mes','%-' . $mes . '-%');
            $presencas = $qb->orderBy('p.dataCadastro','DESC')->getQuery()->getResult();
        } else {
            $dataInicio = \DateTime::createFromFormat('d/m/Y', $dataInicio);
            $dataTermino = \DateTime::createFromFormat('d/m/Y', $dataTermino);
            $presencas = $qb->andWhere($qb->expr()->between('p.dataCadastro', ':inicio', ':termino'))
                            ->setParameter('inicio', $dataInicio->format('Y-m-d'))
                            ->setParameter('termino', $dataTermino->format('Y-m-d'))
                            ->orderBy('p.dataCadastro','DESC')
                            ->getQuery()->getResult();
        }
        
        return $presencas;
    }
    
    public function removerAction(Presenca $presenca) {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($presenca);
            $em->flush();
            $this->get('session')->getFlashBag()->set('message', 'Presença excluída com sucesso');
    	} catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
    	}
        return $this->redirect($this->generateUrl('presenca_index'));
    }
    
    public function buscaAction(Request $request) {
        $presenca = new Presenca();
        $presenca->setDataCadastro('01/01/2001');
        $presenca->setQtdeAlunos(1);
        
        $unidade = null;
        $mes = null;
        $turma = null;
        $turno = null;
        $qb = null;
        $presencas = null;
        $totalPresencas = 0;
        
        $errors = '';
        $form = $this->createForm(new BuscaPresencaForm(), $presenca);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            try {
                $dados = $form->getData();
                $unidade = $dados->getEntidade();
                $mes = $form->get('mes')->getData();
                $turma = $dados->getTurma();
                $turno = $dados->getTurno();
                
                $qb = $this->getDoctrine()->getRepository('PresencaBundle:Presenca')
                    ->createQueryBuilder('p')
                    ->join('p.entidade', 'e')
                    ->where('e.id = :entidade')
                    ->setParameter('entidade',$unidade->getId());
                    if (!empty($turma)) { $qb->andWhere('p.turma = :turma')->setParameter('turma',$turma); }
                    if (!empty($turno)) { $qb->andWhere('p.turno = :turno')->setParameter('turno',$turno); }
                    if (!empty($mes)) { $qb->andWhere('p.dataCadastro LIKE :mesAtual')->setParameter('mesAtual',"%-$mes-%"); }
                $presencas = $qb->orderBy('p.dataCadastro','DESC')
                    ->getQuery()->getResult();
                
                foreach ($presencas as $presenca) {
                    $totalPresencas += $presenca->getQtdeAlunos();
                }
                
            } catch (\Exception $ex) {
                $this->get('session')->getFlashBag()->set('error', $ex->getMessage());
            }
        } else {
            $errors = $this->get('form_helper')->getFormErrors($form);
    	}

        return $this->render('PresencaBundle:Index:busca.html.twig', array('form' => $form->createView(), 'erros' => $errors, 'presencas' => $presencas, 'totalPresencas' => $totalPresencas));
    }
}
