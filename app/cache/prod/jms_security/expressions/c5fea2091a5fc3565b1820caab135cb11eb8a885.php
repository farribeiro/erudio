<?php
// Expression: hasRole('ROLE_INFANTIL_MEMBRO')
return function(array $context) {
    if (!isset($context['token'])) {
        throw new RuntimeException('The context contains no item with key "token".');
    }

    if (!$context['token'] instanceof Symfony\Component\Security\Core\Authentication\Token\TokenInterface) {
        throw new RuntimeException(sprintf('The item "token" is expected to be of type "Symfony\Component\Security\Core\Authentication\Token\TokenInterface", but got "%s".', get_class($context['token'])));
    }

    $a = $context['token']->getRoles();
    if (null !== $b = $context['container']->get('security.role_hierarchy', 2)) {
        $a = $b->getReachableRoles($a);
    }

    $c = array();
    foreach ($a as $role) {
        $c[$role->getRole()] = true;
    }
    $a = $c;

    return isset($a['ROLE_INFANTIL_MEMBRO']);
};
