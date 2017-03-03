<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    @author Municipio de Itajaí - Secretaria de Educação - DITEC         *
 *    @updated 30/06/2016                                                  *
 *    Pacote: Erudio                                                       *
 *                                                                         *
 *    Copyright (C) 2016 Prefeitura de Itajaí - Secretaria de Educação     *
 *                       DITEC - Diretoria de Tecnologias educacionais     *
 *                        ditec@itajai.sc.gov.br                           *
 *                                                                         *
 *    Este  programa  é  software livre, você pode redistribuí-lo e/ou     *
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme     *
 *    publicada pela Free  Software  Foundation,  tanto  a versão 2 da     *
 *    Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.    *
 *                                                                         *
 *    Este programa  é distribuído na expectativa de ser útil, mas SEM     *
 *    QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-     *
 *    ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-     *
 *    sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.     *
 *                                                                         *
 *    Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU     *
 *    junto  com  este  programa. Se não, escreva para a Free Software     *
 *    Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
 *    02111-1307, USA.                                                     *
 *                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace PDFBundle\Report;

require_once dirname(__FILE__) . '/lib/PDF_MC_Table.php';
require_once dirname(__FILE__) . '/lib/GString.php';

abstract class PDFDocument extends \PDF_MC_TABLE {

    const FONT_DEFAULT_TYPE = 'helvetica';
    const FONT_DEFAULT_SIZE = 11;
    
    private $headerX = 10;
    private $headerY = 10;
    
    public $B;
    public $I;
    public $U;
    public $HREF;
    
    public function __construct($orientation = 'P', $sheet = 'A4', $measure = 'mm') {
        parent::__construct($orientation, $measure, $sheet);
    }
    
    abstract function build();

    function header($title, $subtitle = '') {
        $this->setX($this->headerX);
        $this->SetY($this->headerY);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 16);
        if($this->w > 250) {
            $this->Cell(140, 15, $title, 0, 1, 'L');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
            $this->SetY(21);
            $this->Cell(140, 5, $subtitle, 0, 1, 'L');
        } else {
            $this->Cell(95, 15, $title, 'RB', 1, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
            $this->Cell(95, 5, $subtitle, 'R', 0, 'R');
            $this->Cell(95, 5, '', 'T', 0, 'L');
            $this->Ln(5);
        }
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', self::FONT_DEFAULT_SIZE);
    }
    
    /**
     * Sobrescrita de função da classe FPDF para incluir compatibilidade com UTF-8
     */
    final function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '') {
        parent::Cell($w, $h, \GString::construct($txt, 'ISO-8859-1'), $border, $ln, $align, $fill, $link);
    }

    /**
     * Sobrescrita de função da classe FPDF para incluir compatibilidade com UTF-8
     */
    final function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false) {
        parent::MultiCell($w, $h, $txt, $border, $align, $fill);
    }

    /**
     * Sobrescrita de função da classe FPDF para incluir compatibilidade com UTF-8
     */
    final function Text($x, $y, $txt) {
        parent::Text($x, $y, \GString::construct($txt, 'ISO-8859-1'));
    }

    /**
     * Sobrescrita de função da classe FPDF para incluir compatibilidade com UTF-8
     */
    final function Write($h, $txt, $link = '') {
        $this->Write($h, \GString::construct($txt, 'ISO-8859-1'), $link);
    }
    
    final public function render() {
        $this->build();
        $this->Output();
    }
    
    public function printMonth($mesNumerico) {
        switch($mesNumerico) {
            case 1: return 'janeiro';
            case 2: return 'fevereiro';
            case 3: return 'março';
            case 4: return 'abril';
            case 5: return 'maio';
            case 6: return 'junho';
            case 7: return 'julho';
            case 8: return 'agosto';
            case 9: return 'setembro';
            case 10: return 'outubro';
            case 11: return 'novembro';
            case 12: return 'dezembro';    
        }
    }
    
    function WriteHTML($html) {
        // HTML parser
        $html = str_replace("\n", ' ', $html);
        $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                // Text
                if($this->HREF)
                    $this->PutLink($this->HREF, $e);
                else
                    $this->Write(5,$e);
            }
            else
            {
                // Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    // Extract attributes
                    $a2 = explode(' ',$e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr) {
        switch($tag) {
            case 'strong':
                $this->SetStyle('B',true);
                break;
            case 'em':
                $this->SetStyle('I',true);
                break;
            case 'B':
            case 'I':
            case 'U':
                $this->SetStyle($tag, true);
                break;
            case 'A':
            case 'a':
                $this->HREF = $attr['HREF'];
                break;
        }
    }

    function CloseTag($tag) {
        switch($tag) {
            case 'strong':
                $this->SetStyle('B', false);
                break;
            case 'em':
                $this->SetStyle('I', false);
                break;
            case 'B':
            case 'I':
            case 'U':
                $this->SetStyle($tag, false);
                break;
            case 'A':
            case 'a':
                $this->HREF = $attr['HREF'];
                break;
        }
    }

    function SetStyle($tag, $enable) {
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach(array('B', 'I', 'U') as $s)
        {
            if($this->$s>0) {
                $style .= $s;
            }
        }
        $this->SetFont('', $style);
    }

    function PutLink($URL, $txt) {
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5, $txt, $URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
    
    function getHeaderX() {
        return $this->headerX;
    }

    function getHeaderY() {
        return $this->headerY;
    }

    function setHeaderX($headerX) {
        $this->headerX = $headerX;
    }

    function setHeaderY($headerY) {
        $this->headerY = $headerY;
    }
    
}
