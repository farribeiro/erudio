<?php

namespace IntegracaoIEducarBundle\Service;

class IEducarFacade {
      
    const URL_SERVICO = 'https://intranet.itajai.sc.gov.br/educar_relatorio_historico_escolar_service.php';
    
    function getAlunos($nome, $dataNascimento) {
        $nomeUrl = urlencode($nome);
        return file_get_contents(self::URL_SERVICO . "?ws=alu&nasc={$dataNascimento}&nome={$nomeUrl}");
    }
    
    function getUnidadesEnsino($nome) {
        $param = $nome ? urlencode($nome) : 'e';
        return file_get_contents(self::URL_SERVICO . "?ws=esc&esc=" . $param);
    }
    
    function getHistorico($codAluno, $codUnidadeEnsino) {
        return file_get_contents(self::URL_SERVICO . "?ws=his&alu={$codAluno}&esc={$codUnidadeEnsino}");
    }
    
    function getHistoricoPDF($codAluno, $codUnidadeEnsino) {
        $options = ['http' => [
            'method' => 'GET',
            'header' => 'Accept: application/pdf'
        ]];
        $context = stream_context_create($options);
        $historico = file_get_contents(
            self::URL_SERVICO . "?ws=his&alu={$codAluno}&esc={$codUnidadeEnsino}",
            false,
            $context
        );
        return $historico;
    }
    
}
