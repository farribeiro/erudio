<?php

namespace ReportBundle\Util;

class DateTimeUtil {
    
    static function dataPorExtenso(\DateTime $data) {
        switch($data->format('m')) {
            case 1: $mes = 'Janeiro'; break;
            case 2: $mes = 'Fevereiro'; break;
            case 3: $mes = 'Março'; break;
            case 4: $mes = 'Abril'; break;
            case 5: $mes = 'Maio'; break;
            case 6: $mes = 'Junho'; break;
            case 7: $mes = 'Julho'; break;
            case 8: $mes = 'Agosto'; break;
            case 9: $mes = 'Setembro'; break;
            case 10: $mes = 'Outubro'; break;
            case 11: $mes = 'Novembro'; break;
            case 12: $mes = 'Dezembro';   
        }
        return $data->format('d') . ' de ' . $mes . ' de ' . $data->format('Y');
    }
    
    static function mesPorExtenso($mes) {
        switch($mes) {
            case 1: return 'Janeiro';
            case 2: return 'Fevereiro';
            case 3: return 'Março';
            case 4: return 'Abril';
            case 5: return 'Maio';
            case 6: return 'Junho';
            case 7: return 'Julho';
            case 8: return 'Agosto';
            case 9: return 'Setembro';
            case 10: return 'Outubro';
            case 11: return 'Novembro';
            case 12: return 'Dezembro';
            default: throw new Exception('Argumento inválido: mês deve ser um número entre 1 e 12');
        }
    }
    
}
