<?php

namespace SME\CommonsBundle\Util;

class DocumentosUtil {
    
    const CERTIDAO_NASCIMENTO_CARTORIO_START = 0;
    const CERTIDAO_NASCIMENTO_LIVRO_START = 15;
    const CERTIDAO_NASCIMENTO_FOLHA_START = 20;
    const CERTIDAO_NASCIMENTO_TERMO_START = 23;
    const CERTIDAO_NASCIMENTO_ANO_START = 10;
    const CERTIDAO_NASCIMENTO_CARTORIO_SIZE = 6;
    const CERTIDAO_NASCIMENTO_LIVRO_SIZE = 5;
    const CERTIDAO_NASCIMENTO_FOLHA_SIZE = 3;
    const CERTIDAO_NASCIMENTO_TERMO_SIZE = 7;
    
    public static function validarCpf($cpf) {
        // Verifica se o numero de digitos informados é igual a 11 
        if (\strlen($cpf) != 11 || !\is_numeric($cpf)) {
            return false;
        }
        else if (
            $cpf == '00000000000' || 
            $cpf == '11111111111' || 
            $cpf == '22222222222' || 
            $cpf == '33333333333' || 
            $cpf == '44444444444' || 
            $cpf == '55555555555' || 
            $cpf == '66666666666' || 
            $cpf == '77777777777' || 
            $cpf == '88888888888' || 
            $cpf == '99999999999') {
            return false; 
         }
         // Calcula os digitos verificadores para verificar se o CPF é válido
         else {
            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }
            return true;
        }
    }
    
    public static function formatarCpf($cpf) {
        return \is_numeric($cpf) && \strlen($cpf) == 11
            ? \substr($cpf, 0, 3) . '.' . \substr($cpf, 3, 3) . '.' . \substr($cpf, 6, 3) . '-' . \substr($cpf, 9, 3)
            : $cpf;
    }
    
    public static function validarCertidaoNascimento($certidao) {
        return \strlen($certidao) === 32 && self::validarDadosCertidaoNascimento( 
                    \substr($certidao, self::CERTIDAO_NASCIMENTO_TERMO_START, self::CERTIDAO_NASCIMENTO_TERMO_SIZE),
                    \substr($certidao, self::CERTIDAO_NASCIMENTO_LIVRO_START, self::CERTIDAO_NASCIMENTO_LIVRO_SIZE), 
                    \substr($certidao, self::CERTIDAO_NASCIMENTO_FOLHA_START, self::CERTIDAO_NASCIMENTO_FOLHA_SIZE), 
                    \substr($certidao, self::CERTIDAO_NASCIMENTO_CARTORIO_START, self::CERTIDAO_NASCIMENTO_CARTORIO_SIZE));
    }
    
    public static function validarDadosCertidaoNascimento($termo, $livro, $folha, $cartorio = '0') {
        return \strlen($cartorio) <= self::CERTIDAO_NASCIMENTO_CARTORIO_SIZE && \is_numeric($cartorio) && 
               \strlen($livro) <= self::CERTIDAO_NASCIMENTO_LIVRO_SIZE && \is_numeric($livro) && 
               \strlen($folha) <= self::CERTIDAO_NASCIMENTO_FOLHA_SIZE && \is_numeric($folha) && 
               \strlen($termo) <= self::CERTIDAO_NASCIMENTO_TERMO_SIZE && \is_numeric($termo);
    }
    
    public static function gerarCertidaoNascimento($termo, $livro, $folha = '0', $cartorio = '0', $ano = 'XXXX') {
        if(self::validarDadosCertidaoNascimento($termo, $livro, $folha, $cartorio) && \strlen($ano) === 4) {
            while(\strlen($cartorio) < self::CERTIDAO_NASCIMENTO_CARTORIO_SIZE) { $cartorio = '0' . $cartorio; }
            while(\strlen($livro) < self::CERTIDAO_NASCIMENTO_LIVRO_SIZE) { $livro = '0' . $livro; }
            while(\strlen($folha) < self::CERTIDAO_NASCIMENTO_FOLHA_SIZE) { $folha = '0' . $folha; }
            while(\strlen($termo) < self::CERTIDAO_NASCIMENTO_TERMO_SIZE) { $termo = '0' . $termo; }
            return $cartorio . '0X55' . $ano . '1' . $livro . $folha . $termo . 'XX';
        } else {
            throw new \Exception('Os dados da certidão não são válidos, certifique-se que todos contenham apenas dígitos numéricos');
        }
    }
    
    public static function decomporCertidaoNascimento($certidao) {
        if(\strlen($certidao) === 32) {
            return array(
                'cartorio' => substr($certidao, self::CERTIDAO_NASCIMENTO_CARTORIO_START, self::CERTIDAO_NASCIMENTO_CARTORIO_SIZE),
                'livro' => substr($certidao, self::CERTIDAO_NASCIMENTO_LIVRO_START, self::CERTIDAO_NASCIMENTO_LIVRO_SIZE),
                'folha' => substr($certidao, self::CERTIDAO_NASCIMENTO_FOLHA_START, self::CERTIDAO_NASCIMENTO_FOLHA_SIZE),
                'termo' => substr($certidao, self::CERTIDAO_NASCIMENTO_TERMO_START, self::CERTIDAO_NASCIMENTO_TERMO_SIZE),
                'ano' => substr($certidao, self::CERTIDAO_NASCIMENTO_ANO_START, 4)
            );
        } else {
            throw new \Exception('O número de dígitos da certidão está incorreto');
        }
    }
    
}
