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

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_movimentacao_reclassificacao")
*/
class Reclassificacao extends Movimentacao {
    
    /** 
    * @JMS\Groups({"LIST"})   
    * @ORM\ManyToOne(targetEntity = "Enturmacao") 
    * @ORM\JoinColumn(name = "enturmacao_origem_id") 
    */
    private $enturmacaoOrigem;
    
    /** 
    * @JMS\Groups({"LIST"})   
    * @ORM\ManyToOne(targetEntity = "Enturmacao", cascade = {"persist"}) 
    * @ORM\JoinColumn(name = "enturmacao_destino_id") 
    */
    private $enturmacaoDestino;
    
    /**
    * @JMS\MaxDepth(depth = 2)
    * @ORM\OneToMany(targetEntity = "NotaReclassificacao", mappedBy = "reclassificacao", fetch="EXTRA_LAZY", cascade = {"persist"}) 
    */
    private $notas;
    
    /** @JMS\Type("CursoBundle\Entity\Turma") */
    private $turmaDestino;
    
    function getEnturmacaoOrigem() {
        return $this->enturmacaoOrigem;
    }

    function getEnturmacaoDestino() {
        return $this->enturmacaoDestino;
    }
    
    function getTurmaDestino() {
        return $this->turmaDestino;
    }
    
    function getNotas() {
        return $this->notas;
    }
    
    function aplicar() {
        $enturmacao = new Enturmacao($this->getMatricula(), $this->turmaDestino);
        $this->enturmacaoDestino = $enturmacao;
        $this->enturmacaoOrigem->encerrar();
    }       
    
}
