<?php

namespace SME\ProtocoloBundle\Reports\Impl;

use SME\ProtocoloBundle\Reports\RequerimentoReport;
use SME\ProtocoloBundle\Entity\Protocolo;

class ProrrogacaoLicencaMaternidadeReport extends RequerimentoReport
{
    
    public function __construct(Protocolo $protocolo) {
        parent::__construct($protocolo);
        $dateFragsTermino = explode('/', $this->params['inicio_licenca']);
        $datetimeInicioLicenca = new \DateTime($dateFragsTermino[2] . '-' . $dateFragsTermino[1] . '-' . $dateFragsTermino[0]);
        $this->params['termino_licenca'] = $datetimeInicioLicenca->modify('+119 day')->format('d/m/Y');
        $datetimeInicioProrrogacao = $datetimeInicioLicenca->modify('+1 day');
        $this->params['inicio_prorrogacao'] = $datetimeInicioProrrogacao->format('d/m/Y');

        if (isset($this->params['final_licenca'])) {
            $this->params['termino_prorrogacao'] = $this->params['final_licenca'];
        } else {
            $this->params['termino_prorrogacao'] = $datetimeInicioProrrogacao->modify('+59 day')->format('d/m/Y');
        }
    }
    
    public function prepareDocument() {
        for ($i = 1; $i <= 3; $i++) {
            $this->via = $i;
            $this->rel($i);
            if ($i <= 2) {
                $this->rel_declaracao($i);
            }
        }
    }

    private function rel($via) {
        $this->titulo = 'REQUERIMENTO DE PRORROGAÇÃO DE LICENÇA MATERNIDADE OU LICENÇA À ADOTANTE';
        $this->AddPage();
        $this->AliasNbPages();

        $this->SetFont('Arial', '', 12);

        $this->Cell(540, 10, 'Ilustríssimo Senhor Secretário Municipal de Educação', 0, 1, 'J');
        $this->Cell(540, 10, 'NESTA', 0, 1, 'J');
        $this->Ln(15);

        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(350,10,'Nome', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'CPF', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'RG', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(350, 20, $this->params['nome'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['cpf'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['rg'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->SetFont('Arial','', 9);
        $this->Cell(75,10,'Matrícula', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(330,10,'Cargo de origem', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(75,10,'Vínculo', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(30,10,'C.H.', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(75, 20, $this->params['matricula'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(330, 20, $this->params['cargo_origem'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(75, 20, $this->params['vinculo'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(30, 20, $this->params['carga_horaria'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        //----------------------------------------------------------------
        $this->Ln(20);
        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(540, 16,'Licença concedida anteriormente:', 0, 'J');
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->MultiCell(540, 16,"Tipo: Licença à {$this->params['tipo_licenca']}   Início: {$this->params['inicio_licenca']}   Término: {$this->params['termino_licenca']}", 0, 'J');
        $this->Ln(15);
        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(540, 16,"Prorrogação:", 0, 'J');
        $this->SetFont('Arial', '', 12);
        $this->Ln(5);
        $this->MultiCell(540, 16,"Início: {$this->params['inicio_prorrogacao']}   Término: {$this->params['termino_prorrogacao']}", 0, 'J');
        $this->Ln(15);
        $texto = 'O servidor efetivo abaixo assinado, requer a Vsª se digne conceder PRORROGAÇÃO DA LICENÇA MATERNIDADE OU LICENÇA À ADOTANTE,' .
                ' com fulcro na Lei Complementar nº 180 de 17/12/2010.';
        $this->MultiCell(540, 16, $texto, 0, 'J');

        $this->Ln(20);
        $this->MultiCell(540, 16, 'Nestes termos', 0, 'J');
        $this->MultiCell(540, 16, 'Pede Deferimento', 0, 'J');

        $this->Ln(40);
        $this->MultiCell(540, 16, '____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(540, 16, 'Assinatura do(a) Requerente', 0, 'C');

        $this->Ln(50);
        $dia = date("d");
        $mes = self::mesNumericoParaExtenso( date("m") );
        $ano = date("Y");
        $this->MultiCell(540, 14, "Itajaí, $dia de $mes de $ano", 0, 'R');

        if ($via == 1 || $via == 3) {
            $this->Ln(30);
            $this->caixaProtocolo();
        }
    }

    private function rel_declaracao($via) {
        $this->titulo = 'DECLARAÇÃO PARA CONCESSÃO DA PRORROGAÇÃO DE LICENÇA MATERNIDADE OU LICENÇA À ADOTANTE';
        $this->AddPage();
        $this->AliasNbPages();
        
        $this->SetFont('Arial','B', 12);
        $this->Ln(15);
        $this->Write(16,'EU,');
        $this->Ln(5);
        
        $this->SetFont('Arial','', 9);
        $this->Cell(350,10,'', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'CPF', '', 0, 'J');
        $this->Cell(10,10,' ', '', 0, 'J');
        $this->Cell(85,10,'RG', '', 1, 'J');

        $this->SetFont('Arial', '', 10);
        $this->Cell(350, 20, $this->params['nome'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['cpf'], 'LTBR', 0, 'J');
        $this->Cell(10, 20,'','', 0, 'J');
        $this->Cell(85, 20, $this->params['rg'], 'LTBR', 1, 'J');
        $this->Ln(5);
        
        $this->Ln(15);
        $this->SetFont('Arial','', 12);
        $this->MultiCell(540, 16,'de acordo com a Lei Complementar nº 180 de 17/12/2010, que estabelece:',0, 'J');
        $this->Ln(10);
        $this->SetFont('Arial', '', 9);
        $this->Cell(120,60,'','',0,'J');
        $this->MultiCell(420,10,'Art. 10 À servidora efetiva gestante é assegurada licença para repouso pelo período de 120 (cento e vinte) dias '
                . 'consecutivos, a partir da data de nascimento da criança, mediante apresentação da certidão de nascimento, prorrogáveis por 60 (sessenta) dias.', 0, 'J');
        $this->Cell(120,60,'','',0,'J');
        $this->MultiCell(420,10,'§ 9º A prorrogação da licença gestação deverá ser requerida antes do término dos 120 (cento e vinte) dias, desde '
                . 'que a servidora ateste que não exerce atividade remunerada e a criança não está matriculada em creche ou organização similar, sob pena de perda do direito de usurfruto da prorrogação.', 0, 'J');
        $this->Ln(10);
        $this->SetFont('Arial','', 12);
        $this->MultiCell(540, 16,'declaro que não exerço e não exercerei qualquer atividade remunerada no período de prorrogação da referida licença e também '
                . "não mantenho ou manterei no mesmo período meu(minha) filho(a) {$this->params['nome_crianca']} em creche ou instituição congênere.",0, 'J');
        $this->MultiCell(540, 16,'Por ser verdade, firmo a presente declaração.',0, 'J');
        $this->Ln(50);
        $this->MultiCell(540, 16, '____________________________________', 0, 'C');
        $this->Ln(5);
        $this->MultiCell(540, 16, 'Assinatura do(a) Requerente', 0, 'C');

        $this->Ln(50);
        $this->dataCadastro();
    }
    
}

?>

