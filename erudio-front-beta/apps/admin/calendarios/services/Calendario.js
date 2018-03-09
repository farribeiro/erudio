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
(function (){
    'use strict';    
    class CalendarioService {        
        constructor(rest,instituicaoService,sistemaAvaliacaoService,modeloGradeHorarioService,unidadeService){
            this.rest = rest;
            this.instituicaoService = instituicaoService;
            this.sistemaAvaliacaoService = sistemaAvaliacaoService;
            this.unidadeService = unidadeService;
            this.modeloGradeHorarioService = modeloGradeHorarioService;
            this.url = 'calendarios';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getDiasPorMes(calendario, mes, loader) { return this.rest.buscar(this.url+'/'+calendario.id+'/meses/'+mes,null,loader);  }
        getInstituicoes(opcoes){ return this.instituicaoService.getAll(opcoes); }
        getUnidades(opcoes){ return this.unidadeService.getAll(opcoes); }
        getSistemaAvaliacoes(opcoes){ return this.sistemaAvaliacaoService.getAll(opcoes); }
        getModeloGradeHorarios(opcoes){ return this.modeloGradeHorarioService.getAll(opcoes); }
        getEstrutura() { return { nome: null, dataInicio: new Date(), dataTermino: new Date(), instituicao: {id: null}, calendarioBase: {id: null}, sistemaAvaliacao: {id: null} }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Calendário", "M"); }
        salvarDias(objeto,calendario) { return this.rest.salvarLote(objeto, this.url+'/'+calendario.id+'/dias', "Calendário", "M", true); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Calendário", "M"); }
        remover(objeto) { this.rest.remover(objeto, "Calendário", "M"); }
    };
    
    angular.module('CalendarioService',[]).service('CalendarioService',CalendarioService);
    CalendarioService.$inject = ["BaseService","InstituicaoService","SistemaAvaliacaoService","ModeloGradeHorarioService","UnidadeService"];
})();