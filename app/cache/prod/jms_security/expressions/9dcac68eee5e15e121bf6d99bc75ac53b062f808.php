<?php
// Expression: #protocolo.getRequerente().getId() == user.getPessoa().getId()
return function(array $context) {
    if (!isset($context['object'])) {
        throw new RuntimeException('The context contains no item with key "object".');
    }

    if (!$context['object'] instanceof CG\Proxy\MethodInvocation) {
        throw new RuntimeException(sprintf('The item "object" is expected to be of type "CG\Proxy\MethodInvocation", but got "%s".', get_class($context['object'])));
    }

    $a = array();
    foreach ($context['object']->reflection->getParameters() as $b => $c) {
        $a[$c->name] = $b;
    }

    if (!isset($a['protocolo'])) {
        throw new RuntimeException(sprintf('There is no parameter with name "protocolo" for method "%s".', $context['object']));
    }

    if (!isset($context['user'])) {
        throw new RuntimeException('The context contains no item with key "user".');
    }

    return ($context['object']->arguments[$a['protocolo']]->getRequerente()->getId()) === ($context['user']->getPessoa()->getId());
};
