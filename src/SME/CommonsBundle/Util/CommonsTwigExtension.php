<?php

namespace SME\CommonsBundle\Util;

class CommonsTwigExtension extends \Twig_Extension {
    
    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('cpf', array($this, 'cpfFilter'))
        );
    }
    
    public function getName() {
        return 'commons_extension';
    }
    
    public function cpfFilter($cpf) {
        return \strlen($cpf) === 11
            ? \substr($cpf, 0, 3) . '.' . \substr($cpf, 3, 3) . '.' . \substr($cpf, 6, 3) . '-' . \substr($cpf, 9)
            : $cpf;
    }
    
}
