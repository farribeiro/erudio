<?php

namespace SME\ProtocoloBundle\Reports;

use Symfony\Component\HttpFoundation\Response;
use SME\CommonsBundle\Entity\PessoaFisica;
use SME\ProtocoloBundle\Entity\Protocolo;

require_once dirname(__FILE__) . '/lib/fpdf.php';
require_once dirname(__FILE__) . '/lib/GString.php';


/**
 * Classe responsável por gerar o documento do requerimento. Cada requerimento disponível
 * no sistema deve prover uma classe que estenda esta, implementando os métodos de carregamento
 * dos dados e preparação do documento.
 */
abstract class RequerimentoReport extends \FPDF {

    protected $protocolo;
    protected $params;
    protected $titulo = '';
    protected $subtitulo = '';
    
    protected $via;
    protected $vias = array('SME', 'ADM', 'Requerente');
    protected $assinaturas = array('diretoriaDGP' => 'Profª Patrícia A. A. Obelar Coelho', 'secretario' => 'Profª MSc. Elisete Furtado Cardoso');

    /**
     * Construtor padrão de um requerimento, recebe uma conexão com o banco para
     * carregar as informações dos campos
     * @param ConexaoBanco $conexao
     * @param string $protocolo 
     */
     public function __construct(Protocolo $protocolo) {
        parent::FPDF('P', 'pt', 'A4');
        $this->protocolo = $protocolo;
        $this->params = array(
            'nome' => $protocolo->getRequerente()->getNome(),
            'cpf' => $protocolo->getRequerente()->getCpfCnpj(),
            'rg' => $protocolo->getRequerente()->getNumeroRg(),
            'dataNascimento' => $protocolo->getRequerente()->getDataNascimento() ? $protocolo->getRequerente()->getDataNascimento()->format('d/m/Y') : ''
        );
        foreach($protocolo->getInformacoesDocumento() as $param) {
            $this->params[$param->getNomeCampo()] = $param->getValor();
        }
        
        /** 
         * Trecho adaptado para lidar com requerimentos legados do sistema antigo, os quais não salvavam os dados de contato da pessoa no documento, pois eles sempre importavam os dados atuais.
         * O problema da abordagem antiga é que ao tentar reproduzir um requerimento antigo exatamente como ele era, tal documento poderia não ser gerado com as informações originais.
         **/
        $this->params['email'] = array_key_exists('email', $this->params) ? $this->params['email'] : $protocolo->getRequerente()->getEmail();
        if($protocolo->getRequerente()->getTelefone()) {
            $this->params['telefone'] = array_key_exists('telefone', $this->params) ? $this->params['telefone'] : $protocolo->getRequerente()->getTelefone()->getNumero();
        } else {
            $this->params['telefone'] = '';
        }
        if($protocolo->getRequerente()->getCelular()) {
            $this->params['celular'] = array_key_exists('celular', $this->params) ? $this->params['celular'] : $protocolo->getRequerente()->getCelular()->getNumero();
        } else {
            $this->params['celular'] = '';
        }
        if(!$this->getProtocolo()->getSolicitacao()->getExterno()) {
            $this->params['logradouro'] = array_key_exists('logradouro', $this->params) ? $this->params['logradouro'] : $protocolo->getRequerente()->getEndereco()->getLogradouro();
            $this->params['numero'] = array_key_exists('numero', $this->params) ? $this->params['numero'] : $protocolo->getRequerente()->getEndereco()->getNumero();
            $this->params['complemento'] = array_key_exists('complemento', $this->params) ? $this->params['complemento'] : $protocolo->getRequerente()->getEndereco()->getComplemento();
            $this->params['bairro'] = array_key_exists('bairro', $this->params) ? $this->params['bairro'] : $protocolo->getRequerente()->getEndereco()->getBairro();
            $this->params['cep'] = array_key_exists('cep', $this->params) ? $this->params['cep'] : $protocolo->getRequerente()->getEndereco()->getCep();
            $this->params['cidade'] = array_key_exists('cidade', $this->params) ? $this->params['cidade'] : $protocolo->getRequerente()->getEndereco()->getCidade()->getNome();
        }
    }

    /**
     * Método responsável por preparar o documento para posterior geração do arquivo PDF. 
     */
    abstract function prepareDocument();

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
    final public function Text($x, $y, $txt) {
        parent::Text($x, $y, \GString::construct($txt, 'ISO-8859-1'));
    }

    /**
     * Sobrescrita de função da classe FPDF para incluir compatibilidade com UTF-8
     */
    final public function Write($h, $txt, $link = '') {
        parent::Write($h, \GString::construct($txt, 'ISO-8859-1'), $link);
    }

    /**
     * Método de geração do cabeçalho do requerimento, que deve constar em todas as páginas
     * do documento.
     */
    function header() {
        $this->Image(dirname(__FILE__) . '/../Resources/images/logo-prefeitura-itajai.jpg', $this->w - 150, 10, '120%');
        $this->SetFont('Arial', 'B', 16);
        $this->SetY(10);
        $this->Cell($this->w - 50, 20, 'SECRETARIA MUNICIPAL DE EDUCAÇÃO');
        $this->Ln(10);
        $this->SetFont('Arial', '', 14);
        $this->Cell($this->w - 50, 40, 'Diretoria de Gestão de Pessoas');
        $this->Ln(30);
        $this->Line($this->x, $this->y, $this->w - 30, $this->y);
        if ($this->getTitulo() != '') {
            $this->SetFont('Arial', 'B', 14);
            $this->MultiCell($this->w - 50, 20, $this->titulo, '', 'C');
        }
        if ($this->getSubtitulo() != '') {
            $this->SetFont('Arial', '', 12);
            $this->MultiCell($this->w - 50, 20, $this->subtitulo, '', 'C');
        }
        $this->SetFont('Arial', '', 8);
        $this->Cell($this->w - 50, 20, '(' . $this->via . 'ªVia/' . $this->vias[$this->via - 1] . ')', 0, 1, 'R');
        $this->SetFont('Arial', '', 12);
    }

    /**
     * Método de geração do rodapé do requerimento, que deve constar em todas as páginas
     * do documento.
     */
    function footer() {
        $numero = true;
        if ($numero) {
            $pag = 'Página:' . $this->PageNo() . '/{nb}';
        } else {
            $pag = '';
        }
        $this->SetY(-50);
        $this->Line($this->x, $this->y, $this->w - 30, $this->y);
        $this->SetY(-45);
        $this->SetFont('Arial', 'B', 8);
        $YY = $this->y;
        $this->Cell(0, 10, 'Av. Ver. Abrahão João Francisco Nº 3.855 - Ressacada - Itajaí/SC', 0, 0, 'R');
        $this->SetY($YY);
        $this->SetX(25);
        $this->Cell(0, 10, $pag, 0, 0, 'L');
        $this->Ln(10);
        $this->Cell(0, 10, 'Fone/Fax: (47) 3249-3304 dgp@itajai.sc.gov.br', 0, 0, 'R');
        $this->Ln(10);
        $this->Cell(0, 10, 'http://educacao.itajai.sc.gov.br', 0, 0, 'R');
        $this->SetFont('Arial', '', 12);
    }

    /**
     * 
     * @param type $fontSize
     */
    function caixaProtocolo($fontSize = 10) {
        $this->SetFont('Arial', 'B', $fontSize);
        $L = 270;
        $X = 30;
        $this->SetX($X);
        $this->MultiCell($L, $fontSize + 7, "Protocolo: {$this->getProtocolo()->getProtocolo()}", 'LTR', 'J');

        $this->SetX($X);
        $this->MultiCell($L, $fontSize + 7, 'Recebido por:', 'LTR', 'J');

        $this->SetX($X);
        $this->MultiCell($L, $fontSize + 7, 'Assinatura do Recebedor:', 'LTR', 'J');

        $this->SetX($X);
        $this->MultiCell($L, $fontSize + 7, 'Recebido em: ______/_____/__________', 'LTRB', 'J');
        $this->SetFont('Arial', '', 12);
    }

    function dataCadastro($width = 540, $height = 14) {
        $data = $this->protocolo->getDataCadastro() ? $this->protocolo->getDataCadastro() : new \DateTime();
        $dia = $data->format('d');
        $mes = self::mesNumericoParaExtenso($data->format('m'));
        $ano = $data->format('Y');
        $this->MultiCell($width, $height, "Itajaí, $dia de $mes de $ano", 0, 'R');
    }
    
    public function render() {
        $this->prepareDocument();
        return new Response(
                $this->Output(), 
                200,
                array('Content-type' => 'application/pdf')
        );
    }
    
    public static function mesNumericoParaExtenso($numerico)
    {
        switch($numerico) {
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
        }
    }

    public function getProtocolo() {
        return $this->protocolo;
    }
    
    public function getSolicitante() {
        return $this->protocolo->getRequerente();
    }

    public function getRequerente() {
        return $this->protocolo->getRequerente();
    }
    
    public function getParams() {
        return $this->params;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getSubtitulo() {
        return $this->subtitulo;
    }

    public function setSubtitulo($subtitulo) {
        $this->subtitulo = $subtitulo;
    }

}
