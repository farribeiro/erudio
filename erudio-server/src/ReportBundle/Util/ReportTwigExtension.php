<?php

namespace ReportBundle\Util;

use ReportBundle\Util\DateTimeUtil;

class ReportTwigExtension extends \Twig_Extension {
    
    function getFilters() {
        return array(
            new \Twig_SimpleFilter('cpf', array($this, 'cpfFilter')),
            new \Twig_SimpleFilter('dataPorExtenso', array($this, 'dataPorExtensoFilter'))
        );
    }
    
    function getName() {
        return 'report_extension';
    }
    
    function cpfFilter($cpf) {
        return \strlen($cpf) === 11
            ? \substr($cpf, 0, 3) . '.' . \substr($cpf, 3, 3) . '.' . \substr($cpf, 6, 3) . '-' . \substr($cpf, 9)
            : $cpf;
    }
    
    function dataPorExtensoFilter($data) {
        if  ($data == 'now') {
            $data = new \DateTime();
        }
        return DateTimeUtil::dataPorExtenso($data);
    }
    
}
