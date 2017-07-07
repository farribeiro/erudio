<?php

 //include 'maintenance.html';
 //die();

//use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

// Use APC for autoloading to improve performance.
// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
// with other applications also using APC.

//$loader = new ApcClassLoader('sme', $loader);
//$loader->register(true);

require_once __DIR__.'/../app/AppKernel.php';
require_once __DIR__.'/../app/AppCache.php';

$kernel = new AppKernel('prod', true);
$kernel->loadClassCache();
$kernel = new AppCache($kernel);
Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
if(preg_match('|MSIE ([0-9].[0-9]{1,2})|', $request->server->get('HTTP_USER_AGENT'), $matched)) {
    if( $matched[1] < 9) {
        include 'navegador_incompativel.html';
        die();
    }
}
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
