(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class ProfAvaliacoesController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, baseService, etapaService, mediaService, $http, shared, $scope) {
            this.http = $http; this.service = service; this.scope = $scope;
            this.baseService = baseService; this.etapaService = etapaService;
            this.util = util; this.mdDialog = $mdDialog; this.scope.shared = shared;
            this.erudioConfig = erudioConfig; this.timeout = $timeout;
            this.permissaoLabel = "AVALIACAO"; this.mediaService = mediaService;
            this.mediasModal = null; this.modalAberto = null; this.possuiEnturmacoes = parseInt(sessionStorage.getItem('possuiEnturmacoes'));
            this.mediaEmUso = null; this.avaliacaoModal = null; this.notasQualitativas = [];
            this.avaliacaoRemover = null; this.turmaSelecionada = null;
            this.dataEntrega = null; this.isQualitativa = false; this.alunoSelecionado = null;
            this.tipoSistemaAvaliacao = ''; this.tamanhoCard = 60;
            this.pagina = 0; this.editandoNota = false; this.avaliacaoLabel = "Avaliações Cadastradas";
            this.listaQualitativa = false; this.avaliacao = null; this.atribuindoNota = false;
            this.editando = false; this.mostraBotao = false; this.disciplinaEscolhida = false;
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        desabilitarPaginacao() { $(".fit-screen").unbind('scroll'); }
        tipoBotaoAdd() { if (this.disciplinasLecionadas !== undefined && this.disciplinasLecionadas.length === 1) { return 'btn-add-avaliacao'; } else { return 'btn-add-avaliacao-fix'; } }
        tipoBotaoVoltar() { if (this.disciplinasLecionadas !== undefined && this.disciplinasLecionadas.length === 1) { return 'btn-voltar-avaliacao'; } else { return 'btn-voltar-avaliacao-fix'; } }

        buscarDisciplinas() {
            this.isQualitativa = false;
            if (!this.util.isVazio(sessionStorage.getItem('turmaSelecionada'))) {
                this.disciplinaEscolhida = true;
                this.turmaSelecionada = JSON.parse(sessionStorage.getItem('turmaSelecionada'));
                this.buscarAvaliacoes(false);
            }
        }

        buscarAvaliacoes(loader) {
            this.mostraBotao = true;
            this.etapaService.get(this.turmaSelecionada.turma.etapa.id, false).then((etapa) => {
                this.tipoSistemaAvaliacao = etapa.sistemaAvaliacao.regime.unidade;
                if(etapa.sistemaAvaliacao.tipo === "QUALITATIVO") {
                    this.opcoes = [
                        { tooltip: 'Notas', icone: 'playlist_add_check', opcao: 'notas', validarEscrita: true },
                    ];
                    this.isQualitativa = true; this.tamanhoCard = 100;
                    this.service.getQualitativas({ page: this.pagina, disciplina: this.turmaSelecionada.id },false).then((avaliacoes) => {
                        if (avaliacoes.length === 0) { this.msgVazio = true; } else { this.msgVazio = false; }
                        if (this.pagina === 0) { this.objetos = avaliacoes; } else { 
                            if (avaliacoes.length !== 0) { this.objetos = this.objetos.concat(avaliacoes); } else { this.finalLista = true; this.pagina--; }
                        }
                    });
                } else {
                    this.opcoes = [
                        { tooltip: 'Notas', icone: 'playlist_add_check', opcao: 'notas', validarEscrita: true },
                        { tooltip: 'Remover', icone: 'delete', opcao: 'remover', validarEscrita: true }
                    ];
                    this.isQualitativa = false; this.tamanhoCard = 60;
                    this.service.getQuantitativas({ page: this.pagina, disciplina: this.turmaSelecionada.id },false).then((avaliacoes) => {
                        if (avaliacoes.length === 0) { this.msgVazio = true; } else { this.msgVazio = false; }
                        if (this.pagina === 0) { this.objetos = avaliacoes; } else { 
                            if (avaliacoes.length !== 0) { this.objetos = this.objetos.concat(avaliacoes); } else { this.finalLista = true; this.pagina--; }
                        }
                    });

                    this.service.getQuantitativas({ page: this.pagina, disciplina: this.turmaSelecionada.id, dataEntrega: moment().format('YYYY-MM-DD')},false).then((avaliacoesDia) => {
                        if (avaliacoesDia.length === 0) { this.msgVazioDia = true; } else { this.msgVazioDia = false; }
                        this.avaliacoesDia = avaliacoesDia;
                    });
                }
            });
        }

        buscarAvaliacoesDia(loader) {
            if (this.isQualitativa){
                this.service.getQualitativas({ page: this.pagina, disciplina: this.turmaSelecionada.id, dataEntrega: moment().format('YYYY-MM-DD')},loader).then((avaliacoesDia) => {
                    if (avaliacoesDia.length === 0) { this.msgVazioDia = true; } else { this.msgVazioDia = false; }
                    this.avaliacoesDia = avaliacoesDia;
                });
            } else {
                this.service.getQuantitativas({ page: this.pagina, disciplina: this.turmaSelecionada.id, dataEntrega: moment().format('YYYY-MM-DD')},loader).then((avaliacoesDia) => {
                    if (avaliacoesDia.length === 0) { this.msgVazioDia = true; } else { this.msgVazioDia = false; }
                    this.avaliacoesDia = avaliacoesDia;
                });
            }
        }

        mostraBotaoVoltar() {
            $('.btn-home').show();
            $('.btn-home').click(() => { $('.btn-home').hide(); this.fecharForm(); });
        }

        carregarAvaliacao(avaliacao) {
            if (this.isQualitativa) {
                this.service.getQualitativa(avaliacao.id).then((objeto) => { this.avaliacao = objeto; this.abrirForm(); });
            } else {
                this.service.getQuantitativa(avaliacao.id).then((objeto) => {
                    this.avaliacao = objeto; this.dataEntrega = moment(objeto.dataEntrega).format('DD/MM/YYYY'); this.abrirForm();
                });
            }
        }

        abrirPainel(index, aluno) {
            this.fecharPainel(); this.alunoSelecionado = aluno; this.buscarNotaQualitativa(aluno);
            $(".condensed-panel-"+index).hide(); $(".expanded-panel-"+index).show();
        }

        fecharPainel() { $(".expanded-panel").hide(); $(".condensed-panel").show(); }

        ultimoPainel(index) { if (this.mediasModal.length-1 === index) { return 'ultimo-panel'; } else { return ''; } }

        abrirForm(){
            this.mostraBotaoVoltar();
            this.editando = true; this.msgVazio = false; this.medias = []; this.desabilitarPaginacao(); this.avaliacaoLabel = "Gerenciar Avaliação";
            if (this.isQualitativa) {
                this.tiposQuali = this.service.getTiposAvaliacaoQuali();
                if (this.util.isNovo(this.avaliacao) || this.util.isVazio(this.avaliacao)) { this.avaliacao = this.service.getEstruturaQualitativa(); }
            } else {
                this.tamanhoCard = 100;
                this.service.getTiposAvaliacao(null, true).then((tiposAvaliacao) => {
                    this.tiposAvaliacao = tiposAvaliacao; this.medias = [];
                    if (this.util.isNovo(this.avaliacao) || this.util.isVazio(this.avaliacao)) { this.avaliacao = this.service.getEstrutura(); }
                });
            }
            this.etapaService.get(this.turmaSelecionada.turma.etapa.id,true).then((etapa) => {
                for (var i=0; i<etapa.sistemaAvaliacao.quantidadeMedias; i++) { this.medias.push((i+1)+'º '+etapa.sistemaAvaliacao.regime.unidade); }
            });
            this.timeout(() => { this.util.aplicarMascaras(); },300);
        }

        fecharForm(){
            this.avaliacaoLabel = "Avaliações Cadastradas"; this.atribuindoNota = false;
            if (this.util.isVazio(this.objetos)) { this.msgVazio = true; } this.habilitarPaginacao();
            this.editando = false; this.dataEntrega = null; this.listaQualitativa = false;
            if (this.isQualitativa) { this.isQualitativa = true; this.avaliacao = this.service.getEstrutura(); } else { this.isQualitativa = false; this.tamanhoCard = 60; this.avaliacao = this.service.getEstruturaQualitativa(); }
        }

        validar(nomeForm) { return this.util.validar(nomeForm); }
        validaCampo() { this.util.validaCampo(); }

        salvar(nomeForm) {
            if (this.isQualitativa) {
                if (!this.util.isVazio(this.avaliacao.nome) && !this.util.isVazio(this.avaliacao.media) && !this.util.isVazio(this.avaliacao.tipo)) {
                    this.avaliacao.disciplina.id = this.turmaSelecionada.id;
                    this.avaliacao.media = parseInt(this.avaliacao.media); delete this.avaliacao.dataEntrega;
                    var resultado = null; delete this.avaliacao.habilidades;
                    if (this.util.isNovoObjeto(this.avaliacao)) { resultado = this.service.salvarAvaliacaoQuali(this.avaliacao,false,true);
                    } else { resultado = this.service.atualizar(this.avaliacao); }
                    resultado.then(() => { this.editando = false; this.buscarAvaliacoes(true); this.fecharForm(); }, (error) => {
                        this.util.toast(error.data.error.message);
                    });
                } else { this.util.toast("Certifique que todos os campos foram preenchidos"); }
            } else {
                if (!this.util.isVazio(this.avaliacao.nome) && !this.util.isVazio(this.avaliacao.media) && !this.util.isVazio(this.avaliacao.tipo.id) && !this.util.isVazio(this.dataEntrega)) {
                    this.avaliacao.disciplina.id = this.turmaSelecionada.id;
                    this.avaliacao.dataEntrega = moment(this.util.converteData(this.dataEntrega)).format('YYYY-MM-DD\T00:00:00P');
                    this.avaliacao.media = parseInt(this.avaliacao.media);
                    var resultado = null; delete this.avaliacao.habilidades;
                    if (this.util.isNovoObjeto(this.avaliacao)) { resultado = this.service.salvarAvaliacaoQuanti(this.avaliacao,false,true);
                    } else { resultado = this.service.atualizar(this.avaliacao,true); }
                    resultado.then(() => { this.editando = false; this.buscarAvaliacoes(true); this.fecharForm(); });
                } else { this.util.toast("Certifique que todos os campos foram preenchidos"); }
            }
        }

        paginar(){ this.pagina++; this.buscarAvaliacoes(true); }

        executarOpcao(event,opcao,objeto) {
            this.avaliacaoRemover = objeto;
            switch (opcao.opcao) {
                case 'remover': this.modalExclusao(event); break; case 'notas': this.darNotas(objeto); break; default: return false; break;
            }
        }
        
        modalExclusao(event) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Avaliação", "Deseja remover esta avaliação?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = self.avaliacaoRemover.id;
                var index = self.util.buscaIndice(id, self.objetos);
                if (index !== false) {
                    self.service.remover(self.avaliacaoRemover, "Avaliação","f"); self.objetos.splice(index,1);
                    if (self.objetos.length === 0) { self.msgVazio = true; } self.buscarAvaliacoesDia();
                }
            });
        }

        paddingAccordion() { if (this.isQualitativa) { return 'no-padding'; } else { return ''; } }

        buscarNotaQualitativa(media) {
            this.service.getNotasQualitativas({avaliacao: this.avaliacaoRemover.id, media: media.id}).then((notas) => {
                this.notasAvaliadas = []; this.notasQualitativas = notas;
                if (notas.length > 0) { this.editandoNota = true; } else { this.editandoNota = false; }
                notas.forEach((nota) => {
                    nota.habilidadesAvaliadas.forEach((habilidade) => { this.notasAvaliadas.push(habilidade.conceito.id); });
                });
            });
        }

        darNotas(objeto,loader) {
            this.mostraBotaoVoltar();
            var self = this; this.desabilitarPaginacao(); this.listaQualitativa = true; this.atribuindoNota = true; 
            this.mediaService.getAll({ disciplinaOfertada: this.turmaSelecionada.id, numero: this.avaliacaoRemover.media },loader).then((medias) => {
                this.mediasModal = medias; this.avaliacaoModal = this.avaliacaoRemover;
                medias.forEach((media) => {
                    this.carregarFoto(media.disciplinaCursada.matricula.idAluno).then((foto) => { media.urlFoto = foto; });
                });
                if (this.isQualitativa) {
                    this.mediaEmUso = objeto;
                    this.service.getConceitos(null).then((conceitos) => {
                        this.conceitos = conceitos;
                        this.service.getHabilidades({disciplina:this.turmaSelecionada.disciplinaId, media: this.avaliacaoRemover.media}).then((habilidades) => {
                            this.habilidades = habilidades;
                        });
                    });   
                } else {
                    this.service.getNotasQuantitativas({avaliacao: objeto.id},true).then((notas) => {
                        if (notas.length > 0) {
                            this.editandoNota = true; this.notasAvaliacao = notas;
                            medias.forEach((media,index) => { 
                                notas.forEach((nota) => { if (nota.media.id === media.id) { media.nota = nota.valor; media.temValor = true; } });
                            });
                        } else { this.editandoNota = false; }
                    });
                }
            });
        }

        carregarFoto (pessoaId) {
            var token = "Bearer "+sessionStorage.getItem('token');
            var fileUrl = this.erudioConfig.urlServidor+'/pessoas/'+pessoaId+'/foto';
            return this.http.get(fileUrl,{headers: {"JWT-Authorization":token},responseType: 'arraybuffer'}).then((data) => {
                return new Promise((resolve) => {
                    var file = new Blob([data.data],{type: 'image/jpg'}); resolve(URL.createObjectURL(file));
                });
            }, (error) => { return new Promise((resolve) => { resolve(this.erudioConfig.dominio+"/apps/professor/avaliacoes/assets/images/avatar.png"); }) });
        }

        isNotaSalva (aluno){ 
            if (!this.util.isVazio(aluno.temValor)) { return 'isNotaSalva'; } else { return 'isNotaNova'; }
        }

        salvarNotasQualitativas() {
            var nota = this.service.getEstruturaNotaQualitativa(); var self = this;
            var tamanho = $('md-select.conceitos').length;
            var preenchidos = $("md-select.conceitos md-select-value span div.md-text").length;
            if (tamanho - preenchidos === 0) {
                if (this.notasQualitativas.length > 0) {
                    angular.forEach(angular.element('md-select.conceitos md-select-value span div.md-text'), (value,key) => {
                        var conceito = {id: null};
                        var element = angular.element(value);
                        var valueText = element[0].textContent;
                        self.conceitos.forEach((conceito) => {
                            if (valueText === conceito.sigla) {
                                conceito.id = conceito.id;
                                if (this.notasQualitativas[0].habilidadesAvaliadas[key] !== undefined) { this.notasQualitativas[0].habilidadesAvaliadas[key].conceito = conceito; }
                                if (tamanho-1 === key) {
                                    if (this.notasQualitativas[0].avaliacao.disciplina.professores !== undefined) { delete this.notasQualitativas[0].avaliacao.disciplina.professores; }
                                    var result = this.service.atualizar(this.notasQualitativas[0],true,true);
                                    result.then((resultado) => {
                                        this.notasQualitativas[0] = resultado;
                                        //this.baseService.salvarLote(this.mediaEmUso,'medias/'+this.mediaEmUso.id+'?calcular=1',"Média","F",true);
                                    });
                                }
                            }
                        });
                    });
                } else {
                    angular.forEach(angular.element('md-select.conceitos md-select-value span div.md-text'), (value,key) => {
                        var habilidade = {habilidade: {id: null}, conceito:{id:null}};
                        var element = angular.element(value);
                        var media = $('#modal-qualitativa').attr('data-media');
                        var valueText = element[0].textContent;
                        nota.avaliacao.id = self.avaliacaoRemover.id;
                        nota.media.id = media;
                        self.conceitos.forEach((conceito) => {
                            if (valueText === conceito.sigla) {
                                habilidade.conceito.id = conceito.id;
                                if (self.habilidades[key] !== undefined) {
                                    habilidade.habilidade.id = self.habilidades[key].id;
                                    nota.habilidadesAvaliadas.push(habilidade);
                                }
                                if (tamanho-1 === key) {
                                    this.service.salvarQualitativa(nota).then((resultado) => {
                                        this.notasQualitativas[0] = resultado;
                                    });
                                }
                            }
                        });
                    });
                }
            } else {
                this.util.toast("Por favor, verifique se todos os itens foram preenchidos.");
            }
        }

        salvarNotasQuantitativas() {
            let tamanho = $('.formNotas').length; let contador = 0;
            $('.formNotas').each((index, element) => {
                var input = $(element).find('input');
                if (input.val() === undefined || input.val() === null || input.val() === '') { contador++; }
                if (index === tamanho-1) { if (contador > 0) { $scope.util.toast('O sistema irá salvar, mas há notas não preenchidas.'); } }
            });
            if (this.editandoNota) {
                var notasSalvas = $('.formNotas').find('input.isNotaSalva');
                var notasNovas = $('.formNotas').find('input.isNotaNova');
                for (var i=0; i<notasSalvas.length; i++) {
                    var objeto = null; var mediaId = $(notasSalvas[i]).attr('data-media');
                    this.notasAvaliacao.forEach((nota,j) => {
                        if (nota.media.id === parseInt(mediaId)) {
                            objeto = nota;
                            var value = $(notasSalvas[i]).val();
                            if (!this.util.isVazio(value)) { 
                                objeto.valor = parseFloat(value); this.service.atualizar(objeto,false);
                                if (i === notasSalvas.length-1) { this.util.toast("Notas atualizadas com sucesso."); }
                            }
                        }
                    });
                }
                for (var j=0; j<notasNovas.length; j++) {
                    var objeto = null; var mediaId = $(notasNovas[j]).attr('data-media'); var valor = $(notasNovas[j]).val();
                    this.salvarNotaQuanti(objeto, mediaId, valor);
                    if (j === notasNovas.length-1) { this.util.toast("Notas salvas com sucesso."); }
                }
            } else {
                var notasNovas = $('.formNotas').find('input.isNotaNova');
                for (var j=0; j<notasNovas.length; j++) {
                    var objeto = null; var mediaId = $(notasNovas[j]).attr('data-media'); var valor = $(notasNovas[j]).val();
                    $(notasNovas[j]).removeClass('isNotaNova'); $(notasNovas[j]).addClass('isNotaSalva'); this.salvarNotaQuanti(objeto, mediaId, valor);
                    if (j === notasNovas.length-1) { this.util.toast("Notas salvas com sucesso."); }
                }
            }
        }

        salvarNotaQuanti(objeto, mediaId, valor) {
            objeto = this.service.getEstruturaQuantitativa(); objeto.media = { id: mediaId }; 
            objeto.avaliacao = { id: this.avaliacaoModal.id };
            if (valor !== undefined && valor !== null && valor !== '') { 
                objeto.valor = parseFloat(valor); this.service.salvarQuantitativa(objeto,false).then((obj) => { this.notasAvaliacao.push(obj); });
            }
        }

        habilitarPaginacao() {
            this.timeout(function(){
                $(".fit-screen").scroll(function(){
                    let distancia = Math.floor(Number($(".conteudo").offset().top - $(document).height()));
                    let altura = Math.floor(Number($(".main-layout").height()));
                    let total = altura + distancia;
                    var active = $("#avaliacoesHome").parent().parent().parent().parent().hasClass('md-active');
                    if (total === 0 && active) { self.paginar(); }
                });
            },500);
        }

        abrirNotaRemota(query){
            var avaliacao = this.scope.shared.getAvaliacao();
            if (!this.util.isVazio(query)) { this.executarOpcao(null,{opcao:'notas'},avaliacao); }
        };

        iniciar(){
            let permissao = this.verificarPermissao(); let self = this; $('.btn-home').hide();
            if (permissao) {
                this.util.comPermissao(); this.escrita = this.verificaEscrita(); 
                this.fab = { tooltip: 'Adicionar Avaliação', icone: 'add', href: this.link+'novo' };
                this.scope.$watch("shared.avaliacaoAbrir.id",(query) => { this.abrirNotaRemota(query); });
                this.scope.$watch("shared.abaHome",(query) => { this.fecharForm(); });
                this.timeout(()=>{ this.validaCampo(); },500);
                this.habilitarPaginacao(); this.buscarDisciplinas();
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    ProfAvaliacoesController.$inject = ["AvaliacaoService","Util","$mdDialog","ErudioConfig","$timeout","BaseService","EtapaService","MediaService","$http","Shared","$scope"];
    angular.module('ProfAvaliacoesController',['ngMaterial', 'util', 'erudioConfig']).controller('ProfAvaliacoesController',ProfAvaliacoesController);
})();