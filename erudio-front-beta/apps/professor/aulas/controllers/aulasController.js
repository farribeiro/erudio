(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class AulaController {
        constructor(service, util, erudioConfig, $timeout, turmaService, aulaService, diaService, quadroHorarioService, frequenciaService, $http, $mdDialog, $mdMenu, disciplinaCursadaService, shared, scope){
            this.service = service; this.util = util; this.mdDialog = $mdDialog; this.scope = scope; this.scope.shared = shared;
            this.erudioConfig = erudioConfig; this.turmaService = turmaService;
            this.aulaService = aulaService; this.quadroHorarioService = quadroHorarioService;
            this.frequenciaService = frequenciaService; this.diaService = diaService;
            this.timeout = $timeout; this.http = $http; this.mdMenu = $mdMenu;
            this.turma = null; this.disciplina = JSON.parse(sessionStorage.getItem('turmaSelecionada'));
            this.horarios = []; this.menuHorarios = []; this.possuiEnturmacoes = parseInt(sessionStorage.getItem('possuiEnturmacoes'));
            this.aulasSemana = {segunda: [], terca: [], quarta: [], quinta: [], sexta: []};
            this.calendario = null; this.mesCalendario = []; this.disciplinaCursadaService = disciplinaCursadaService;
            this.diaSelecionado = null; this.semanaCalendario = []; this.fotoPessoa = null;
            this.permissaoLabel = "HOME_PROFESSOR"; this.nomeForm = "calendarioForm";
            this.aula = null; this.aulas = null; this.mes = null; this.ano = null; this.temFoto = false;
            this.disciplinaEscolhida = false; this.delayUpdate = null; this.conteudo = null; this.fotoSrc = null;
            this.chamada = false; this.alunos = null; this.anotacoes = null; this.alunoSelecionado = null;
            this.iniciar();
        }

        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }

        resetCalendario() { this.mesCalendario = []; this.semanaCalendario = []; }

        preparaCalendario(mes,ano) {
            var self = this;
            if (this.util.isVazio(mes) && this.util.isVazio(ano)) {
                var dateBase = new Date(); this.mes = dateBase.getMonth(); this.ano = dateBase.getFullYear();
                this.preparaCalendario(this.mes, this.ano);
            } else {
                this.diaS = 1; this.mes = mes; this.ano = ano; this.diaSemana = new Date(this.ano,this.mes,this.diaS).getDay();
                this.counterCalendario = this.diaSemana; this.gapInicio = this.diaSemana;
                this.diasMes = this.util.diasNoMes(this.mes,this.ano); this.semanaCalendario = new Array(this.gapInicio); this.nomeMes = this.util.nomeMes(this.mes);
                this.timeout(function(){ self.linkPaginacao(); },500); self.buscarEventos();
            }
        };

        buscarCalendario() {
            if (!this.util.isVazio(sessionStorage.getItem('turmaSelecionada'))) {
                this.disciplinaEscolhida = true;
                var self = this; var obj = JSON.parse(sessionStorage.getItem('turmaSelecionada'));
                this.horarios = []; this.menuHorarios = []; this.aulasSemana = {segunda: [], terca: [], quarta: [], quinta: [], sexta: []};
                this.turmaService.get(obj.turma.id,true).then((turma) => {
                    this.turma = turma;
                    this.quadroHorarioService.getHorarios(turma.quadroHorario.id,turma.id,true).then((horarios) => {
                        for (var i=0; i<horarios.length; i++) {
                            horarios[i].inicio = this.util.converteHora(horarios[i].inicio);
                            if (!this.util.isVazio(horarios[i].disciplinaAlocada)) {
                                horarios[i].inicio = this.util.converteHora(horarios[i].inicio);
                                switch (horarios[i].diaSemana.diaSemana) {
                                    case "2":
                                        if (horarios[i].disciplinaAlocada.id === this.disciplina.id) { this.aulasSemana.segunda.push(horarios[i]); }
                                    break;
                                    case "3":
                                        if (horarios[i].disciplinaAlocada.id === this.disciplina.id) { this.aulasSemana.terca.push(horarios[i]); }
                                    break;
                                    case "4":
                                        if (horarios[i].disciplinaAlocada.id === this.disciplina.id) { this.aulasSemana.quarta.push(horarios[i]); }
                                    break;
                                    case "5":
                                        if (horarios[i].disciplinaAlocada.id === this.disciplina.id) { this.aulasSemana.quinta.push(horarios[i]); }
                                    break;
                                    case "6":
                                        if (horarios[i].disciplinaAlocada.id === this.disciplina.id) { this.aulasSemana.sexta.push(horarios[i]); }
                                    break;
                                    default: return false; break;
                                }
                            }
                        }
                    });
                    this.calendario = turma.calendario; this.preparaCalendario();
                });
            }
        }

        mostraBotaoVoltar() {
            $('.btn-home').show();
            $('.btn-home').click(() => { $('.btn-home').hide(); this.fecharForm(); });
        }

        escolherDia(dia,horarios) {
            if (horarios.length > 0 && dia.efetivo !== undefined && dia.efetivo) {
                this.mostraBotaoVoltar();
                this.diaSelecionado = dia;
                var obj = JSON.parse(sessionStorage.getItem('turmaSelecionada'));
                this.aulaService.getAll({dia: dia.id, disciplina: obj.id},true).then((aulas) => {
                    if (aulas.length === 0) {
                        horarios.forEach((horario,i) => {
                            var aula = this.aulaService.getEstruturaAula();
                            aula.turma.id = obj.turma.id;
                            aula.dia.id = dia.id;
                            if (!obj.turma.etapa.frequenciaUnificada) {
                                aula.disciplina.id = obj.id;
                                aula.horario.id = horario.id;
                            } else {
                                delete aula.disciplina; delete aula.horario;
                            }
                            var resultado = this.aulaService.salvar({aulas:[aula]});
                            if (i === horarios.length-1){
                                resultado.then(() => { this.escolherDia(dia, horarios); });
                            }
                        });
                    } else {
                        aulas.forEach((aula) => {
                            if (!this.util.isVazio(aula.horario)) {
                                aula.horario.inicio = this.util.converteHora(aula.horario.inicio);
                            }
                        });
                        this.aula = aulas[0]; this.aulas = aulas; this.buscarAnotacoes(this.aula);
                        this.frequenciaService.getAll({aula: this.aula.id}, true).then((alunos) => {
                            /*if (alunos.length === 0) {
                                this.buscarAlunos(obj);
                            } else {*/
                                this.alunos = alunos;
                                alunos.forEach((aluno,i) => {
                                    this.carregarFoto(aluno.aluno.id).then((foto) => { aluno.aluno.urlFoto = foto; });
                                });
                            //}
                        });
                    }
                });
                this.chamada = true;
            } else { this.util.toast("Não há nenhum horário de aula neste dia para esta disciplina."); }
        }

        initFotos (id) {

        }

        buscarAlunos(disciplina){
            this.alunos = [];
            this.disciplinaCursadaService.getAll({ disciplinaOfertada: disciplina.id, view:'medias' },true).then((cursadas) => {
                cursadas.forEach((cursada,i) => {
                    var frequencia = this.frequenciaService.getEstruturaFrequencia();
                    var aluno = cursada.matricula.aluno; aluno.foto = this.util.avatarPadrao();
                    this.carregarFoto(cursada.matricula.aluno.id).then((foto) => {
                        aluno.urlFoto = foto;
                        frequencia.aluno = aluno; frequencia.aula.id = this.aula.id;
                        this.alunos.push(frequencia);
                    });
                });
            });
        }

        atualizarListaFrequencia(aula) {
            var obj = JSON.parse(sessionStorage.getItem('turmaSelecionada'));
            this.buscarAnotacoes(aula); this.alunos = [];
            this.frequenciaService.getAll({aula: aula.id}, true).then((alunos) => {
                /*if (alunos.length === 0) {
                    this.buscarAlunos(obj);
                } else {*/
                    this.alunos = alunos;
                    alunos.forEach((aluno) => {
                        this.carregarFoto(aluno.aluno.id).then((foto) => { aluno.aluno.urlFoto = foto; });
                    });
                //}
            });
        }

        buscarAnotacoes(aula) {
            this.aulaService.getAnotacoes({aula: aula.id},true).then((anotacoes) => {
                this.anotacoes = anotacoes;
            });
        }

        salvarAnotacao() {
            var dataAula = new Date(this.diaSelecionado.data);
            if (dataAula <= new Date()) {
                var anotacao = this.aulaService.getEstruturaAnotacao();
                if (this.util.isVazio(this.conteudo)) {
                    this.util.toast("Não é possível criar anotações vazias.");
                } else {
                    anotacao.aula.id = this.aula.id;
                    anotacao.conteudo = this.conteudo;
                    this.aulaService.salvarAnotacao(anotacao,true).then(() => { this.conteudo = null; this.buscarAnotacoes(this.aula); });
                }
            } else {
                this.util.toast("Não é possível criar anotações em aulas futuras.");
            }
        }

        removerAnotacao(event, anotacao) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Anotação", "Deseja remover esta anotação?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = anotacao.id;
                var index = self.util.buscaIndice(id, self.anotacoes);
                if (index !== false) {
                    self.aulaService.remover(anotacao, "Anotação","f");
                    self.anotacoes.splice(index,1);
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

        fecharForm(){
            this.chamada = false;
            this.alunos = []; this.conteudo = null;
        }

        verStatus(status) {
            if (status === "PRESENCA") {
                return 'presenca-cor';
            } else if (status === "FALTA") {
                return 'falta-cor';
            } else {
                return 'falta-justificada-cor';
            }
        }

        darFrequencia(frequencia, loader) {
            var dataAula = new Date(this.diaSelecionado.data);
            if (dataAula <= new Date()) {
                if (frequencia.status === "PRESENCA") {
                    frequencia.status = "FALTA";
                    if (!this.util.isVazio(frequencia.justificativa)) { frequencia.justificativa = ''; }
                    //if (!this.util.isVazio(frequencia.id)) {
                        this.frequenciaService.atualizar(frequencia,true);
                    //} else { this.frequenciaService.salvar(frequencia,true).then((novaFrequencia) => { frequencia.id = novaFrequencia.id}); }
                } else {
                    frequencia.status = "PRESENCA"; frequencia.justificativa = "";
                    $(".justificativa-aluno-"+frequencia.id).hide();
                    //if (!this.util.isVazio(frequencia.id)) {
                        this.frequenciaService.atualizar(frequencia,true);
                    //} else { this.frequenciaService.salvar(frequencia,true).then((novaFrequencia) => { frequencia.id = novaFrequencia.id}); }
                }
            } else {
                this.util.toast("Não é possível dar presença em aulas futuras.");
            }
        }

        atualizarJustificativa() {
            if (this.alunoSelecionado.justificativa.length > 0) { this.alunoSelecionado.status = "FALTA_JUSTIFICADA"; } else { this.alunoSelecionado.status = "FALTA"; }
            this.frequenciaService.atualizar(this.alunoSelecionado,true).then(() => { this.fecharPainel(); });
        }

        mostrarJustificativa(aluno) {
            this.alunoSelecionado = aluno;
            $(".justificativa-aluno-"+alunoId).toggle();
        }

        buscarEventos() {
            this.service.getDiasPorMes(this.calendario,this.mes+1,true).then((dias) => {
                this.dias = dias;
                for (var i=0; i<this.diasMes; i++) {
                    this.counterCalendario++; this.semanaCalendario.push(this.dias[i]);
                    if (this.counterCalendario === 7) { this.mesCalendario.push(this.semanaCalendario); this.counterCalendario = 0; this.semanaCalendario = []; }
                    if (i === this.diasMes-1) {
                        var dataFinal = new Date(this.ano,this.mes,i+1); this.gapFinal = 6 - dataFinal.getDay();
                        for (var j=0; j<this.gapFinal; j++) { this.semanaCalendario.push(null); if (j === this.gapFinal-1) { this.mesCalendario.push(this.semanaCalendario); } }
                    }
                }
            });
        }

        linkPaginacao() {
            this.proximoMes = new Date(this.ano,this.mes,1); this.proximoMes.setMonth(this.proximoMes.getMonth()+1);
            this.mesAnterior = new Date(this.ano,this.mes,1); this.mesAnterior.setMonth(this.mesAnterior.getMonth()-1);
        }

        classeTipoDia(dia, aulas){
            var classe = '';
            if (!this.util.isVazio(dia)) {
                if (dia.efetivo) {
                    classe += 'calendario-dia-efetivo';
                    if (!this.util.isVazio(aulas) && aulas.length > 0) { return classe +' clicavel';  } else { return classe; }
                }
                else if (dia.letivo) { classe += 'calendario-dia-letivo'; return classe; }
                else { classe += 'calendario-dia-nao-letivo'; return classe; }
            }
        }

        paginaProxima(){ this.resetCalendario(); this.preparaCalendario(this.proximoMes.getMonth(),this.proximoMes.getFullYear()); }
        paginaAnterior(){ this.resetCalendario(); this.preparaCalendario(this.mesAnterior.getMonth(),this.mesAnterior.getFullYear()); }

        abrirPainel(index, aluno) {
            if (aluno.status !== 'PRESENCA') {
                this.fecharPainel();
                this.alunoSelecionado = aluno;
                $(".condensed-panel-"+index).hide();
                $(".expanded-panel-"+index).show();
            }
        }

        fecharPainel() {
            $(".expanded-panel").hide();
            $(".condensed-panel").show();
        }

        ultimoPainel(index,status) {
            var classe = '';
            if (status === "PRESENCA") {
                classe += "nao-clicavel"
                if (this.alunos.length-1 === index) { return classe + ' ultimo-panel'; } else { return classe; }
            } else {
                if (this.alunos.length-1 === index) { return 'ultimo-panel'; } else { return ''; }
            }
        }

        mostraCameraStream(pessoaId) {
            $(".cortina-foto").show(); this.fotoPessoa = pessoaId;
            var video = document.querySelector('#camera-stream');
            navigator.getMedia = ( navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
            if (!navigator.getMedia) { this.util.toast("Seu navegador ainda não possui suporte para tirar fotos.");
            } else {
                navigator.getMedia({ video: true
                },(stream) => {
                    video.src = window.URL.createObjectURL(stream); $('.start-video').show(); video.play();
                },(error) => { console.log(error); });
            }
        }

        capturar() {
            var imageW = $('#camera-stream').width(); var imageH = $('#camera-stream').height();
            var foto = this.tirarFoto(); var image = document.querySelector("#foto-perfil-preview");
            image.setAttribute('src',foto); image.classList.add('visivel');
            image.setAttribute('style','width:'+imageW+'px; height:'+imageH+'px;');
            this.temFoto = true;
        }

        tirarFoto() {
            var video = document.querySelector('#camera-stream');
            var hidden_canvas = document.querySelector('canvas');
            var context = hidden_canvas.getContext('2d');
            var width = video.videoWidth; var height = video.videoHeight;
            if (width && height) {
                hidden_canvas.width = width; hidden_canvas.height = height;
                context.drawImage(video, 0, 0, width, height);
                return hidden_canvas.toDataURL('image/jpeg');
            }
        }

        removerPreFoto() {
            var image = document.querySelector("#foto-perfil-preview"); image.setAttribute('src',"");
            image.classList.remove('visivel'); this.temFoto = false;
        }

        salvarFoto(){
            var src = $("#foto-perfil-preview").attr('src'); var image = document.querySelector("#foto-perfil-preview"); this.fotoSrc = src;
            var token = "Bearer "+sessionStorage.getItem('token');
            var fileUrl = this.erudioConfig.urlServidor+'/pessoas/'+this.fotoPessoa+'/foto';
            if (!this.util.isVazio(src)) {
                var file = this.util.dataURLtoFile(src,'avatar_'+this.fotoPessoa+'.jpg');
                var fd = new FormData(); fd.append('foto',file);
                this.timeout(()=>{
                    this.http.post(fileUrl,fd,{
                        headers: {
                            "JWT-Authorization":token,
                            'Content-type':undefined
                        }
                    }).then(() => { $('.aluno-foto-'+this.fotoPessoa).attr('src',src); });
                },500);
            }
            this.cancelarFoto();
        }

        cancelarFoto() {
            var image = document.querySelector("#foto-perfil-preview");
            var video = document.querySelector('#camera-stream'); video.src = "";
            image.setAttribute('src',""); image.classList.remove('visivel');
            this.temFoto = false; $('.start-video').hide();
            $(".cortina-foto").hide();
        }

        iniciar(){
            let permissao = this.verificarPermissao();
            if (permissao) {
                this.util.comPermissao(); $('.btn-home').hide();
                this.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
                this.escrita = this.verificaEscrita();
                this.isAdmin = this.util.isAdmin();
                this.scope.$watch("shared.abaHome", (query) => { this.fecharForm(); });
                this.buscarCalendario();
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }

    AulaController.$inject = ["CalendarioService","Util","ErudioConfig","$timeout","TurmaService","AulaService","DiaService","QuadroHorarioService","FrequenciaService","$http","$mdDialog","$mdMenu","DisciplinaCursadaService","Shared","$scope"];
    angular.module('AulaController',['ngMaterial', 'util', 'erudioConfig']).controller('AulaController',AulaController);
})();