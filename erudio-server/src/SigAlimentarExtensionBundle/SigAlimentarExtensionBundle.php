<?php

namespace SigAlimentarExtensionBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use SigAlimentarExtensionBundle\DependencyInjection\SigAlimentarExtension;

class SigAlimentarExtensionBundle extends Bundle {
    
    function getContainerExtension() {
        return new SigAlimentarExtension();
    }
    
}
