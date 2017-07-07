<?php

namespace SME\SuporteTecnicoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller {
    
    public function indexAction() {
        return $this->get('security.context')->isGranted('ROLE_SUPORTE_ADMIN') 
                || $this->get('security.context')->isGranted('ROLE_SUPORTE_USER') 
                ? $this->render('SuporteTecnicoBundle:Index:index.html.twig')
                : $this->render('SuporteTecnicoBundle:Index:forbidden.html.twig');
    }
    
}