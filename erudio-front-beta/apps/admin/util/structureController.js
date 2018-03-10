(function (){
    var structure = angular.module('structure',[]);

    structure.service('Structure', ['$timeout', function($timeout) {

        //ESTRUTURA DE OBJETOS
        this.instituicao = { nome:null, sigla:null, cpfCnpj:null, email:null, endereco:null, telefones:[] };
        this.endereco = { logradouro:null, numero:null, bairro:null, complemento:null, cep:null, cidade: { id:null, nome:null, estado: { id:null, nome:null, sigla:null }, latitude: null, longitude: null } };
        this.telefone = {descricao: '', falarCom: null, numero: null, pessoa: { id: null, tipo_pessoa: null } };
        this.tipoUnidade = { nome: null, sigla: null };
        this.unidade = { nome:null, cpfCnpj:null, email:null, tipo: { id:null }, instituicaoPai: { id:null }, endereco: null, cursos: [], telefones: [] };
        this.cursoUnidade = { curso: { id:null }, unidadeEnsino: { id: null } };
        this.regime = { nome: null };
        this.curso = { nome: null, modalidade: {id: null}, especializado: false, alfabetizatorio: false };
        this.etapa = { nome: null, nomeExibicao: null, ordem: null, modulo:{ id:null }, modeloQuadroHorario:{ id:null }, sistemaAvaliacao:{ id:null }, limiteAlunos: null, integral: true, curso: { id:null } };
        this.disciplina = { nome: null, nomeExibicao: null, cargaHoraria: null, opcional: false, curso: {id: null}, etapa: {id: null}, sigla: null };
        this.modulo = { nome: null, curso: {id: null} };
        this.turno = { nome:null, inicio:null, termino:null };
        this.modeloGradeHorario = { nome: null, curso: {id: null}, quantidadeAulas: null, duracaoAula: null, duracaoIntervalo: null, posicaoIntervalo: null };
        this.quadroHorario = { nome: null, inicio: null, modelo: {id: null}, unidadeEnsino: {id: null}, turno: {id: null}, diasSemana: [{diaSemana: '2'}, {diaSemana: '3'}, {diaSemana: '4'}, {diaSemana: '5'}, {diaSemana: '6'}] };
        this.calendario = { nome: null, dataInicio: null, dataTermino: null, instituicao: {id: null}, calendarioBase: {id: null}, sistemaAvaliacao: {id: null} };

        this.getObjeto = function (obj) {
            switch (obj) {
                case 'instituicao': return angular.copy(this.instituicao); break;
                case 'endereco': return angular.copy(this.endereco); break;
                case 'telefone': return angular.copy(this.telefone); break;
                case 'tipoUnidade': return angular.copy(this.tipoUnidade); break;
                case 'unidade': return angular.copy(this.unidade); break;
                case 'cursoUnidade': return angular.copy(this.cursoUnidade); break;
                case 'regime': return angular.copy(this.regime); break;
                case 'curso': return angular.copy(this.curso); break;
                case 'etapa': return angular.copy(this.etapa); break;
                case 'disciplina': return angular.copy(this.disciplina); break;
                case 'modulo': return angular.copy(this.modulo); break;
                case 'turno': return angular.copy(this.turno); break;
                case 'modeloGradeHorario': return angular.copy(this.modeloGradeHorario); break;
                case 'quadroHorario': return angular.copy(this.quadroHorario); break;
                case 'calendario': return angular.copy(this.calendario); break;
                default: return false; break;
            };
        };
    }]);
})();