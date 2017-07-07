<?php

namespace SME\DGPContratacaoBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\CommonsBundle\Util\DateTimeUtil;
use SME\DGPContratacaoBundle\Entity\TipoProcesso;
use SME\DGPBundle\Util\AssinaturasUtil;

class ChecklistAdmissaoReport extends PDFDocument {
    
    private $vinculo;
    private $atendente;
    
    public function build() {
        $this->AddPage();
        $this->AliasNbPages();
        $this->rel();
    }
    
    private function rel() {
        $this->Ln(4);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 10);
        $this->SetFillColor(200, 200, 200);
        $hoje = new \DateTime();
        $this->MultiCell($this->ws, 4, 'CHECKLIST PARA VERIFICAÇÃO DA REGULARIDADE DO PROCESSO DE ADMISSÃO DE PESSOAL', '', 'C');
        $this->MultiCell($this->ws, 4, 'Nº ' . ($this->vinculo->getNumeroControle() ? $this->vinculo->getNumeroControle() : '_____') . '/' . $hoje->format('Y'), '', 'C');
        $this->Ln(2);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 9);
        $this->MultiCell($this->ws, 4, 'Efetuou-se a análise do processo de admissão de ' . $this->vinculo->getServidor()->getNome() . ' mediante verificação dos procedimentos e da documentação relacionada a seguir:', '', 'J');
        $this->Ln(2);
        if($this->vinculo->getInscricaoVinculacao()) {
            $processo = $this->vinculo->getInscricaoVinculacao()->getProcesso();
            switch($processo->getTipoProcesso()->getId()) {
                case TipoProcesso::CONCURSO_PUBLICO:
                    $this->checklistConcurso($processo); break;
                case TipoProcesso::PROCESSO_SELETIVO:
                    $this->checklistProcessoSeletivo($processo); break;
                case TipoProcesso::CHAMADA_PUBLICA:
                    $this->checklistChamadaPublica($processo);
            }
        } else {
            $this->checklistComissionado();
        }
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 8);
        $this->Cell(150, 4, 'PARA TODOS OS CARGOS', 1, 0, 'L');
        $this->Cell(20, 4, 'SIM', 1, 0, 'C');
        $this->Cell(20, 4, 'NÃO', 1, 1, 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 8);
        $this->Cell(150, 4, 'Nome', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Sexo', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'RG e CPF', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Estado Civil (certidão de casamento quando casado)', 1, 0, 'L');
        $this->Cell(20, 4, '', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Certidão de nascimento dos filhos para fins de salário família (dos que possuem)', 1, 0, 'L');
        $this->Cell(20, 4, '', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Cargo ou função', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Vencimento', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Lotação', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'PIS ou PASEP', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Nacionalidade Brasileira', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Gozo dos Direitos Políticos', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Certificado de Reservista ou equivalente (para candidatos do sexo masculino) quitação militar;', 1, 0, 'L');
        $this->Cell(20, 4, '', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Título de Eleitor e comprovante da última votação (frente e verso) quitação eleitoral;', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Idade mínima de 18 anos', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Declaração de não acumulação de cargos, função, emprego, ou percepção de proventos, fornecida pelo candidato', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 3, 'No caso de acumulação legal de cargos, função, emprego ou percepção de proventos, informar o cargo, o órgão ao', 'LR', 0, 'L');
        $this->Cell(20, 3, '', 'LR', 0, 'C');
        $this->Cell(20, 3, '', 'LR', 1, 'C');
        $this->Cell(150, 4, 'qual pertence e a carga horária', 'LRB', 0, 'L');
        $this->Cell(20, 4, '', 'LRB', 0, 'C');
        $this->Cell(20, 4, '', 'LRB', 1, 'C');
        $this->Cell(150, 3, 'Declaração de ter sofrido ou não, no exercício de função pública, penalidades disciplinares, conforme legislação', 'LR', 0, 'L');
        $this->Cell(20, 3, 'X', 'LR', 0, 'C');
        $this->Cell(20, 3, '', 'LR', 1, 'C');
        $this->Cell(150, 4, 'aplicável', 'LRB', 0, 'L');
        $this->Cell(20, 4, '', 'LRB', 0, 'C');
        $this->Cell(20, 4, '', 'LRB', 1, 'C');
        $this->Cell(150, 4, 'Comprovante de residência', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Declaração de Bens', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Certidão Negativa de antecedentes criminais', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Duas fotos 3x4', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Comprovante de Conta corrente', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Cópia Carteira de trabalho', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');

        $this->Ln(2);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 9);
        $this->MultiCell($this->ws, 4, 'Estando a documentação em conformidade com a checklist acima preenchida, encaminha-se o presente processo à Unidade de Controle Interno para manifestação sobre a regularidade nos termos da Instrução Normativa TC 11/2011.', '', 'J');
        $this->Ln(3);
        $this->MultiCell($this->ws, 4, 'Itajaí (SC), ' . DateTimeUtil::dataPorExtenso(new \DateTime()), '', 'J');
        $this->Ln(4);
        $this->Cell(190, 4, '___________________________________________________________________________', '', 1, 'C');
        $this->Cell(190, 3, 'Nome, cargo e assinatura do servidor que preencheu a ckecklist', '', 1, 'C');
        $this->Ln(2);
        $this->MultiCell($this->ws, 4, 'Ciente:', '', 'J');
        $this->Ln(5);
        $this->Cell(190, 4, '___________________________________', '', 1, 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);
        $this->Cell(190, 4, AssinaturasUtil::DIRETOR_DGP, '', 1, 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 9);
        $this->Cell(190, 3, 'Diretor(a) de Gestão de Pessoas', '', 1, 'C');
    }
    
    private function checklistConcurso($processo) {
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 8);
        $this->Cell(150, 4, 'CONCURSO PÚBLICO', 1, 0, 'L');
        $this->Cell(20, 4, 'SIM', 1, 0, 'C');
        $this->Cell(20, 4, 'NÃO', 1, 1, 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 8);
        
        $this->Cell(150, 4, 'Concurso Público - Edital nº ' . $processo->getEdital(), 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Data de Homologação do concurso: ' . $processo->getDataHomologacao()->format('d/m/Y')
                . '          Decreto: ' . $processo->getDecretoHomologacao(), 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Data de Publicação do Decreto: ' . $processo->getDataPublicacaoHomologacao()->format('d/m/Y')
                . '                na Edição JM nº ' . $processo->getEdicaoJornalHomologacao(), 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Data de Validade do Concurso: ' . $processo->getDataEncerramento()->format('d/m/Y'), 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        if($processo->getDecretoProrrogacao()) {
            $this->Cell(150, 4, 'Data de Prorrogação do concurso: ' . $processo->getDataProrrogacao()->format('d/m/Y')
                . '          Decreto: ' . $processo->getDecretoProrrogacao(), 1, 0, 'L');
            $this->Cell(20, 4, 'X', 1, 0, 'C');
            $this->Cell(20, 4, '', 1, 1, 'C');
            $this->Cell(150, 4, 'Data de Publicação do Decreto: ' . $processo->getDataPublicacaoProrrogacao()->format('d/m/Y')
                    . '                na Edição JM nº ' . $processo->getEdicaoJornalProrrogacao(), 1, 0, 'L');
            $this->Cell(20, 4, 'X', 1, 0, 'C');
            $this->Cell(20, 4, '', 1, 1, 'C');
        }
        $this->Cell(150, 4, 'Autorização orçamentária para a contratação emitida pela SEPOG', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Comprovação da existência do cargo e vaga (nas informações do servidor para efetivação)', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Portaria (cargo público) (nas informações do servidor para efetivação)', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Foi atendida a legislação pertinente', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Laudo de inspeção de saúde, procedida por órgão médico oficial', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Foi feito o termo de posse para cargo público', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Foram publicados os atos', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Apresentou a habilitação exigida no edital', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Prova de cumprimento dos demais requisitos no edital de concurso público', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
    }
    
    private function checklistProcessoSeletivo($processo) {
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 8);
        $this->Cell(150, 4, 'TEMPORÁRIO', 1, 0, 'L');
        $this->Cell(20, 4, 'SIM', 1, 0, 'C');
        $this->Cell(20, 4, 'NÃO', 1, 1, 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 8);
        $this->Cell(150, 4, 'Processo Seletivo Simplificado (ACT) - Edital nº ' . $processo->getEdital(), 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Data de Homologação do Processo Seletivo: ' . $processo->getDataHomologacao()->format('d/m/Y')
                . '          Edital de Homologação: ' . $processo->getDecretoHomologacao(), 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Data de Publicação da Homologação: ' . $processo->getDataPublicacaoHomologacao()->format('d/m/Y')
                . '                na Edição JM nº ' . $processo->getEdicaoJornalHomologacao(), 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Data de Validade do Processo Seletivo: ' . $processo->getDataEncerramento()->format('d/m/Y'), 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Autorização orçamentária para a contratação emitida pela SEPOG', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Carteira de Trabalho entregue na Secretaria de Educação', 1, 0, 'L');
        $this->Cell(20, 4, '', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Laudo de inspeção de saúde', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Foram publicados os atos(relativo ao Processo Seletivo)', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Apresentou a habilitação exigida no edital', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Atendimento à Lei Municipal nº 5.194, de 04 de novembro de 2008', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Prova de cumprimento dos demais requisitos exigidos no edital de processo seletivo', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
    }
    
    private function checklistChamadaPublica($processo) {
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 8);
        $this->Cell(150, 4, 'TEMPORÁRIO', 1, 0, 'L');
        $this->Cell(20, 4, 'SIM', 1, 0, 'C');
        $this->Cell(20, 4, 'NÃO', 1, 1, 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 8);
        $this->Cell(150, 4, 'Processo por Nível de Escolaridade - Edital nº ' . $processo->getEdital(), 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');

        $this->Cell(150, 4, 'Data de Publicação do Edital: ' . $processo->getDataPublicacaoHomologacao()->format('d/m/Y')
                . '                na Edição JM nº ' . $processo->getEdicaoJornalHomologacao(), 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Data de Validade do Processo por Nível de Escolaridade: ' . $processo->getDataEncerramento()->format('d/m/Y'), 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Autorização orçamentária para a contratação emitida pela SEPOG', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Carteira de Trabalho entregue na Secretaria de Educação', 1, 0, 'L');
        $this->Cell(20, 4, '', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Laudo de inspeção de saúde', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Foram publicados os atos(relativo ao Processo Seletivo)', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Apresentou a habilitação exigida no edital', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Atendimento à Lei Municipal nº 5.194, de 04 de novembro de 2008', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Prova de cumprimento dos demais requisitos exigidos no edital de processo por nível de escolaridade', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
    }
    
    private function checklistComissionado() {
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 8);
        $this->Cell(150, 4, 'COMISSIONADO', 1, 0, 'L');
        $this->Cell(20, 4, 'SIM', 1, 0, 'C');
        $this->Cell(20, 4, 'NÃO', 1, 1, 'C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 8);
        $this->Cell(150, 4, 'Portaria de nomeação nº ' . $this->vinculo->getPortaria(), 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'A nomeação foi publicada em ' . $this->vinculo->getDataNomeacao()->format('d/m/Y') . ', jornal Edição nº ' . $this->vinculo->getEdicaoJornalNomeacao(), 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Laudo de inspeção de saúde', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Autorização orçamentária para a contratação emitida pela SEPOG', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
        $this->Cell(150, 4, 'Foi feito o termo de posse para o cargo', 1, 0, 'L');
        $this->Cell(20, 4, 'X', 1, 0, 'C');
        $this->Cell(20, 4, '', 1, 1, 'C');
    }
    
    function getVinculo() {
        return $this->vinculo;
    }

    function getAtendente() {
        return $this->atendente;
    }

    function setVinculo($vinculo) {
        $this->vinculo = $vinculo;
    }

    function setAtendente($atendente) {
        $this->atendente = $atendente;
    }

}

