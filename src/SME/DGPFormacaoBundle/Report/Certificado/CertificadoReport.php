<?php

namespace SME\DGPFormacaoBundle\Report\Certificado;

use SME\PDFBundle\Report\PDFDocument;
use SME\DGPFormacaoBundle\Entity\Matricula;
use SME\CommonsBundle\Util\DateTimeUtil;
use SME\CommonsBundle\Util\DocumentosUtil;

class CertificadoReport extends PDFDocument {
    
    const FONT_DEFAULT_TYPE = 'Times';
    protected $header = true;
    
    private $matricula;
    
    public function header($title = 'CERTIFICADO', $subtitle = '') {
        if($this->header) {
            $this->Image(
                dirname(__FILE__) . '/../../Resources/images/brasao-municipal.jpg', 
                $this->w - 180, 10, 64, 16
            );
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
            $this->SetY(28);
            $this->SetX($this->w - 180);
            $this->Cell(64, 8, 'SECRETARIA DE EDUCAÇÃO', 'T', 1, 'C');
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 28);
            $this->Ln(10);
            $this->SetX(20);
            $this->MultiCell($this->w - 40, 18, $title, '', 'C');
        }
    }
    
    public function build() {
        $this->renderFront();
        $this->renderBack();
    }
    
    protected function renderFront() {
        $this->AddPage('L');
        $this->AliasNbPages();
    	$this->Ln(12);
        
        $nome = $this->matricula->getPessoa()->getNome();
        $cpf = DocumentosUtil::formatarCpf($this->matricula->getPessoa()->getCpfCnpj());
        $formacao = $this->matricula->getFormacao()->getNomeCertificado();
        $inicio = $this->matricula->getFormacao()->getDataInicioFormacao()->format('d/m/Y');
        $fim = $this->matricula->getFormacao()->getDataTerminoFormacao()->format('d/m/Y');
        $ch = $this->matricula->getFormacao()->getCargaHoraria();
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 14);
        $this->SetX(20);
        $this->MultiCell($this->w - 40, 8, 'Certificamos que', '', 'C');
        $this->SetX(20);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 24);
        $this->MultiCell($this->w - 40, 24, $nome, '', 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 14);
        $this->SetX(20);
        $this->MultiCell($this->w - 40, 8, "CPF: {$cpf}, participou da formação"
            . " continuada \"{$formacao}\", realizada entre os dias {$inicio} e {$fim} com carga horária de {$ch} horas.", '', 'C');
        $this->SetY(130);
        $this->SetX(20);
        $this->MultiCell($this->w - 40, 8, 'Itajaí, ' . DateTimeUtil::dataPorExtenso($this->matricula->getFormacao()->getDataTerminoFormacao()), '', 'C');
        $this->Image(dirname(__FILE__) . '/../../Resources/images/assinaturas/diretor-dgp.jpg', 60, $this->y, 46, 29);
        $this->Image(dirname(__FILE__) . '/../../Resources/images/assinaturas/secretario.png', $this->w - 106, $this->y, 46, 29);
        $this->Ln(20);
        
        $assinaturasY = $this->y;
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell($this->w / 2, 8, 'Profª Patrícia A. A. Obelar Coelho', '', 0, 'C');
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell($this->w / 2, 8, 'Diretora de Gestão de Pessoas', '', 0, 'C');
        
        $this->SetY($assinaturasY);
        $this->SetX($this->w - 155);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->Cell($this->w / 2, 8, 'Profª MSc Elisete Furtado Cardoso', '', 0, 'C');
        $this->Ln(5);
        $this->SetX($this->w - 155);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell($this->w / 2, 8, 'Secretária de Educação', '', 0, 'C');
        
        $this->Ln(18);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'I', 10);
        $this->MultiCell($this->w - 20, 8, 'Verifique a autenticidade em http://educacao.itajai.sc.gov.br/certificados | Número de matrícula: ' . $this->matricula->getId(), '', 'C');
    }
    
    protected function renderBack() {
        if(!$this->matricula->getFormacao()->getEncontros()->isEmpty()) {
            $this->header = false;
            $this->AddPage('L');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
            $this->SetX(10);
            $this->SetY(27);
            $this->Ln(15);
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
            $this->SetAligns(array('C', 'C', 'C'));

            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
            $this->SetWidths(array(30,160,30));
            $this->setX(40);
            $this->Row(array('Data', 'Tema / Conteúdos', 'Carga Horária'));
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
            $this->SetAligns(array('C', 'L', 'C'));
            foreach($this->matricula->getFormacao()->getEncontros() as $encontro) {
                $this->setX(40);
                $this->Row(array(
                    $encontro->getDataRealizacao()->format('d/m/Y'), 
                    $encontro->getConteudo(), 
                    $encontro->getCargaHoraria() . 'h'
                ));
            }
        }
    }
    
    public function getMatricula() {
        return $this->matricula;
    }

    public function setMatricula(Matricula $matricula) {
        $this->matricula = $matricula;
    }
    
}
