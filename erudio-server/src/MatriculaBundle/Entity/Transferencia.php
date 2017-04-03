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

namespace MatriculaBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation as JMS;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_movimentacao_transferencia")
*/
class Transferencia extends Movimentacao {
    
    const STATUS_PENDENTE = 'PENDENTE',
          STATUS_ACEITO = 'ACEITO',
          STATUS_RECUSADO = 'RECUSADO';
    
    /** 
    * @JMS\Groups({"LIST"})  
    * @ORM\Column(type = "string", nullable = false) 
    */
    private $status;
    
    /** 
    * @JMS\Groups({"LIST"})  
    * @JMS\Type("DateTime<'Y-m-d'>")
    * @ORM\Column(name = "data_agendamento", type = "date") 
    */
    private $dataAgendamento;
    
    /** 
    * @JMS\Type("DateTime<'Y-m-d\TH:i:s'>")
    * @ORM\Column(name = "data_encerramento", type = "datetime") 
    */
    private $dataEncerramento;
    
    /** @ORM\Column() */
    private $resposta;
    
    /** 
    * @JMS\Groups({"LIST"}) 
    * @JMS\Type("PessoaBundle\Entity\UnidadeEnsino") 
    * @ORM\ManyToOne(targetEntity = "PessoaBundle\Entity\UnidadeEnsino")
    * @ORM\JoinColumn(name = "unidade_ensino_origem_id") 
    */
    private $unidadeEnsinoOrigem;
    
    /** 
    * @JMS\Groups({"LIST"}) 
    * @JMS\Type("PessoaBundle\Entity\UnidadeEnsino") 
    * @ORM\ManyToOne(targetEntity = "PessoaBundle\Entity\UnidadeEnsino")
    * @ORM\JoinColumn(name = "unidade_ensino_destino_id") 
    */
    private $unidadeEnsinoDestino;
    
    public function init() {
        parent::init();
        $this->unidadeEnsinoOrigem = $this->getMatricula()->getUnidadeEnsino();
        if ($this->status != self::STATUS_ACEITO) {
            $this->status = self::STATUS_PENDENTE;
        }
    }
    
    function getStatus() {
        return $this->status;
    }

    function getDataAgendamento() {
        return $this->dataAgendamento;
    }

    function getDataEncerramento() {
        return $this->dataEncerramento;
    }

    function getResposta() {
        return $this->resposta;
    }
    
    function getUnidadeEnsinoOrigem() {
        return $this->unidadeEnsinoOrigem;
    }

    
    function getUnidadeEnsinoDestino() {
        return $this->unidadeEnsinoDestino;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setDataAgendamento($dataAgendamento) {
        $this->dataAgendamento = $dataAgendamento;
    }

    function setDataEncerramento($dataEncerramento) {
        $this->dataEncerramento = $dataEncerramento;
    }

    function setResposta($resposta) {
        $this->resposta = $resposta;
    }
    
}
