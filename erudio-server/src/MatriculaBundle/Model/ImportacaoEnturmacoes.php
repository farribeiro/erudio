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

namespace MatriculaBundle\Model;

use JMS\Serializer\Annotation as JMS;
use CursoBundle\Entity\Turma;

/**  
 * Classe que representa uma operação de importação de matrículas entre turmas, onde
 * a turma origem é de uma etapa inferior à turma de destino. Tal operação é usada
 * para facilitar a reenturmação dos alunos para a etapa seguinte do curso.
 */
class ImportacaoEnturmacoes {
    
    /**
     * @JMS\Groups({"LIST"})
     * @JMS\MaxDepth(depth = 1) 
     * @JMS\Type("CursoBundle\Entity\Turma") 
     */
    private $turmaOrigem;
    
    /**
     * @JMS\Groups({"LIST"})
     * @JMS\MaxDepth(depth = 1) 
     * @JMS\Type("CursoBundle\Entity\Turma") 
     */
    private $turmaDestino;
    
    /** @JMS\Type("array<MatriculaBundle\Entity\Enturmacao>") */
    public $listaExclusoes;
    
    function __construct(Turma $turmaOrigem, Turma $turmaDestino, array $listaExclusoes = []) {
        $this->turmaOrigem = $turmaOrigem;
        $this->turmaDestino = $turmaDestino;
        $this->listaExclusoes = $listaExclusoes;
    }

    function getTurmaOrigem() {
        return $this->turmaOrigem;
    }

    function getTurmaDestino() {
        return $this->turmaDestino;
    }
    
    function getListaExclusoes() {
        return $this->listaExclusoes;
    }
    
}
