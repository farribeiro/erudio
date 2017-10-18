<?php

namespace SME\FilaUnicaBundle\Report;

use SME\PDFBundle\Report\PDFDocument;
use SME\FilaUnicaBundle\Entity\Inscricao;
use SME\FilaUnicaBundle\Entity\TipoInscricao;
use SME\CommonsBundle\Util\DateTimeUtil;

class ComprovanteInscricaoReport extends PDFDocument {
    
    private $inscricao;
    
    function header($title = 'SECRETARIA DE EDUCAÇÃO', $subtitle = 'Comprovante de Inscrição - Fila Única') {
        parent::header($title, $subtitle);
    }
    
    function footer() {
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 9);
        $data = new \DateTime();
        $this->MultiCell($this->ws, 6, 'Documento impresso em ' . $data->format('d/m/Y') , '', 'R');
    }
    
    public function build() {
        $this->AddPage();
        $this->AliasNbPages();
        if($this->inscricao->getTipoInscricao()->getId() === TipoInscricao::TRANSFERENCIA) {
            $this->relTransferencia();
            $this->AddPage();
            $this->relTransferencia();
            $this->relAnexosTransferencia();
        } else {
            $this->relCadastro();
            $this->Ln(4);
            $this->Line($this->x, $this->y, $this->w - 10, $this->y);
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
            $this->Ln(4);
            $this->MultiCell($this->ws, 6, 'COMPROVANTE DE INSCRIÇÃO - FILA ÚNICA - MUNICÍPIO DE ITAJAÍ', '', 'C');
            $this->relCadastro();
            $this->AddPage();
            $this->relAnexos();
        }
    }
    
    private function relCadastro() {
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Ln(3);
        $this->MultiCell($this->ws, 4, '1. Declaro que estou de acordo com todos os dados inseridos no Cadastro de Inscrição da Fila Única e que a partir destes dados será gerado um protocolo de acompanhamento online deste processo.', '', 'J');
        $this->Ln(2);
        $this->MultiCell($this->ws, 4, '2. Declaro que estou ciente de que é de minha responsabilidade a atualização de endereço e telefone sempre que necessário e que as mesmas deverão ser realizadas em qualquer Centro de Educação Infantil da Rede Municipal de Ensino de Itajaí, de segunda-feira a sexta-feira, no horário de atendimento de cada Unidade de Ensino.', '', 'J');
        $this->Ln(2);
        $this->MultiCell($this->ws, 4, '3. Declaro que estou ciente que transcorrido o prazo de 05 (cinco) dias úteis da publicação no Portal da Educação - educacao.itajai.sc.gov.br, no link Sistema Fila Única e/ou comunicação via telefone e não efetuada a matricula neste prazo, a vaga disponível será ofertada a outro/a candidato/a, sem aviso prévio.', '', 'J');
        $this->Ln(2);
        $this->MultiCell($this->ws, 4, '4. E, por fim, declaro estar ciente de que caso os dados fornecidos não possibilitem a comunicação com um responsável legal, após  3 (três) dias úteis de tentativas inexitosas de comunicação, a inscrição será eliminada, sendo chamado o próximo inscrito da fila.', '', 'J');
        $this->Ln(3);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE,'', 9);
        $this->Cell(35,5,'Protocolo', '', 0, 'J');
        $this->Cell(5,5,' ', '', 0, 'J');
        $this->Cell(70,5,'Zoneamento', '', 0, 'J');
        $this->Cell(5,5,' ', '', 0, 'J');
        $this->Cell(40,5,'Ano escolar', '', 0, 'J');
        $this->Cell(5,5,' ', '', 0, 'J');
        $this->Cell(30,5,'Data de inscrição', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(35, 6, $this->inscricao->getProtocolo(), 'LTBR', 0, 'J');
        $this->Cell(5, 6,'','', 0, 'J');
        $this->Cell(70, 6, $this->inscricao->getZoneamento()->getNome() . ' - ' . $this->inscricao->getZoneamento()->getDescricao(), 'LTBR', 0, 'J');
        $this->Cell(5, 6,'','', 0, 'J');
        $this->Cell(40, 6, $this->inscricao->getAnoEscolar()->getNome(), 'LTBR', 0, 'J');
        $this->Cell(5, 6,'','', 0, 'J');
        $this->Cell(30, 6, $this->inscricao->getDataCadastro()->format('d/m/Y'), 'LTBR', 1, 'J');
        $this->Ln(1);

        $this->SetFont(self::FONT_DEFAULT_TYPE,'', 9);
        $this->Cell(100, 5,'Nome da criança', '', 0, 'J');
        $this->Cell(5, 5,' ', '', 0, 'J');
        $this->Cell(50, 5, $this->inscricao->getCrianca()->getCpfCnpj() ? 'CPF' : 'Certidão de nascimento', '', 0, 'J');
        $this->Cell(5, 5,' ', '', 0, 'J');
        $this->Cell(30, 5,'Data de nascimento', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(100, 6, $this->inscricao->getCrianca()->getNome(), 'LTBR', 0, 'J');
        $this->Cell(5, 6,'','', 0, 'J');
        if($this->inscricao->getCrianca()->getCpfCnpj()) {
            $this->Cell(50, 6, $this->inscricao->getCrianca()->getCpfCnpj(), 'LTBR', 0, 'J');
        } else {
            $this->Cell(50, 6, $this->inscricao->getCrianca()->getTermoCertidaoNascimento() . ' - '
                    . $this->inscricao->getCrianca()->getLivroCertidaoNascimento() . ' - '
                    . $this->inscricao->getCrianca()->getFolhaCertidaoNascimento(), 'LTBR', 0, 'J');
        }
        $this->Cell(5, 6,'','', 0, 'J');
        $this->Cell(30, 6, $this->inscricao->getCrianca()->getDataNascimento()->format('d/m/Y'), 'LTBR', 1, 'J');
        $this->Ln(1);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE,'', 9);
        $this->Cell(30, 5,'Renda Per Capita', '', 0, 'J');
        $this->Cell(5, 5,' ', '', 0, 'J');
        $this->Cell(155, 5,'Responsáveis', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $responsaveis = '';
        foreach($this->inscricao->getCrianca()->getRelacoes() as $responsavel) {
            if($responsavel->getResponsavel()) {
                $responsaveis .= $responsavel->getParente()->getNome();
                if($responsavel->getParentesco()) {
                    $responsaveis .= ' (' . $responsavel->getParentesco()->getNome() . '), ';
                } else {
                    $responsaveis .= ', ';
                }
            }
        }
        $this->Cell(30, 6, 'R$ ' . $this->inscricao->getRendaPerCapita(), 'LTBR', 0, 'J');
        $this->Cell(5, 6, '', '', 0, 'J');
        $this->Cell(155, 6, $responsaveis, 'LTBR', 1, 'J');
        $this->Ln(4);
        $this->Cell(95, 8, '___________________________________', '', 0, 'C');
        $this->Cell(95, 8, '___________________________________', '', 1, 'C');
        $this->Cell(95, 5, 'Assinatura do Requerente', '', 0, 'C');
        $this->Cell(95, 5, 'Assinatura do Atendente', '', 1, 'C');
    }

    public function relTransferencia() {
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Ln(8);
        $this->MultiCell($this->ws, 4, '1. Declaro que estou de acordo com todos os dados inseridos no Requerimento de Transferência, e que a partir destes dados será gerado um protocolo;', '', 'J');
        $this->Ln(2);
        $this->MultiCell($this->ws, 4, '2. Declaro que estou ciente de que a partir do momento em que a transferência foi solicitada, a criança entrará em uma fila de espera até que seja disponibilizada uma vaga na Unidade pretendida. A criança enquanto aguarda o encaminhamento da transferência deverá manter frequência assídua na Unidade em que está matriculada,  caso contrário, perderá a vaga atual e o direito a transferência;', '', 'J');
        $this->Ln(2);
        $this->MultiCell($this->ws, 4, '3. Declaro que estou ciente que assim que a transferência for autorizada, a matrícula deverá ser realizada no prazo de 07 (sete) dias úteis, sem direito a desistência;', '', 'J');
        $this->Ln(2);
        $this->MultiCell($this->ws, 4, '4. E, por fim, declaro sob as penas do artigo 299 do Código Penal, que as informações fornecidas são fiéis e verdadeiras, não havendo omissões de dados que possam induzir a equívocos de julgamento, e assumo total responsabilidade pelo conteúdo desta declaração.', '', 'J');
        $this->Ln(3);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE,'', 9);
        $this->Cell(35,5,'Protocolo', '', 0, 'J');
        $this->Cell(5,5,' ', '', 0, 'J');
        $this->Cell(55,5,'Ano escolar', '', 0, 'J');
        $this->Cell(5,5,' ', '', 0, 'J');
        $this->Cell(55,5,'Período', '', 0, 'J');
        $this->Cell(5,5,' ', '', 0, 'J');
        $this->Cell(30,5,'Data de inscrição', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(35, 6, $this->inscricao->getProtocolo(), 'LTBR', 0, 'J');
        $this->Cell(5, 6,'','', 0, 'J');
        $this->Cell(55, 6, $this->inscricao->getAnoEscolar()->getNome(), 'LTBR', 0, 'J');
        $this->Cell(5, 6,'','', 0, 'J');
        $this->Cell(55, 6, $this->inscricao->getPeriodoDia()->getNome(), 'LTBR', 0, 'J');
        $this->Cell(5, 6,'','', 0, 'J');
        $this->Cell(30, 6, $this->inscricao->getDataCadastro()->format('d/m/Y'), 'LTBR', 1, 'J');
        $this->Ln(3);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE,'', 9);
        $this->Cell(190,5,'Unidade de origem', '', 1, 'J');
        $this->Cell(190, 6, $this->inscricao->getUnidadeOrigem()->getNome(), 'LTBR', 1, 'J');
        $this->Ln(3);
        
        $this->Cell(92,5,'Unidade pretendida', '', 0, 'J');
        $this->Cell(5,5,' ', '', 0, 'J');
        $this->Cell(93,5,'Unidade pretendida (alternativa)', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(92, 6, $this->inscricao->getUnidadeDestino()->getNome(), 'LTBR', 0, 'J');
        $this->Cell(5, 6,'','', 0, 'J');
        $this->Cell(93, 6, $this->inscricao->getUnidadeDestinoAlternativa() ? $this->inscricao->getUnidadeDestinoAlternativa()->getNome() : '', 'LTBR', 1, 'J');
        $this->Ln(3);

        $this->SetFont(self::FONT_DEFAULT_TYPE,'', 9);
        $this->Cell(100, 5,'Nome da criança', '', 0, 'J');
        $this->Cell(5, 5,' ', '', 0, 'J');
        $this->Cell(50, 5,'Certidão de nascimento', '', 0, 'J');
        $this->Cell(5, 5,' ', '', 0, 'J');
        $this->Cell(30, 5,'Data de nascimento', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Cell(100, 6, $this->inscricao->getCrianca()->getNome(), 'LTBR', 0, 'J');
        $this->Cell(5, 6,'','', 0, 'J');
        $this->Cell(50, 6, $this->inscricao->getCrianca()->getTermoCertidaoNascimento() . ' - '
                . $this->inscricao->getCrianca()->getLivroCertidaoNascimento() . ' - '
                . $this->inscricao->getCrianca()->getFolhaCertidaoNascimento(), 'LTBR', 0, 'J');
        $this->Cell(5, 6,'','', 0, 'J');
        $this->Cell(30, 6, $this->inscricao->getCrianca()->getDataNascimento()->format('d/m/Y'), 'LTBR', 1, 'J');
        $this->Ln(3);
        
        $this->SetFont(self::FONT_DEFAULT_TYPE,'', 9);
        $this->Cell($this->ws, 5,'Responsáveis', '', 1, 'J');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $responsaveis = '';
        foreach($this->inscricao->getCrianca()->getRelacoes() as $responsavel) {
            if($responsavel->getResponsavel()) {
                $responsaveis .= $responsavel->getParente()->getNome();
                if($responsavel->getParentesco()) {
                    $responsaveis .= ' (' . $responsavel->getParentesco()->getNome() . '), ';
                } else {
                    $responsaveis .= ', ';
                }
            }
        }
        $this->Cell($this->ws, 6, $responsaveis, 'LTBR', 1, 'J');
        $this->Ln(10);
        $this->Cell(95, 8, '___________________________________', '', 0, 'C');
        $this->Cell(95, 8, '___________________________________', '', 1, 'C');
        $this->Cell(95, 5, 'Assinatura do Requerente', '', 0, 'C');
        $this->Cell(95, 5, 'Assinatura do Atendente', '', 1, 'C');
        $this->Ln(15);
    }
    
    public function relAnexosTransferencia() {
        /** Anexo I **/
        $this->AddPage();
        $this->Ln(3);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $this->Cell($this->ws, 4, 'ANEXO I','','1','C');
        $this->Ln(5);
        $this->Cell($this->ws, 4, 'TERMO DE RESPONSABILIDADE PELOS DADOS FORNECIDOS','','1','C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $count = 0;
        $responsaveis = array();
        $relacoes = $this->inscricao->getCrianca()->getRelacoes();
        $pessoasResponsaveis = array();
        foreach ($relacoes as $relacao) { $pessoasResponsaveis[] = $relacao->getParente(); }
        foreach($this->inscricao->getRendaDetalhada() as $componente) {
                $componentesRendimento = array(
                    "rendimentoMensal" => $componente->getRendimentoMensal(),
                    "atividade" => $componente->getAtividade(),
                    "localTrabalho" => $componente->getLocalTrabalho()
                );        
                $pessoaResponsavel = NULL;
                foreach ($pessoasResponsaveis as $pessoa) {
                    if ($pessoa->getId() == $componente->getResponsavel()) { $pessoaResponsavel = $pessoa; }
                }
                if (!empty($pessoaResponsavel)) {
                    $responsavelCrianca = array($pessoaResponsavel,$componentesRendimento);
                    $responsaveis[] = $responsavelCrianca;
                }
        }
        foreach($this->inscricao->getCrianca()->getRelacoes() as $responsavel) {            
            if($responsavel->getResponsavel()) {
                $responsaveisNaoListados[] = $responsavel->getParente();
            }
        }
        
        $responsaveisEndereco = $this->inscricao->getCrianca()->getEndereco()->getLogradouro().' nº '.$this->inscricao->getCrianca()->getEndereco()->getNumero().', bairro '.$this->inscricao->getCrianca()->getEndereco()->getBairro();
        $this->Ln(5);
        if (count($responsaveis) > 0) {
            $responsavel = $responsaveis[0];
            $this->MultiCell($this->ws, 4, 'Eu '.$responsavel[0]->getNome().' portador do RG nº '.$responsavel[0]->getNumeroRg().', inscrito no CPF sob o nº '.$responsavel[0]->getCpfCnpj().' responsável pelo(a) infante '.$this->inscricao->getCrianca()->getNome().' informado e consonante às normativas regentes do Programa Fila Única, declaro que todas as informações prestadas para a devida inscrição são verdadeiras, corretas e completas, razão pela qual assino o presente termo de responsabilidade, ciente que a falsidade dos referidos dados fornecidos está sujeita às penalidades legais previstas no Artigo 299 do Código Penal, podendo ocasionar a desclassificação do Programa Fila Única.', '', 'J');
        } else {
            $responsavel = $responsaveisNaoListados[0];
            $this->MultiCell($this->ws, 4, 'Eu '.$responsavel->getNome().' portador do RG nº '.$responsavel->getNumeroRg().', inscrito no CPF sob o nº '.$responsavel->getCpfCnpj().' responsável pelo(a) infante '.$this->inscricao->getCrianca()->getNome().' informado e consonante às normativas regentes do Programa Fila Única, declaro que todas as informações prestadas para a devida inscrição são verdadeiras, corretas e completas, razão pela qual assino o presente termo de responsabilidade, ciente que a falsidade dos referidos dados fornecidos está sujeita às penalidades legais previstas no Artigo 299 do Código Penal, podendo ocasionar a desclassificação do Programa Fila Única.', '', 'J');
        }
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'I', 10);
        $x = $this->GetX();
        $this->SetX($x+70);
        $this->MultiCell(120, 4, '"Art. 299. Omitir, em documento público ou particular, declaração que dele devia constar, ou nele inserir ou fazer inserir declaração falsa ou diversa da que devia ser escrita, com o fim de prejudicar direito, criar obrigação ou alterar a verdade sobre fato juridicamente relevante:
                                   Pena - reclusão, de um a cinco anos, e multa, se o documento é público, e reclusão de um a três anos, e multa, de quinhentos mil réis a cinco contos de réis, se o documento é particular."', '', 'R');
        $this->SetX($x);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Ln(10);
        $this->MultiCell($this->ws, 4, 'Itajaí (SC), '. DateTimeUtil::dataPorExtenso(new \DateTime).'.', '', 'C');
        $this->Ln(20);
        $this->SetX($x+50);
        $this->MultiCell(100, 4, 'Assinatura', 'T', 'C');
        
        /** Anexo II **/
        $this->Ln(10);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $this->Cell($this->ws, 4, 'ANEXO II','','1','C');
        $this->Ln(5);
        $this->Cell($this->ws, 4, 'DECLARAÇÃO DE RESIDÊNCIA','','1','C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Ln(5);
        if (count($responsaveis) > 0) {
            $responsavel = $responsaveis[0];
            $this->MultiCell($this->ws, 4, 'Eu '.$responsavel[0]->getNome().' portador do RG nº '.$responsavel[0]->getNumeroRg().', inscrito no CPF sob o nº '.$responsavel[0]->getCpfCnpj().' residente e domiciliado em '.$responsaveisEndereco.', no município de Itajaí/SC, conforme cópia de comprovante anexo.', '', 'J');
        } else {
            $responsavel = $responsaveisNaoListados[0];
            $this->MultiCell($this->ws, 4, 'Eu '.$responsavel->getNome().' portador do RG nº '.$responsavel->getNumeroRg().', inscrito no CPF sob o nº '.$responsavel->getCpfCnpj().' residente e domiciliado em '.$responsaveisEndereco.', no município de Itajaí/SC, conforme cópia de comprovante anexo.', '', 'J');
        }
        $this->Ln(2);
        $this->MultiCell($this->ws, 4, 'Declaro ainda estar ciente de que a falsidade da presente declaração está sujeita às penalidades legais previstas no Artigo 299 do Código Penal.', '', 'J');
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'I', 10);
        $this->SetX($x+70);
        $this->MultiCell(120, 4, '"Art. 299. Omitir, em documento público ou particular, declaração que dele devia constar, ou nele inserir ou fazer inserir declaração falsa ou diversa da que devia ser escrita, com o fim de prejudicar direito, criar obrigação ou alterar a verdade sobre fato juridicamente relevante:
                                   Pena - reclusão, de um a cinco anos, e multa, se o documento é público, e reclusão de um a três anos, e multa, de quinhentos mil réis a cinco contos de réis, se o documento é particular."', '', 'R');
        $this->SetX($x);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Ln(10);
        $this->MultiCell($this->ws, 4, 'Itajaí (SC), '. DateTimeUtil::dataPorExtenso(new \DateTime).'.', '', 'C');
        $this->Ln(20);
        $this->SetX($x+50);
        $this->MultiCell(100, 4, 'Assinatura', 'T', 'C');
    }


    public function relAnexos() {
        /** Anexo I **/
        $this->Ln(3);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $this->Cell($this->ws, 4, 'ANEXO I','','1','C');
        $this->Ln(5);
        $this->Cell($this->ws, 4, 'TERMO DE RESPONSABILIDADE PELOS DADOS FORNECIDOS','','1','C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        //$count = 0;
        $responsaveis = array();
        $relacoes = $this->inscricao->getCrianca()->getRelacoes();
        $pessoasResponsaveis = array();
        foreach ($relacoes as $relacao) { $pessoasResponsaveis[] = $relacao->getParente(); }
        foreach($this->inscricao->getRendaDetalhada() as $componente) {
                $componentesRendimento = array(
                    "rendimentoMensal" => $componente->getRendimentoMensal(),
                    "atividade" => $componente->getAtividade(),
                    "localTrabalho" => $componente->getLocalTrabalho()
                );
                if (empty($componentesRendimento["rendimentoMensal"])) { $componentesRendimento["rendimentoMensal"] = "_____________________________"; }
                if (empty($componentesRendimento["atividade"])) { $componentesRendimento["atividade"] = "_____________________________"; }
                if (empty($componentesRendimento["localTrabalho"])) { $componentesRendimento["localTrabalho"] = "_____________________________"; }
                $pessoaResponsavel = NULL;
                foreach ($pessoasResponsaveis as $pessoa) {
                    if ($pessoa->getId() == $componente->getResponsavel()) { $pessoaResponsavel = $pessoa; }
                }
                if (!empty($pessoaResponsavel)) {
                    $responsavelCrianca = array($pessoaResponsavel,$componentesRendimento);
                    $responsaveis[] = $responsavelCrianca;
                }
        }
        
        $responsaveisEndereco = $this->inscricao->getCrianca()->getEndereco()->getLogradouro().' nº '.$this->inscricao->getCrianca()->getEndereco()->getNumero().', bairro '.$this->inscricao->getCrianca()->getEndereco()->getBairro();
        $this->Ln(5);
        if (count($responsaveis) > 0) {
            $responsavel = $responsaveis[0];
            $this->MultiCell($this->ws, 4, 'Eu '.$responsavel[0]->getNome().' portador do RG nº '.$responsavel[0]->getNumeroRg().', inscrito no CPF sob o nº '.$responsavel[0]->getCpfCnpj().' responsável pelo(a) infante '.$this->inscricao->getCrianca()->getNome().' informado e consonante às normativas regentes do Programa Fila Única, declaro que todas as informações prestadas para a devida inscrição são verdadeiras, corretas e completas, razão pela qual assino o presente termo de responsabilidade, ciente que a falsidade dos referidos dados fornecidos está sujeita às penalidades legais previstas no Artigo 299 do Código Penal, podendo ocasionar a desclassificação do Programa Fila Única.', '', 'J');
        }
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'I', 10);
        $x = $this->GetX();
        $this->SetX($x+70);
        $this->MultiCell(120, 4, '"Art. 299. Omitir, em documento público ou particular, declaração que dele devia constar, ou nele inserir ou fazer inserir declaração falsa ou diversa da que devia ser escrita, com o fim de prejudicar direito, criar obrigação ou alterar a verdade sobre fato juridicamente relevante:
                                   Pena - reclusão, de um a cinco anos, e multa, se o documento é público, e reclusão de um a três anos, e multa, de quinhentos mil réis a cinco contos de réis, se o documento é particular."', '', 'R');
        $this->SetX($x);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Ln(10);
        $this->MultiCell($this->ws, 4, 'Itajaí (SC), '. DateTimeUtil::dataPorExtenso(new \DateTime).'.', '', 'C');
        $this->Ln(20);
        $this->SetX($x+50);
        $this->MultiCell(100, 4, 'Assinatura', 'T', 'C');
        
        /** Anexo II **/
        $this->Ln(10);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $this->Cell($this->ws, 4, 'ANEXO II','','1','C');
        $this->Ln(5);
        $this->Cell($this->ws, 4, 'DECLARAÇÃO DE RESIDÊNCIA','','1','C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Ln(5);
        if (count($responsaveis) > 0) {
            $responsavel = $responsaveis[0];
            $this->MultiCell($this->ws, 4, 'Eu '.$responsavel[0]->getNome().' portador do RG nº '.$responsavel[0]->getNumeroRg().', inscrito no CPF sob o nº '.$responsavel[0]->getCpfCnpj().' residente e domiciliado em '.$responsaveisEndereco.', no município de Itajaí/SC, conforme cópia de comprovante anexo.', '', 'J');
        }
        $this->Ln(2);
        $this->MultiCell($this->ws, 4, 'Declaro ainda estar ciente de que a falsidade da presente declaração está sujeita às penalidades legais previstas no Artigo 299 do Código Penal.', '', 'J');
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'I', 10);
        $this->SetX($x+70);
        $this->MultiCell(120, 4, '"Art. 299. Omitir, em documento público ou particular, declaração que dele devia constar, ou nele inserir ou fazer inserir declaração falsa ou diversa da que devia ser escrita, com o fim de prejudicar direito, criar obrigação ou alterar a verdade sobre fato juridicamente relevante:
                                   Pena - reclusão, de um a cinco anos, e multa, se o documento é público, e reclusão de um a três anos, e multa, de quinhentos mil réis a cinco contos de réis, se o documento é particular."', '', 'R');
        $this->SetX($x);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Ln(10);
        $this->MultiCell($this->ws, 4, 'Itajaí (SC), '. DateTimeUtil::dataPorExtenso(new \DateTime).'.', '', 'C');
        $this->Ln(20);
        $this->SetX($x+50);
        $this->MultiCell(100, 4, 'Assinatura', 'T', 'C');
        
        /** Anexo III **/
        $this->AddPage();
        $this->Ln(10);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        $this->Cell($this->ws, 4, 'ANEXO III','','1','C');
        $this->Ln(5);
        $this->Cell($this->ws, 4, 'DECLARAÇÃO DE TRABALHO','','1','C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Ln(5);
        if (count($responsaveis) > 0) {
            $responsavel = $responsaveis[0];
            $this->MultiCell($this->ws, 4, 'Eu '.$responsavel[0]->getNome().' portador do RG nº '.$responsavel[0]->getNumeroRg().', inscrito no CPF sob o nº '.$responsavel[0]->getCpfCnpj().' residente e domiciliado em '.$responsaveisEndereco.', no município de Itajaí/SC, declaro para os devidos fins que sou TRABALHADOR, desenvolvendo a atividade de '.$responsavel[1]["atividade"].' no local '.$responsavel[1]["localTrabalho"].', com a renda mensal de R$'.$responsavel[1]["rendimentoMensal"].'.', '', 'J');
        }
        $this->Ln(2);
        $this->MultiCell($this->ws, 4, 'Ainda, assumo inteira responsabilidade pelas informações prestadas e declaro estar ciente de que a falsidade nas informações acima implicará nas penalidades cabíveis,  previstas no Artigo 299 do Código Penal.', '', 'J');
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'I', 10);
        $this->SetX($x+70);
        $this->MultiCell(120, 4, '"Art. 299. Omitir, em documento público ou particular, declaração que dele devia constar, ou nele inserir ou fazer inserir declaração falsa ou diversa da que devia ser escrita, com o fim de prejudicar direito, criar obrigação ou alterar a verdade sobre fato juridicamente relevante:
                                   Pena - reclusão, de um a cinco anos, e multa, se o documento é público, e reclusão de um a três anos, e multa, de quinhentos mil réis a cinco contos de réis, se o documento é particular."', '', 'R');
        $this->SetX($x);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Ln(10);
        $this->MultiCell($this->ws, 4, 'Itajaí (SC), '. DateTimeUtil::dataPorExtenso(new \DateTime).'.', '', 'C');
        $this->Ln(20);
        $this->SetX($x+50);
        $this->MultiCell(100, 4, 'Assinatura', 'T', 'C');
        
        /** Anexo IV **/
        if (count($responsaveis) > 0) { 
            if (count($responsaveis) == 2) { $responsavel = $responsaveis[1]; }
        }
        if (count($responsaveis) == 2) {
            $this->Ln(10);
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
            $this->Cell($this->ws, 4, 'ANEXO IV','','1','C');
            $this->Ln(5);
            $this->Cell($this->ws, 4, 'DECLARAÇÃO DE TRABALHO','','1','C');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
            $this->Ln(5);
            if (count($responsaveis) > 0) {
                $this->MultiCell($this->ws, 4, 'Eu '.$responsavel[0]->getNome().' portador do RG nº '.$responsavel[0]->getNumeroRg().', inscrito no CPF sob o nº '.$responsavel[0]->getCpfCnpj().' residente e domiciliado em '.$responsaveisEndereco.', no município de Itajaí/SC, declaro para os devidos fins que sou TRABALHADOR, desenvolvendo a atividade de '.$responsavel[1]["atividade"].' no local '.$responsavel[1]["localTrabalho"].', com a renda mensal de R$'.$responsavel[1]["rendimentoMensal"].'.', '', 'J');
            }
            $this->Ln(2);
            $this->MultiCell($this->ws, 4, 'Ainda, assumo inteira responsabilidade pelas informações prestadas e declaro estar ciente de que a falsidade nas informações acima implicará nas penalidades cabíveis,  previstas no Artigo 299 do Código Penal.', '', 'J');
            $this->Ln(5);
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'I', 10);
            $this->SetX($x+70);
            $this->MultiCell(120, 4, '"Art. 299. Omitir, em documento público ou particular, declaração que dele devia constar, ou nele inserir ou fazer inserir declaração falsa ou diversa da que devia ser escrita, com o fim de prejudicar direito, criar obrigação ou alterar a verdade sobre fato juridicamente relevante:
                                       Pena - reclusão, de um a cinco anos, e multa, se o documento é público, e reclusão de um a três anos, e multa, de quinhentos mil réis a cinco contos de réis, se o documento é particular."', '', 'R');
            $this->SetX($x);
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
            $this->Ln(10);
            $this->MultiCell($this->ws, 4, 'Itajaí (SC), '. DateTimeUtil::dataPorExtenso(new \DateTime).'.', '', 'C');
            $this->Ln(20);
            $this->SetX($x+50);
            $this->MultiCell(100, 4, 'Assinatura', 'T', 'C');
        }
        
        /** Anexo V **/
        if (count($responsaveis) == 2) { $this->AddPage(); }
        $this->Ln(6);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
        if (count($responsaveis) == 1) {
            $this->Cell($this->ws, 4, 'ANEXO IV','','1','C');
        } else {
            $this->Cell($this->ws, 4, 'ANEXO V','','1','C');
        }
        $this->Ln(5);
        $this->Cell($this->ws, 4, 'DECLARAÇÃO DE AUSÊNCIA DE CARTEIRA PROFISSIONAL','','1','C');
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Ln(5);
        if (count($responsaveis) > 0) {
            $responsavel = $responsaveis[0];
            $this->MultiCell($this->ws, 4, 'Eu '.$responsavel[0]->getNome().' portador do RG nº '.$responsavel[0]->getNumeroRg().', inscrito no CPF sob o nº '.$responsavel[0]->getCpfCnpj().' residente e domiciliado em '.$responsaveisEndereco.', no município de Itajaí/SC, declaro sob as penas da lei, que não possuo carteira profissional pelo motivo de', '', 'J');
        }
        $this->Line($this->x, $this->y+7, $this->w - 10, $this->y+7);
        $this->Ln(8);
        $this->MultiCell($this->ws, 4, 'Por ser expressão da verdade, firmo a presente declaração, ciente de que a falsidade das informações acima implicará nos penalidades previstas no Artigo 299 do Código Penal.', '', 'J');
        $this->Ln(5);
        $this->SetFont(self::FONT_DEFAULT_TYPE, 'I', 10);
        $this->SetX($x+70);
        $this->MultiCell(120, 4, '"Art. 299. Omitir, em documento público ou particular, declaração que dele devia constar, ou nele inserir ou fazer inserir declaração falsa ou diversa da que devia ser escrita, com o fim de prejudicar direito, criar obrigação ou alterar a verdade sobre fato juridicamente relevante:
                                   Pena - reclusão, de um a cinco anos, e multa, se o documento é público, e reclusão de um a três anos, e multa, de quinhentos mil réis a cinco contos de réis, se o documento é particular."', '', 'R');
        $this->SetX($x);
        $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
        $this->Ln(10);
        $this->MultiCell($this->ws, 4, 'Itajaí (SC), '. DateTimeUtil::dataPorExtenso(new \DateTime).'.', '', 'C');
        $this->Ln(20);
        $this->SetX($x+50);
        $this->MultiCell(100, 4, 'Assinatura', 'T', 'C');
        
        /** Anexo VI **/
        if (count($responsaveis) > 0) { 
            if (count($responsaveis) == 2) { $responsavel = $responsaveis[1]; }
        }
        if (count($responsaveis) == 2) {
            $this->Ln(6);
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'B', 12);
            $this->Cell($this->ws, 4, 'ANEXO VI','','1','C');
            $this->Ln(5);
            $this->Cell($this->ws, 4, 'DECLARAÇÃO DE AUSÊNCIA DE CARTEIRA PROFISSIONAL','','1','C');
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
            $this->Ln(5);
            if (count($responsaveis) > 0) {
                $this->MultiCell($this->ws, 4, 'Eu '.$responsavel[0]->getNome().' portador do RG nº '.$responsavel[0]->getNumeroRg().', inscrito no CPF sob o nº '.$responsavel[0]->getCpfCnpj().' residente e domiciliado em '.$responsaveisEndereco.', no município de Itajaí/SC, declaro sob as penas da lei, que não possuo carteira profissional pelo motivo de', '', 'J');
            }
            $this->Line($this->x, $this->y+7, $this->w - 10, $this->y+7);
            $this->Ln(8);
            $this->MultiCell($this->ws, 4, 'Por ser expressão da verdade, firmo a presente declaração, ciente de que a falsidade das informações acima implicará nos penalidades previstas no Artigo 299 do Código Penal.', '', 'J');
            $this->Ln(5);
            $this->SetFont(self::FONT_DEFAULT_TYPE, 'I', 10);
            $this->SetX($x+70);
            $this->MultiCell(120, 4, '"Art. 299. Omitir, em documento público ou particular, declaração que dele devia constar, ou nele inserir ou fazer inserir declaração falsa ou diversa da que devia ser escrita, com o fim de prejudicar direito, criar obrigação ou alterar a verdade sobre fato juridicamente relevante:
                                       Pena - reclusão, de um a cinco anos, e multa, se o documento é público, e reclusão de um a três anos, e multa, de quinhentos mil réis a cinco contos de réis, se o documento é particular."', '', 'R');
            $this->SetX($x);
            $this->SetFont(self::FONT_DEFAULT_TYPE, '', 10);
            $this->Ln(10);
            $this->MultiCell($this->ws, 4, 'Itajaí (SC), '. DateTimeUtil::dataPorExtenso(new \DateTime).'.', '', 'C');
            $this->Ln(20);
            $this->SetX($x+50);
            $this->MultiCell(100, 4, 'Assinatura', 'T', 'C');
        }
    }


    public function getInscricao() {
        return $this->inscricao;
    }

    public function setInscricao(Inscricao $inscricao) {
        $this->inscricao = $inscricao;
    }
    
}
