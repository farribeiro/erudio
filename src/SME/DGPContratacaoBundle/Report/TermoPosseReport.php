<?php

namespace SME\DGPContratacaoBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\DGPBundle\Util\AssinaturasUtil;
use SME\DGPBundle\Entity\Vinculo;
use SME\DGPBundle\Entity\TipoVinculo;
use SME\CommonsBundle\Util\DateTimeUtil;
use SME\CommonsBundle\Util\NumberUtil;

class TermoPosseReport extends PDFDocument {
    
    const FONT_TIMES = 'Times';
    
    private $vinculo;

    public function header($title = '', $subtitle = '') {
        $this->SetMargins(30, 12.5, 20);
        $this->Image(dirname(__FILE__) . '/../Resources/images/brasao-municipal-padronizado.png', $this->w - 82, 12.5);
    }

    public function build() {
        $this->AddPage();
        $this->Ln(20);
        $this->SetMargins(30, 0, 20);
        $this->SetFont(self::FONT_TIMES, 'B', 12);
        $this->Ln(3);
        //$this->MultiCell(0, 6, 'FOLHA ' . ($this->vinculo->getNumeroControle() ? $this->vinculo->getNumeroControle() : '_____') . ' - ' . $this->vinculo->getDataPosse()->format('d/m/Y'), 0, 'J');
        //$this->Ln(15);
        $this->SetFont(self::FONT_TIMES, 'B', 14);
        $this->MultiCell(0, 6, 'TERMO DE POSSE E COMPROMISSO', 0, 'C');
        $this->Ln(15);
        
        $this->SetFont(self::FONT_TIMES, '', 14);
        $this->SetMargins(30, 0, 20);
        $dia = NumberUtil::numeroPorExtenso($this->vinculo->getDataPosse()->format('d'));
        $mes = strtolower(DateTimeUtil::mesPorExtenso($this->vinculo->getDataPosse()->format('m')));
        $ano = NumberUtil::numeroPorExtenso($this->vinculo->getDataPosse()->format('y'));
        $tipo = $this->vinculo->getTipoVinculo()->getId() === TipoVinculo::EFETIVO ? 'efetivo' : 'comissão';
        $this->MultiCell(0, 8, '      Aos ' . $dia . ' dias do mês de '. $mes . ' de dois mil e ' . $ano . ', na Secretaria Municipal de Educação, compareceu o(a) Sr.(a) ' .
            //mb_strtoupper($this->vinculo->getServidor()->getNome(), 'UTF-8') . ', CPF nº ' . $this->vinculo->getServidor()->getCpfCnpj() . ' a fim de tomar posse no cargo de provimento em comissão de' . $tipo . ' de ' . mb_strtoupper($this->vinculo->getCargo()->getNome(), 'UTF-8') .
                mb_strtoupper($this->vinculo->getServidor()->getNome(), 'UTF-8') . ', CPF nº ' . $this->vinculo->getServidor()->getCpfCnpj() . ' a fim de tomar posse no cargo de provimento em comissão de' . $tipo . ' de ' . mb_strtoupper($this->vinculo->getCargo()->getNome(), 'UTF-8') .
            //', da SECRETARIA MUNICIPAL DE EDUCAÇÃO, nomeado(a) pela Portaria nº ' . $this->vinculo->getPortaria() . 
            //', de ' . DateTimeUtil::dataPorExtenso($this->vinculo->getDataNomeacao()) . ', publicada na Edição nº ' . $this->vinculo->getEdicaoJornalNomeacao() .
            //' do Jornal do Município, a qual satisfez as exigências expressas nos artigos 10, 18 e 19, da Lei n.º 2.960, de 03 de abril de 1995, e prestou o seguinte compromisso:', 0, 'J');
            ' o(a) qual satisfez as exigências expressas nos artigos 10, 18 e 19, da Lei n.º 2.960, de 03 de abril de 1995, e prestou o seguinte compromisso:', 0, 'J');
        $this->SetFont(self::FONT_TIMES, 'B', 14);
        $this->SetMargins(30, 0, 20);
        $this->Ln(5);
        //$this->MultiCell(0, 8, 'Por minha honra e pela Pátria, juro cumprir com exatidão e escrúpulos os deveres inerentes ao cargo de provimento em ' . $tipo . ' de ' .
        $this->MultiCell(0, 8, '      Por minha honra e pela Pátria, juro cumprir com exatidão e escrúpulos os deveres inerentes ao cargo de provimento em comissão de ' .
            mb_strtoupper($this->vinculo->getCargo()->getNome(), 'UTF-8') . ', quanto a mim couber, a bem do Município e dos concidadãos.', 0, 'J');
        $this->SetMargins(30, 0, 20);
        $this->Ln(5);
        $this->SetFont(self::FONT_TIMES, '', 14);
        /*$this->MultiCell(0, 8, '         Do que, para constar eu, ' . AssinaturasUtil::SECRETARIO_ADMINISTRACAO . 
            ', Secretário Municipal de Administração de Itajaí, lavrou o presente termo, que vai assinado por mim e pelo(a) Sr.(a) ' . 
            mb_strtoupper($this->vinculo->getServidor()->getNome(), 'UTF-8') . '.', 0, 'J');*/
        $this->MultiCell(0, 8, '         O(a) servidor(a) apresentou, ainda, os documentos exigidos por lei, do que, para constar, eu, ' . AssinaturasUtil::SECRETARIO_EDUCACAO . 
            ', Secretária Municipal de Educação de Itajaí, lavrei o presente termo, que vai assinado por mim e pelo(a) Sr.(a) ' . 
            mb_strtoupper($this->vinculo->getServidor()->getNome(), 'UTF-8') . '.', 0, 'J');
        
        $this->SetMargins(15, 0, 10);
        $this->Ln(30);
        $this->SetFont(self::FONT_TIMES, 'B', 14);
        $this->Cell(90, 8, mb_strtoupper(AssinaturasUtil::SECRETARIO_EDUCACAO, 'UTF-8'), 0, 0, 'C');
        $this->Cell(90, 8, mb_strtoupper($this->vinculo->getServidor()->getNome(), 'UTF-8'), 0, 1, 'C');
        $this->SetFont(self::FONT_TIMES, 'B', 10);
        $this->Cell(90, 4, 'Secretário Municipal de Educação', 0, 0, 'C');
        $this->Cell(90, 4, 'Empossado(a)', 0, 1, 'C');
        $this->Ln(30);
        $this->SetFont(self::FONT_TIMES, 'B', 14);
        $this->Cell(183, 4, mb_strtoupper(AssinaturasUtil::PREFEITO, 'UTF-8'), 0, 0, 'C');
        $this->Ln(6);
        $this->SetFont(self::FONT_TIMES, 'B', 10);
        $this->Cell(183, 4, 'Prefeito Municipal', 0, 1, 'C');
    }
    
    function getVinculo() {
        return $this->vinculo;
    }

    function setVinculo(Vinculo $vinculo) {
        $this->vinculo = $vinculo;
    }
    
}
