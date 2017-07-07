<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_erros', 1);
//error_reporting(E_ALL);
 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
Debug::enable();

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
if(preg_match('|MSIE ([0-9].[0-9]{1,2})|', $request->server->get('HTTP_USER_AGENT'), $matched)) {
    if( $matched[1] < 9) {
        throw new \Exception('O navegador Internet explrer só é suportado a partir da versão 9.');
    }
}
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
