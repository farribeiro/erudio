(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class MediaController {
        constructor(service, util, erudioConfig, $timeout, $mdDialog, disciplinaCursadaService, avaliacaoService, etapaService, $http, mediaService, disciplinaOfertadaService, shared, $scope){
            this.service = service; this.util = util; this.shared = shared; this.disciplinaEscolhida = false;
            this.erudioConfig = erudioConfig; this.http = $http; this.mediaService = mediaService; this.scope = $scope;
            this.disciplina = JSON.parse(sessionStorage.getItem('turmaSelecionada')); this.permissaoLabel = "AVALIACAO";
            this.disciplinaCursadaService = disciplinaCursadaService; this.mdDialog = $mdDialog; this.scope.shared = shared;
            this.medias = []; this.headers = []; this.cursadas = []; this.etapaService = etapaService;
            this.avaliacaoService = avaliacaoService; this.isQualitativa = false; this.parciais = []; this.timeout = $timeout;
            this.disciplinaOfertadaService = disciplinaOfertadaService; this.possuiEnturmacoes = parseInt(sessionStorage.getItem('possuiEnturmacoes'));
            this.iniciar();
        }

        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }

        buscarAlunos(reiniciar,media) {
            if (!this.util.isVazio(sessionStorage.getItem('turmaSelecionada'))) {
                this.disciplinaEscolhida = true;
                if(reiniciar) { this.medias = []; this.cursadas = []; }
                this.etapaService.get(this.disciplina.turma.etapa.id, false).then((etapa) => {
                    if(etapa.sistemaAvaliacao.tipo === "QUALITATIVO") { this.isQualitativa = true; } else { this.isQualitativa = false; }
                });
                this.disciplinaCursadaService.getAll({ disciplinaOfertada: this.disciplina.id, view:'medias' },true).then((cursadas) => {
                    this.cursadas = cursadas;
                    cursadas.forEach((cursada,i) => {
                        if (i === 0) {
                            var medias = [];
                            cursada.medias.forEach((media, j) => { this.headers.push("M"+(j+1)); this.headers.push('Frequência'); });
                        }
                        if (media !== undefined) {
                            cursada.medias.forEach((mediaObj) => { if (mediaObj.id === media.id) { this.buscarNotaIndividual(mediaObj); } });
                        }
                        cursada.matricula.aluno.foto = this.util.avatarPadrao();
                        this.carregarFoto(cursada.matricula.aluno.id).then((foto) => { cursada.matricula.aluno.foto = foto; });
                    });
                });
            }
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

        buscarNotaIndividual(media) {
            if (this.isQualitativa) {
                this.avaliacaoService.getNotasQualitativas({media:media.id}).then((parciais) => {
                    media.parciais = parciais;
                });
            } else {
                this.avaliacaoService.getNotasQuantitativas({media:media.id}).then((parciais) => {
                    media.parciais = parciais;
                });
            }
        }

        abrirPainel(index, medias) {
            this.fecharPainel();
            $(".condensed-panel-"+index).hide();
            $(".expanded-panel-"+index).show();
            this.parciais = [];
            medias.forEach((media) => { this.buscarNotaIndividual(media),false; });
        }

        fecharPainel() {
            $(".expanded-panel").hide();
            $(".condensed-panel").show();
        }

        ultimoPainel(index) {
            if (this.cursadas.length-1 === index) {
                return 'ultimo-panel';
            } else {
                return '';
            }
        }

        calcularMedia(media,$index) {
            this.mediaService.get(media.id,true).then((mediaObj) => {
                mediaObj.valor = null; mediaObj.disciplinaCursada = { id: mediaObj.disciplinaCursada.id }; delete mediaObj.faltas;
                this.mediaService.atualizar(mediaObj).then((mediaCalculada) => {
                    this.buscarAlunos(false, mediaCalculada);
                });
            });
        }

        recalcularMedia(media,index) {
            this.mediaService.get(media.id,true).then((mediaObj) => {
                mediaObj.valor = null; mediaObj.disciplinaCursada = { id: mediaObj.disciplinaCursada.id }; delete mediaObj.faltas;
                this.mediaService.atualizar(mediaObj).then((mediaCalculada) => {
                    $('.media-valor-'+mediaCalculada.id).html(mediaCalculada.valor);
                });
            });
        }

        recalcularMediaRemota(valor) {
            if (valor && !this.util.isVazio(valor)) {
                this.shared.retornarParaMedias = null;
                var cursada = this.shared.getCursada();
                var index = $('.expanded-panel:visible').attr('data-index-id');
                var avaliacaoId = this.shared.getAvaliacao().id;
                $('.nota-avaliacao-'+avaliacaoId).html(this.shared.getAvaliacaoNota());

                cursada.medias.forEach((media,i) => {
                    if (!this.util.isVazio(media.valor) && !this.util.isVazio(media.parciais)) {
                        media.parciais.forEach((parcial, j) => {
                            if (parcial.avaliacao.id === avaliacaoId) {
                                this.recalcularMedia(media, index);
                            }
                        });
                    }
                });
            }
        }

        redirectAvaliacao(avaliacao,cursada) {
            this.shared.setAvaliacao(null);
            this.shared.setCursada(null);
            this.timeout(() => {
                this.shared.setCursada(cursada);
                this.shared.setAvaliacao(avaliacao); var self = this;
                angular.forEach(angular.element(".md-tab"), (val,key) => {
                    if ($(val).text() === 'avaliações') { setTimeout(() => { $(val).trigger('click'); },10); }
                });
            },200);
        }

        calcularMediaFinal() {
            let confirm = this.util.customDialog(event, "Média Final", "Deseja realmente calcular a média final?", 'calcular', this.mdDialog);
            this.mdDialog.show(confirm).then(() => {
                this.disciplinaOfertadaService.fecharMediaFinal(this.disciplina).then(() => { this.buscarAlunos(true); }, () => {
                    this.util.toast('Preencha todas as médias antes de calcular a média final.');
                });
            });
        }

        iniciar(){
            let permissao = this.verificarPermissao();
            if (permissao) {
                this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back'};
                this.util.comPermissao();
                this.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
                this.scope.$watch("shared.retornarParaMedias",(query) => { this.recalcularMediaRemota(query); });
                this.escrita = this.verificaEscrita();
                this.isAdmin = this.util.isAdmin();
                this.buscarAlunos(true);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }

     MediaController.$inject = ["CalendarioService","Util","ErudioConfig","$timeout","$mdDialog","DisciplinaCursadaService","AvaliacaoService","EtapaService","$http","MediaService","DisciplinaOfertadaService","Shared","$scope"];
    angular.module('MediaController',['ngMaterial', 'util', 'erudioConfig']).controller('MediaController',MediaController);
})();