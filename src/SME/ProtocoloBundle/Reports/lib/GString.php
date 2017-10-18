<?php
/**
 * <--- Copyright 2005-2011 de Solis - Cooperativa de SoluÃ§Ãµes Livres Ltda. e
 * Univates - Centro UniversitÃ¡rio.
 * 
 * Este arquivo Ã© parte do programa Gnuteca.
 * 
 * O Gnuteca Ã© um software livre; vocÃª pode redistribuÃ­-lo e/ou modificÃ¡-lo
 * dentro dos termos da LicenÃ§a PÃºblica Geral GNU como publicada pela FundaÃ§Ã£o
 * do Software Livre (FSF); na versÃ£o 2 da LicenÃ§a.
 * 
 * Este programa Ã© distribuÃ­do na esperanÃ§a que possa ser Ãºtil, mas SEM
 * NENHUMA GARANTIA; sem uma garantia implÃ­cita de ADEQUAÃ‡ÃƒO a qualquer MERCADO
 * ou APLICAÃ‡ÃƒO EM PARTICULAR. Veja a LicenÃ§a PÃºblica Geral GNU/GPL em
 * portuguÃªs para maiores detalhes.
 * 
 * VocÃª deve ter recebido uma cÃ³pia da LicenÃ§a PÃºblica Geral GNU, sob o tÃ­tulo
 * "LICENCA.txt", junto com este programa, se nÃ£o, acesse o Portal do Software
 * PÃºblico Brasileiro no endereÃ§o www.softwarepublico.gov.br ou escreva para a
 * FundaÃ§Ã£o do Software Livre (FSF) Inc., 51 Franklin St, Fifth Floor, Boston,
 * MA 02110-1301, USA --->
 * 
 *
 * @author Jamiel Spezia [jamiel@solis.coop.br]
 *
 * @version $Id$
 *
 * \b Maintainers: \n
 * Eduardo Bonfandini [eduardo@solis.coop.br]
 * Jamiel Spezia [jamiel@solis.coop.br]
 *
 * @since
 * Class created on 01/07/2011
 *
 **/
class GString
{
    private $string;
    private $encoding = 'UTF-8';
    
    public function __construct($string = null, $encoding = 'UTF-8')
    {
        $this->setEncoding($encoding);
        $this->setString($string);
    }

    /**
     * Contrutor estÃ¡tico usado para que possa se utilizar
     * o construtor e chamar a funÃ§Ã£o necessÃ¡ria na mesma linha.
     *
     * @param string $string
     * @return GString
     *
     * @example GString::construct( $string )->generate() = retorna a string em formato de usuÃ¡rio
     */
    public static function construct( $string, $encoding = 'UTF-8' )
    {
        return new GString($string, $encoding);
    }

    
    /**
     * Define a string
     * 
     * @param $string
     */
    public function setString($string)
    {
        $this->string = $this->_convert( $string );
    }

    /**
     * Retorna a string na codificaÃ§Ã£o necessÃ¡ria
     *
     * @param string $string
     * @return string retorna a string na codificaÃ§Ã£o necessÃ¡ria
     */
    protected function _convert( $string )
    {
        $enc = GString::detectEncoding($string);

        if ( $enc == $this->getEncoding() )
        {
            return $string;
        }
        else
        {
            return iconv($enc, $this->getEncoding(), $string );
        }

        return $string;
    }

    /**
     * Adiciona algum texto a string.
     *
     * Passa pela funÃ§Ã£o de conversÃ£o para garantir a string esteja na codificaÃ§Ã£o utilizada.
     *
     * @param string $string texto a ser adicionado
     */
    public function append( $string )
    {
        $this->string .= $this->_convert( $string ) ;
    }

    /**
     * Troca um contÃ©udo por outro, na string atual.
     * AlÃ©m disso retorna a nova string
     *
     * @param string $search conteÃºdo original, a buscar
     * @param string $replace novo conteÃºdo a subistituir
     * @param string retorna a nova string
     */
    public function replace( $search, $replace )
    {
        $this->string = str_replace($search, $replace, $this->string );
        
        return $this;
    }

    /**
     * Converte o texto para minusculas
     *
     * @return GString
     */
    public function toLower()
    {
        $this->string = mb_strtolower( $this->string,$this->getEncoding() );

        return $this;
    }

    /**
     * Converte o texto para maisculas
     *
     * @return GString
     */
    public function toUpper()
    {
        $this->string = mb_strtoupper( $this->string ,$this->getEncoding() );

        return $this;
    }

    /**
     * Retorna o caracter solicitado pelo parametro index
     *
     * @param integer $index indice do caracter a obter
     * @return char retorna o caracter solicitado
     */
    public function charAt($index)
    {
        return $this->string[ $index ];
    }
   
    /**
     * ObtÃ©m a string
     * 
     * @return dia
     */
    public function getString()
    {
    	return $this->string;
    }

    /**
     * Seta a codificaÃ§Ã£o
     *
     * @param $encoding
     */
    public function setEncoding($encoding)
    {
    	$this->encoding = $encoding;
    }

    /**
     * ObtÃ©m a codificaÃ§Ã£o
     *
     * @return dia
     */
    public function getEncoding()
    {
    	return $this->encoding;
    }

    /**
     * Verifica se a string Ã© UTF8
     *
     * @param string o texto a verificar
     * @return boolean
     */
    public static function isUTF8( $string )
    {
        //return mb_detect_encoding($this->getString(), 'UTF-8', true);
        //return iconv('ISO-8859-1', 'UTF-8', iconv('UTF-8', 'ISO-8859-1', $string ) ) == $string;
        return GString::checkEncoding($string, 'UTF-8');
    }

    /**
     * Verifica se a string Ã© da codificaÃ§Ã£o passada
     *
     * @param string $string
     * @param string $enc
     * @return boolean
     */
    public static function checkEncoding( $string , $enc  )
    {
        return GString::detectEncoding( $string ) == $enc;
    }

    /**
     * Retorna a codifificaÃ§Ã£o da string
     *
     * @param string $string
     * @return string retorna a codifificaÃ§Ã£o da string
     */
    public static function detectEncoding($string)
    {
        $encList = array('UTF-8','ISO-8859-1');

        if ( is_array( $encList ) )
        {
            foreach ( $encList as $line => $enc)
            {
                if ( $enc == 'UTF-8' )
                {
                    if ( @iconv('ISO-8859-1', 'UTF-8', @iconv('UTF-8', 'ISO-8859-1', $string ) ) === $string )
                    {
                        return 'UTF-8';
                    }
                }
                else
                {
                    if ( @iconv('UTF-8', $enc, @iconv( $enc, 'UTF-8', $string ) ) === $string )
                    {
                        return $enc;
                    }
                }
            }
        }
    }

    /**
     * Retorna o tamnho da string
     *
     * @return tamanho da string
     */
    public function length()
    {
        return mb_strlen( $this->getString() , $this->getEncoding() );
    }

    /**
     * Remove os espaÃ§os no inicio e fim do texto
     * 
     * @return GString
     */
    public function trim()
    {
        $this->string = trim($this->string);
        return $this;
    }

    /**
     * Converte a string para caracteres ASCII.
     * Retira acentos e outros caracteres especificos.
     *
     * @return GString
     */
    public function toASCII()
    {
        $this->trim(); //remove espaÃ§os
        $content = $this->string;
        $content = eregi_replace("[ÃÃ€Ã‚ÃƒÃ„Ã¡Ã Ã¢Ã£Ã¤]", "A", $content);
        $content = eregi_replace("[Ã‰ÃˆÃŠÃ‹Ã©Ã¨ÃªÃ«]",   "E", $content);
        $content = eregi_replace("[ÃÃŒÃŽÃÃ­Ã¬Ã®Ã¯]",   "I", $content);
        $content = eregi_replace("[Ã“Ã’Ã”Ã•Ã–Ã³Ã²Ã´ÃµÃ¶]", "O", $content);
        $content = eregi_replace("[ÃšÃ™Ã›ÃœÃºÃ¹Ã»Ã¼]",   "U", $content);
        $content = eregi_replace("[Ã‘Ã±]",         "N", $content);
        $content = eregi_replace("[Ã‡Ã§]",         "C", $content);
        $content = eregi_replace("\+",           "",  $content);

        $this->string = $content;

        $this->toUpper(); //coloca tudo em maisculas

        return $this;
    }

    /**
     * Corta a string de um ponto inicial, considerando ou nÃ£o um tamanho
     *
     * @param integer $start posiÃ§Ã£o inicial
     * @param integer $length quantidade de caracteres atÃ© o corte / tamanho
     * @return GString
     */
    public function sub($start, $length)
    {
        $this->string = mb_substr( $this->string, $start, $length, $this->getEncoding() );

        return $this;
    }
    
    /**
     * Explode a string retornando um array
     * 
     * @param string $delimiter delimitador
     * @return array array com a string explodida
     */
    public function explode( $delimiter )
    {
        return explode( $delimiter, $this->string );
    }
         
    /**
     * FunÃ§Ã£o chamada automaticamente pelo PHP quando precisa converter objeto para String
     * 
     * @return a data no formato do usuÃ¡rio
     */
    public function __toString()
    {
        return $this->string;
    }
    
    /**
     * FunÃ§Ã£o que o miolo chama automaticamente, convertendo o objeto para string
     * 
     * @return a data no formato do usuÃ¡rio
     */
    public function generate()
    {
        return $this->getString();
    }
}
?>
