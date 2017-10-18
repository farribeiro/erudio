<?php

namespace SME\DGPContratacaoBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\DGPBundle\Entity\Vinculo;
use SME\DGPBundle\Entity\TipoVinculo;
use SME\DGPContratacaoBundle\Entity\TipoProcesso;

class FichaCadastralReport extends PDFDocument {
    
    private $vinculo;
    private $atendente;
    
    public function build() {
        $this->AddPage();
        $this->AliasNbPages();

        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 14);
        switch($this->vinculo->getTipoVinculo()->getId()) {
            case TipoVinculo::EFETIVO:
                $tipoVinculo = 'EFETIVAÇÃO'; break;
            case TipoVinculo::ACT:
                $tipoVinculo = 'CONTRATAÇÃO'; break;
            case TipoVinculo::COMISSIONADO:
                $tipoVinculo = 'CARGO EM COMISSÃO'; break;
        }
        $hoje = new \DateTime();
        $this->MultiCell($this->w, 8, 'INFORMAÇÕES DO SERVIDOR PARA ' . $tipoVinculo . ' - Nº ' . ($this->vinculo->getNumeroControle() ? $this->vinculo->getNumeroControle() : '____') . '/' . $hoje->format('Y'), 0, 'C');
        $this->Ln(1);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(35, 6, 'Nome:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(155, 6, $this->vinculo->getServidor()->getNome(), 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(35, 6, 'Nacionalidade:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(55, 6, $this->vinculo->getServidor()->getNacionalidade(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(8, 6, 'Naturalidade:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(49, 6, $this->vinculo->getServidor()->getNaturalidade(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(8, 6, 'Raça/Cor:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(20, 6, $this->vinculo->getServidor()->getRaca() ? $this->vinculo->getServidor()->getRaca()->getNome() : 'Não informado', 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(35, 6, 'RG:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(25, 6, $this->vinculo->getServidor()->getNumeroRg(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(38, 6, 'Orgão Expedidor:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(5, 6, $this->vinculo->getServidor()->getOrgaoExpedidorRg(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(52, 6, 'CPF:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(35, 6, $this->vinculo->getServidor()->getCpfCnpj(), 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(35, 6, 'Data de Nascimento:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(25, 6, $this->vinculo->getServidor()->getDataNascimento()->format('d/m/Y'), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(38, 6, 'Estado Civil:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(30, 6, $this->vinculo->getServidor()->getEstadoCivil()->getNome(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(27, 6, 'PIS/PASEP:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(35, 6, $this->vinculo->getServidor()->getPisPasep(), 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(35, 6, 'Filiação Mãe:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(155, 6, $this->vinculo->getServidor()->getNomeMae(), 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(35, 6, 'Filiação Pai:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(155, 6, $this->vinculo->getServidor()->getNomePai(), 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(35, 6, 'Título Eleitor:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(28, 6, $this->vinculo->getServidor()->getNumeroTituloEleitor(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(35, 6, 'Zona:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(15, 6, $this->vinculo->getServidor()->getZonaTituloEleitor(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(42, 6, 'Seção:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(25, 6, $this->vinculo->getServidor()->getSecaoTituloEleitor(), 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(35, 6, 'Carteira de Trabalho:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(30, 6, $this->vinculo->getServidor()->getCarteiraTrabalhoNumero(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(7, 6, 'UF:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(12, 6, $this->vinculo->getServidor()->getCarteiraTrabalhoEstado() ? $this->vinculo->getServidor()->getCarteiraTrabalhoEstado()->getSigla() : '', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(14, 6, 'Série:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(33, 6, $this->vinculo->getServidor()->getCarteiraTrabalhoSerie(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(25, 6, 'Expedição:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(22, 6, $this->vinculo->getServidor()->getCarteiraTrabalhoDataExpedicao() instanceof \DateTime
            ? $this->vinculo->getServidor()->getCarteiraTrabalhoDataExpedicao()->format('d/m/Y') : '', 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(35, 6, 'Endereço:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(108, 6, $this->vinculo->getServidor()->getEndereco()->getLogradouro(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(14, 6, 'Nº', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(33, 6, $this->vinculo->getServidor()->getEndereco()->getNumero(), 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(35, 6, 'Complemento:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(155, 6, $this->vinculo->getServidor()->getEndereco()->getComplemento(), 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(35, 6, 'Bairro:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(45, 6, $this->vinculo->getServidor()->getEndereco()->getBairro(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(18, 6, 'Cidade:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(37, 6, "{$this->vinculo->getServidor()->getEndereco()->getCidade()->getNome()}-{$this->vinculo->getServidor()->getEndereco()->getCidade()->getEstado()->getSigla()}", 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(21, 6, 'CEP:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(34, 6, $this->vinculo->getServidor()->getEndereco()->getCep(), 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(35, 6, 'Telefone:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(22, 6, $this->vinculo->getServidor()->getTelefone()->getNumero(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'B',11);
        $this->Cell(29, 6, 'Celular:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(34, 6, $this->vinculo->getServidor()->getCelular()->getNumero(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(50, 6, $this->vinculo->getBancoContaBancaria() ? $this->vinculo->getBancoContaBancaria()->getNome() : '', 0, 1, 'L');
        
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(35, 6, 'E-mail:', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(89, 6, $this->vinculo->getServidor()->getEmail(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(5, 6, 'C/C', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(30, 6, $this->vinculo->getNumeroContaBancaria(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->Cell(5, 6, 'Ag.', 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(10, 6, $this->vinculo->getAgenciaContaBancaria(), 0, 1, 'L');
        
        $this->informacoesCargo();
        
        //Informações de admissão e processo
        if($this->vinculo->getInscricaoVinculacao()) {
            $this->informacoesProcesso($this->vinculo->getInscricaoVinculacao()->getCargo()->getProcesso());
        } elseif($this->vinculo->getTipoVinculo()->getId() === TipoVinculo::COMISSIONADO) {
            $this->informacoesNomeacao();
        } else {
            $this->informacoesNomeacao();
            $this->SetFont(self::FONT_DEFAULT_TYPE,'I',11);
            $this->MultiCell(190, 8, 'Informações sobre o processo de admissão indisponíveis', 0, 'C');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        }
        
        /**  Formações */
        if (!$this->vinculo->getServidor()->getFormacoes()->isEmpty()) {
            $this->Ln(2);
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
            $this->MultiCell(190, 6, 'Formação Profissional:', 0, 'L');
            $this->setFont(self::FONT_DEFAULT_TYPE,'B',11);
            $this->Cell(40, 5,'Nível', 1, 0, 'C');
            $this->Cell(150, 5,'Curso', 1, 1, 'C');
            foreach($this->vinculo->getServidor()->getFormacoes() as $formacao) {
                $this->setFont(self::FONT_DEFAULT_TYPE,'',11);
                $this->Cell(40, 6, $formacao->getGrauFormacao()->getNome(),1, 0, 'C');
                $this->Cell(150, 6, $formacao->getNome(),1, 1, 'C');
            }
            
        }
        
        /** Alocações */
        $this->SetFont(self::FONT_DEFAULT_TYPE,'B', 14);
        $this->Ln(2);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
        $this->MultiCell(190, 6, 'Alocação:', 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 11);
	if(count($this->vinculo->getAlocacoes()) == 0) {
            $this->Cell(35, 6, ' Local de Trabalho:', 'LT', 0, 'R');
            $this->Cell(155, 6, '' ,'RT', 1, 'L');
            $this->Cell(100, 6," Turno: ", 'L', 0, 'L');
            if($this->vinculo->getTipoVinculo()->getId() === TipoVinculo::ACT) {
                $this->Cell(55, 6, 'Turma: ','',0,'L');
            } else {
		$this->Cell(55, 6,'','',0,'L');
            }
            $this->Cell(35, 6, "Carga horária: ",'R',1,'L');
            if($this->vinculo->getTipoVinculo()->getId() === TipoVinculo::EFETIVO) {
                $this->Cell(190, 6, ' Lotação: ','LRB', 1,'L');
            }
            else {
                $this->Cell(190, 6, ' Motivo:','LR', 1,'J');
                $this->MultiCell(190, 6, '', 'RLB', 'L');
            }
            $this->Ln(5);
	}
        foreach($this->vinculo->getAlocacoes() as $atuacao) {
            $this->Cell(35, 6, 'Local de Trabalho:','LT',0,'R');
            $this->Cell(155, 6,$atuacao->getLocalTrabalho()->getPessoaJuridica()->getNome(),'RT',1,'L');
            $this->Cell(100, 6, ' Turno: ' . ($atuacao->getPeriodo() ? $atuacao->getPeriodo()->getNome() : ''), 'L', 0, 'L');
            if($this->vinculo->getTipoVinculo()->getId() === TipoVinculo::ACT) {
                $this->Cell(55, 6,'Turma: ' . $atuacao->getObservacao(),'', 0, 'L');
            } else {
		$this->Cell(55, 6, '','',0,'L');
            }
            $this->Cell(35, 6, "Carga horária: {$atuacao->getCargaHoraria()}", 'R', 1, 'L');
            switch($this->vinculo->getTipoVinculo()->getId()) {
                case TipoVinculo::EFETIVO:
                    $this->Cell(190, 6,' Lotação: ' . ($atuacao->getLocalLotacao() ? $atuacao->getLocalLotacao()->getNome() : ''),'LR',1,'L');
                    $this->Cell(190, 1, '', 'RLB', 1, 'L');
                    break;
                case TipoVinculo::ACT:
                    $this->Cell(190, 6, ' Motivo:', 'LR', 1, 'J');
                    $this->MultiCell(190, 6, ' ' . $atuacao->getMotivoEncaminhamento(), 'RLB', 'L');
                    break;
                default:
                    $this->Cell(190, 1, '', 'RLB', 1, 'L');
            }
            $this->Ln(5);
        }
        
        $this->informacoesEnquadramento();
        
        /** Assinatura */
        $this->Ln(12);
        $this->Cell(95, 6,'',0,0);
        $this->Cell(95, 6,"__________________________________________", 0, 1, 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',10);
        $this->Cell(95, 6, '', 0, 0);
        $this->Cell(95, 6,'Assinatura do Servidor', 0, 1, 'C');
        if($this->atendente) {
            $this->SetFont('Arial', '', 9);
            $hoje = new \DateTime();
            $this->MultiCell(0, 8, "Atendido por {$this->getAtendente()->getNome()} em {$hoje->format('d/m/Y')}", 0, 'L');
            $this->SetFont('Arial', 'B', 8);
        }
    }
    
    private function informacoesProcesso($processo) {
        if($this->vinculo->getInscricaoVinculacao()->getCargo()->getProcesso()->getNumeroEdital() < 10) {
            $edital = '00' . $this->vinculo->getInscricaoVinculacao()->getCargo()->getProcesso()->getNumeroEdital();
        } else {
            $edital = '0' . $this->vinculo->getInscricaoVinculacao()->getCargo()->getProcesso()->getNumeroEdital();
        }
        if($processo->getTipoProcesso()->getId() != TipoProcesso::CHAMADA_PUBLICA) {
            $this->SetFont(self::FONT_DEFAULT_TYPE,'B',11);
            $this->Cell(35, 6, $processo->getTipoProcesso()->getNome() . ':', 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
            $this->Cell(50, 6, 'Edital ' . $edital . '/' . $this->vinculo->getInscricaoVinculacao()->getCargo()->getProcesso()->getAnoEdital(), 0, 0, 'L');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'B',11);
            $this->Cell(15, 6, "Validade:", 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
            $this->Cell(37, 6, $this->vinculo->getInscricaoVinculacao()->getCargo()->getProcesso()->getDataEncerramento()->format('d/m/Y'), 0, 0, 'L');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'B',11);
            $this->Cell(20, 6, "Classificação:", 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
            $this->Cell(53, 6, $this->vinculo->getInscricaoVinculacao()->getClassificacao()  . 'ª', 0, 1, 'L');
        } else {
            $this->SetFont(self::FONT_DEFAULT_TYPE,'B',11);
            $this->Cell(75, 6, $processo->getTipoProcesso()->getNome() . ':', 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
            $this->Cell(48, 6, 'Edital ' . $edital . '/' . $this->vinculo->getInscricaoVinculacao()->getCargo()->getProcesso()->getAnoEdital(), 0, 0, 'L');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'B',11);
            $this->Cell(35, 6, "Validade:", 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
            $this->Cell(30, 6, $this->vinculo->getInscricaoVinculacao()->getCargo()->getProcesso()->getDataEncerramento()->format('d/m/Y'), 0, 1, 'L');
        }
        $this->informacoesNomeacao();
        if($processo->getDecretoHomologacao()) {
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
            $this->Cell(35, 6, 'Homologação:', 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 11);
            $docHomologacao = $this->vinculo->getTipoVinculo()->getNome() == 'Efetivo' ? 'Decreto ' : 'Edital ';
            $this->Cell(155, 6, $docHomologacao . $processo->getDecretoHomologacao() . ', de ' . $processo->getDataHomologacao()->format('d/m/Y') 
                . ', publicado em ' . $processo->getDataPublicacaoHomologacao()->format('d/m/Y') . ', Edição JM nº ' 
                . $processo->getEdicaoJornalHomologacao(), 0, 1, 'L');
        }
        if($processo->getDecretoProrrogacao()) {
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
            $this->Cell(35, 6, "Prorrogação:", 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 11);
            $this->Cell(155, 6, 'Decreto ' . $processo->getDecretoProrrogacao() . ', de ' . $processo->getDataProrrogacao()->format('d/m/Y') 
                . ', publicado em ' . $processo->getDataPublicacaoProrrogacao()->format('d/m/Y') . ', Edição JM nº ' 
                . $processo->getEdicaoJornalProrrogacao(), 0, 1, 'L');
        }
    }
    
    private function informacoesCargo() {
        if($this->vinculo->getTipoVinculo()->getId() != TipoVinculo::COMISSIONADO) {
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B',11);
            $this->Cell(35, 6, 'Situação Profissional:', 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
            $this->Cell(155, 6, "{$this->vinculo->getTipoVinculo()->getNome()} - {$this->vinculo->getCargo()->getNomeOficial()}", 0, 1, 'L');

            $this->SetFont(self::FONT_DEFAULT_TYPE,'B',11);
            $this->Cell(35, 6, 'Cargo/Disciplina:', 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
            $this->Cell(115, 6, $this->vinculo->getInscricaoVinculacao() 
                    ? $this->vinculo->getInscricaoVinculacao()->getCargo()->getNome() 
                    : $this->vinculo->getCargo()->getNome(), 0, 0, 'L');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'B',11);
            $this->Cell(15, 6, "Quadro:", 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
            $this->Cell(30, 6, $this->vinculo->getQuadroEspecial() ? 'Especial' : 'Permanente', 0, 1, 'L');
        } else {
            $this->SetFont(self::FONT_DEFAULT_TYPE,'B',11);
            $this->Cell(35, 6, 'Vínculo:', 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
            $this->Cell(115, 6, $this->vinculo->getVinculoOriginal() ? 'Efetivo / Comissionado' : 'Comissionado', 0, 1, 'L');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'B',11);
            $this->Cell(35, 6, 'Cargo Comissionado:', 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
            $this->Cell(115, 6, $this->vinculo->getCargo()->getNome(), 0, 1, 'L');
            if($this->vinculo->getVinculoOriginal()) {
                $this->SetFont(self::FONT_DEFAULT_TYPE,'B',11);
                $this->Cell(35, 6, 'Cargo de Origem:', 0, 0, 'R');
                $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
                $this->Cell(115, 6, $this->vinculo->getVinculoOriginal()->getCargo()->getNome(), 0, 1, 'L');
            }
        }
        $this->SetFont(self::FONT_DEFAULT_TYPE,'B',11);
        $this->Cell(35, 6, "Carga Horária:", 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(35, 6, $this->vinculo->getCargaHoraria() . 'h', 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'B',11);
        $this->Cell(29, 6, "Portaria:", 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(35, 6, $this->vinculo->getPortaria(), 0, 0, 'L');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'B',11);
        $this->Cell(29, 6, "Edição do Jornal:", 0, 0, 'R');
        $this->SetFont(self::FONT_DEFAULT_TYPE,'',11);
        $this->Cell(35, 6, $this->vinculo->getEdicaoJornalNomeacao(), 0, 1, 'L');
    }
    
    private function informacoesNomeacao() {
        if ($this->vinculo->getTipoVinculo()->getId() != TipoVinculo::ACT) {
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
            $this->Cell(35, 6, 'Data da Nomeação:', 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 11);
            $this->Cell(31, 6, $this->vinculo->getDataInicio() instanceof \DateTime 
                    ? $this->vinculo->getDataInicio()->format('d/m/Y') : '', 0, 0, 'L');
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
            $this->Cell(45, 6, 'Data da Posse:', 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 11);
            $this->Cell(50, 6, $this->vinculo->getDataTermino() instanceof \DateTime 
                    ? $this->vinculo->getDataTermino()->format('d/m/Y') : '', 0, 1, 'L');
        } else {
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
            $this->Cell(35, 6,'Período Contratual:', 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 11);
            $this->Cell(47, 6, $this->vinculo->getDataInicio()->format('d/m/Y') . ' a ' . $this->vinculo->getDataTermino()->format('d/m/Y'), 0, 0, 'L');
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 11);
            $this->Cell(75, 6, 'Data do Edital de Convocação:', 0, 0, 'R');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 11);
            $this->Cell(20, 6, $this->vinculo->getConvocacaoVinculacao()->getDataRealizacao()->format('d/m/Y'), 0, 1, 'L');
        }
    }
    
    private function informacoesEnquadramento() {
        if($this->vinculo->getTipoVinculo()->getId() === TipoVinculo::COMISSIONADO) {
            $this->Cell(55, 6, 'Opção de Lei', 1, 0, 'C');
            $this->Cell(45, 6, 'Lotação Secretaria', 1, 0, 'C');
            $this->Cell(45, 6, 'Código Depto', 1, 0, 'C');
            $this->Cell(45, 6, 'Código Setor', 1, 1, 'C');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
            $this->Cell(55, 6, $this->vinculo->getOpcaoLei(), 1, 0, 'C');
            $this->Cell(45, 6, $this->vinculo->getLotacaoSecretaria(), 1, 0, 'C');
            $this->Cell(45, 6, $this->vinculo->getCodigoDepartamento(), 1, 0, 'C');
            $this->Cell(45, 6, $this->vinculo->getCodigoSetor(), 1, 0, 'C');
        } else {
            $this->Cell(40, 6, 'Vencimento Nível', 1, 0, 'C');
            $this->Cell(30, 6, 'Gratificação', 1, 0, 'C');
            $this->Cell(40, 6, 'Lotação Secretaria', 1, 0, 'C');
            $this->Cell(40, 6, 'Código Depto', 1, 0, 'C');
            $this->Cell(40, 6, 'Código Setor', 1, 1, 'C');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 12);
            $vencimento = $this->vinculo->getCargo()->getNomeOficial() != 'Especialista' ? 'I' : 'II';
            $this->Cell(40, 6, $vencimento, 1, 0, 'C');
            $this->Cell(30, 6, $this->vinculo->getGratificacao(), 1, 0, 'C');
            $this->Cell(40, 6, $this->vinculo->getLotacaoSecretaria(), 1, 0, 'C');
            $this->Cell(40, 6, $this->vinculo->getCodigoDepartamento(), 1, 0, 'C');
            $this->Cell(40, 6, $this->vinculo->getCodigoSetor(), 1, 0, 'C');
        }
    }
    
    public function getVinculo() {
        return $this->vinculo;
    }

    public function setVinculo(Vinculo $vinculo) {
        $this->vinculo = $vinculo;
    }
    
    function getAtendente() {
        return $this->atendente;
    }

    function setAtendente($atendente) {
        $this->atendente = $atendente;
    }
    
}
