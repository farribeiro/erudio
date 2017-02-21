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

namespace CursoBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;
use PessoaBundle\Entity\PessoaFisica;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_solicitacao_vaga")
*/
class SolicitacaoVaga extends AbstractEditableEntity {
    
    /**        
    * @JMS\Groups({"LIST"})
    * @JMS\Type("PessoaBundle\Entity\PessoaFisica")
    * @JMS\MaxDepth(depth = 1)
    * @ORM\ManyToOne(targetEntity = "PessoaBundle\Entity\PessoaFisica")
    * @ORM\JoinColumn(name = "pessoa_fisica_id")
    */
    private $pessoa;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = true) 
    */
    private $descricao;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = false, name = "data_solicitacao") 
    */
    private $dataSolicitacao;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = false, name = "data_expiracao") 
    */
    private $dataExpiracao;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = false) 
    */
    private $status;
    
    function getPessoa() {
        return $this->pessoa;
    }

    function getDescricao() {
        return $this->descricao;
    }
    
    function getDataSolicitacao() {
        return $this->dataSolicitacao;
    }

    function setPessoa(PessoaFisica $pessoa) {
        $this->pessoa = $pessoa;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }
    
    function setDataSolicitacao($dataSolicitacao) {
        $this->dataSolicitacao = $dataSolicitacao;
    }
    
    function getDataExpiracao() {
        return $this->dataExpiracao;
    }

    function setDataExpiracao($dataExpiracao) {
        $this->dataExpiracao = $dataExpiracao;
    }
    
    function getStatus() {
        return $this->status;
    }

    function setStatus($status) {
        $this->status = $status;
    }
}
