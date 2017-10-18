<?php

namespace SME\CommonsBundle\Util;

class NumberUtil {
    
    public static function numeroPorExtenso($numero)
    {
        switch($numero) {
            case 1: return 'um'; 
            case 2: return 'dois'; 
            case 3: return 'três'; 
            case 4: return 'quatro'; 
            case 5: return 'cinco'; 
            case 6: return 'seis'; 
            case 7: return 'sete'; 
            case 8: return 'oito'; 
            case 9: return 'nove'; 
            case 10: return 'dez'; 
            case 11: return 'onze'; 
            case 12: return 'doze'; 
            case 13: return 'treze'; 
            case 14: return 'quatorze'; 
            case 15: return 'quinze'; 
            case 16: return 'dezesseis'; 
            case 17: return 'dezessete'; 
            case 18: return 'dezoito'; 
            case 19: return 'dezenove'; 
            case 20: return 'vinte'; 
            case 21: return 'vinte e um'; 
            case 22: return 'vinte e dois'; 
            case 23: return 'vinte e três'; 
            case 24: return 'vinte e quatro'; 
            case 25: return 'vinte e cinco'; 
            case 26: return 'vinte e seis'; 
            case 27: return 'vinte e sete'; 
            case 28: return 'vinte e oito'; 
            case 29: return 'vinte e nove'; 
            case 30: return 'trinta'; 
            case 31: return 'trinta e um';
        }
        return null;
    }
    
}
