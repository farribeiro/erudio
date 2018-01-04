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

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEntity;

/**
* @ORM\Entity(readOnly = true)
* @ORM\Table(name = "edu_sistema_avaliacao")
*/
class SistemaAvaliacao extends AbstractEntity {
    
    const TIPO_QUANTITATIVO = 'QUANTITATIVO';
    const TIPO_QUALITATIVO = 'QUALITATIVO';
    const TIPO_SEM_AVALIACAO = 'SEM_AVALIACAO';
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column
    */
    private $nome;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(name = "nome_identificacao") 
    */
    private $nomeIdentificacao;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(type = "string") 
    */
    private $tipo;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\ManyToOne(targetEntity = "Regime")
    * @ORM\JoinColumn(nullable = false) 
    */
    private $regime;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\Type("integer")
    * @ORM\Column(name = "quantidade_medias", type = "integer") 
    */
    private $quantidadeMedias;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\Type("float")
    * @ORM\Column(name = "nota_aprovacao", type = "float")
    */
    private $notaAprovacao;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\Type("float")
    * @ORM\Column(name = "frequencia_minima", type = "float") 
    */
    private $frequenciaAprovacao;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\Type("float")
    * @ORM\Column(name = "hora_aula", type = "float") 
    */
    private $horaAula;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(type = "boolean") 
    */
    private $exame;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\Type("integer")
    * @ORM\Column(name = "peso_exame", type = "integer", nullable = true)
    */
    private $pesoExame = 0;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\Type("float")
    * @ORM\Column(name = "nota_aprovacao_exame", type = "float", nullable = true) 
    */
    private $notaAprovacaoExame;
    
    function getNome() {
        return $this->nome;
    }
    
    function getTipo() {
        return $this->tipo;
    }
    
    function getNomeIdentificacao() {
        return $this->nomeIdentificacao;
    }

    function getExame() {
        return $this->exame;
    }
    
    function getQuantidadeMedias() {
        return $this->quantidadeMedias;
    }
    
    function getRegime() {
        return $this->regime;
    }
    
    function getNotaAprovacao() {
        return $this->notaAprovacao;
    }
    
    function getFrequenciaAprovacao() {
        return $this->frequenciaAprovacao;
    }
    
    function getHoraAula() {
        return $this->horaAula;
    }

    function setHoraAula($horaAula) {
        $this->horaAula = $horaAula;
    }
    
    function setFrequenciaAprovacao($frequenciaAprovacao) {
        $this->frequenciaAprovacao = $frequenciaAprovacao;
    }

    function setNotaAprovacao($notaAprovacao) {
        $this->notaAprovacao = $notaAprovacao;
    }
    
    function getNotaAprovacaoExame() {
        return $this->notaAprovacaoExame;
    }

    function setNotaAprovacaoExame($notaAprovacaoExame) {
        $this->notaAprovacaoExame = $notaAprovacaoExame;
    }
    
    function getPesoExame() {
        return $this->pesoExame;
    }

    function setPesoExame($pesoExame) {
        $this->pesoExame = $pesoExame;
    }
    
    function isQualitativo() {
        return $this->tipo === self::TIPO_QUALITATIVO;
    }
    
    function isQuantitativo() {
        return $this->tipo === self::TIPO_QUANTITATIVO;
    }
    
    function utilizaNotas() {
        return $this->tipo != self::TIPO_SEM_AVALIACAO;
    }
    
}
