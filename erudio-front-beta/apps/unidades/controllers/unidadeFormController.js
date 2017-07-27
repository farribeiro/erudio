(function (){
    var unidadesForm = angular.module('unidadesForm',['ngMaterial', 'util', 'erudioConfig']);
    unidadesForm.controller('UnidadeFormController',['$scope', 'Util', 'ErudioConfig', '$routeParams', '$timeout', function($scope, Util, ErudioConfig, $routeParams, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Unidades de Ensino');
        
        //UNIDADE EM USO
        $scope.unidade = Util.getEstrutura('unidade');
        $scope.unidade.endereco = Util.getEstrutura('endereco');
        $scope.telefone = Util.getEstrutura('telefone');
        
        //ATRIBUTOS EXTRAS
        $scope.telefones = []; $scope.curso = Util.getEstrutura('cursoUnidade'); $scope.cursosUnidade = [];
        
        //SETA SUBHEADER DO FORM
        $scope.subheaders =[{label: 'Informações Institucionais'}, {label: 'Contatos'}, {label: 'Endereço'}, {label: 'Cursos'}];
        
        //TEMPLATE DOS BLOCOS DE INPUTS -
        $scope.inputs = [{ href: Util.getInputBlockCustom('unidades','informacoesPessoais') }, { href: Util.getInputBlockCustom('unidades','contatos') }, { href: Util.getInputBlockCustom('unidades','endereco') } , { href: Util.getInputBlockCustom('unidades','cursos') }];
        
        //CRIAR FORMS
        $scope.forms = [{ nome: 'unidadesForm', subheaders: $scope.subheaders }];
        
        //OPCOES DO BOTAO VOLTAR
        $scope.link = '/#!/unidades/';
        $scope.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: ErudioConfig.dominio + $scope.link};
        
        //BUSCANDO UNIDADE
        $scope.buscarUnidade = function () {
            $scope.telefones = []; $scope.telefone = Util.getEstrutura('telefone');
            if (!Util.isNovo($routeParams.id)) {
                var promise = Util.um('unidades-ensino',$routeParams.id);
                promise.then(function(response){
                    $scope.unidade = response.data; $scope.buscarEstados();
                    $scope.buscarTipos(); $scope.buscarInstituicoes(); $scope.buscarCursos(); $scope.buscarCursosOfertados();
                    if ($scope.unidade.telefones !== undefined) { $scope.getTelefones($scope.unidade.telefones); }
                    if (!Util.isVazio($scope.unidade.endereco)) { $scope.getEndereco($scope.unidade.endereco.id); }
                    Util.aplicarMascaras(); $timeout(function(){ $('#unidadeContatoNumero').find('input').change(function(){ $scope.adicionarTelefone(); }); },300);
                    $timeout(function(){ $scope.initMapa($scope.unidade.endereco.latitude, $scope.unidade.endereco.longitude); },500);
                });
            } else { 
                $timeout(function(){ Util.aplicarMascaras(); $scope.initMapa(); $('#unidadeContatoNumero').find('input').change(function(){ $scope.adicionarTelefone(); }); },300);
                $scope.buscarEstados(); $scope.buscarTipos(); $scope.buscarInstituicoes(); $scope.buscarCursos();
            }
        };
        
        //RECUPERANDO ENDERECO
        $scope.getEndereco = function (id) { var promise = Util.um('enderecos',id); promise.then(function (response) { $scope.unidade.endereco = response.data; $scope.buscarCidades($scope.unidade.endereco.cidade.estado.id); }); };
        
        //RECUPERANDO TELEFONES
        $scope.getTelefones = function (telefones) {
            if (telefones.length > 0) {
                $('md-divider.hide').show(); $('.md-subheader.hide').show();
                for (var i=0; i<telefones.length; i++) { var promise = Util.um('telefones',telefones[i].id); promise.then(function (response) { $scope.telefones.push(response.data); }); }
            } else { $('md-divider.hide').hide(); $('.md-subheader.hide').hide(); }
        };
        
        //VALIDA CAMPO
        $scope.validaCampo = function () { Util.validaCampo(); };
        
        //BUSCANDO ESTADOS
        $scope.buscarEstados = function () { var promiseEstados = Util.getEstados(); promiseEstados.then(function (response){ $scope.estados = response.data; }); };
        
        //BUSCANDO CIDADES
        $scope.buscarCidades = function (estado) { var promiseCidade = Util.getCidades(estado); promiseCidade.then(function(response){ $scope.cidades = response.data; }); };
        
        //BUSCANDO TIPOS
        $scope.buscarTipos = function () { var promise = Util.buscar('tipos-unidade-ensino',null); promise.then(function(response){ $scope.tipos = response.data; }); };
        
        //BUSCANDO INSTITUIÇÕES
        $scope.buscarInstituicoes = function () { var promise = Util.buscar('instituicoes',null); promise.then(function(response){ $scope.instituicoes = response.data; }); };
        
        //BUSCANDO CURSOS
        $scope.buscarCursos = function () { var promise = Util.buscar('cursos',null); promise.then(function(response){ $scope.cursos = response.data; }); };
        
        //BUSCANDO CURSOS OFERTADOS
        $scope.buscarCursosOfertados = function () {
            var promise = Util.buscar('cursos-ofertados',{unidadeEnsino:$scope.unidade.id});
            promise.then(function(response){ var arr = response.data; arr.forEach(function(cursoOfertado){ $scope.cursosUnidade[cursoOfertado.id] = cursoOfertado; }); });
        };
        
        //CHECA CURSO
        $scope.verificarCurso = function (cursoId) { var retorno = false; $scope.cursosUnidade.forEach(function(cursoUnidade){ if (cursoUnidade.curso.id === cursoId) { retorno = true; } }); return retorno; };
        
        //OPÇÕES DE TELEFONE
        $scope.tiposTelefone = ['CELULAR','COMERCIAL','RESIDENCIAL'];
        
        //SALVAR UNIDADE
        $scope.salvar = function () {
            if ($scope.validar('unidadesForm')) {
                var endereco = $scope.unidade.endereco;
                delete $scope.unidade.endereco; delete $scope.unidade.telefones;
                var resultado = Util.salvar(endereco,'enderecos');
                resultado.then(function (response){
                    $scope.unidade.endereco = { id: response.data.id }; 
                    var resultadoPessoa = Util.salvar($scope.unidade,'unidades-ensino','Unidade de Ensino','F');
                    resultadoPessoa.then(function(response){
                        if (Util.isNovo($routeParams.id)) {
                            $scope.cursosUnidade.forEach(function(cursoU){ cursoU.unidadeEnsino.id = response.data.id; Util.salvar(cursoU,'cursos-ofertados'); });
                        }
                        var telefones = $scope.telefones; var tipo_pessoa = response.data.tipo_pessoa;
                        if (telefones.length > 0) {
                            for (var i=0; i<telefones.length; i++) { 
                                telefones[i].pessoa.id = response.data.id; telefones[i].pessoa.tipo_pessoa = tipo_pessoa; var resultadoTelefone = Util.salvar(telefones[i],'telefones');
                                if (i === telefones.length-1) { resultadoTelefone.then(function(){ $scope.buscarUnidade(); Util.redirect($scope.fab.href);; }); }
                            }
                        } else { $scope.buscarUnidade(); Util.redirect($scope.fab.href); }
                    });
                });
            }
        };
        
        //VALIDAR FORM
        $scope.validar = function (formId) { 
            var obrigatorios = Util.validar(formId); var cnpj = null;
            if (!Util.isVazio($scope.unidade.cpfCnpj)) { 
                cnpj = Util.validarCNPJ($scope.unidade.cpfCnpj);
                if (obrigatorios && cnpj) { return true; } else { return false; }
            } else { if (obrigatorios) { return true; } else { return false; } }
        };
        
        //ADICIONAR TELEFONE
        $scope.adicionarTelefone = function () { 
            if (!Util.isVazio($scope.telefone.numero) && !Util.isVazio($scope.telefone.descricao)) {
                $scope.telefones.push($scope.telefone); $('md-divider.hide').show(); $('.md-subheader.hide').show();
                $scope.unidade.telefones = $scope.telefones; Util.toast('Telefone adicionado, salve para garantir as modificações.');
                $scope.telefone = Util.getEstrutura('telefone');
            } else { Util.toast('Ambos os campos devem ser preenchidos para adicionar um telefone.'); }
        };
        
        //REMOVER TELEFONE
        $scope.removerTelefone = function (telefone, index) {
            var promise = Util.remover(telefone, 'Telefone', 'm');
            promise.then(function(){ 
                $scope.telefones.splice(index,1);
                if ($scope.telefones.length > 0) { $('md-divider.hide').show(); $('.md-subheader.hide').show(); } else { $('md-divider.hide').hide(); $('.md-subheader.hide').hide(); }
            });
        };
        
        //ADICIONAR/REMOVER CURSO
        $scope.adicionarCurso = function (cursoId) {
            if (!Util.isNovo($routeParams.id)) {
                $scope.curso.curso.id = cursoId; $scope.curso.unidadeEnsino.id = $scope.unidade.id;
                if (cursoId in $scope.cursosUnidade) {
                    var promise = Util.um('cursos-ofertados',cursoId);
                    promise.then(function(response){ $scope.cursosUnidade.splice(cursoId,1); Util.remover(response.data,'cursos-ofertados','Curso','m'); });
                } else { $scope.cursosUnidade[cursoId] = $scope.curso; Util.salvar($scope.curso,'cursos-ofertados','Curso','M'); }
            } else {
                var curso = angular.copy($scope.curso);
                if (cursoId in $scope.cursosUnidade) { $scope.cursosUnidade.splice(cursoId,1); } else { curso.curso.id = cursoId; $scope.cursosUnidade[cursoId] = curso; }
            }
        };
        
        //INICIA MAPA
        $scope.initMapa = function (lat,lng){
            $timeout(function(){
                if (Util.isVazio(lat) || Util.isVazio(lng)) { lat = -26.930232; lng = -48.684180; }
                $scope.mapa = L.map('mapa').setView([lat,lng],16); $scope.mapa.scrollWheelZoom.disable();
                L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                    attribution: 'Erudio Map by &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                    maxZoom: 24, id: 'mapbox.streets',
                    accessToken: 'pk.eyJ1IjoiY3Jpc3RpYW5vc2llYmVydCIsImEiOiJjajRlaXlseDQwNDNhMndydjNqYzlqOWtyIn0.MQqg-LSABfmy1v8-EIpBGg'
                }).addTo($scope.mapa);
                $scope.marker = L.marker([lat, lng]).addTo($scope.mapa);
                $scope.mapa.on('click', $scope.adicionaMarcador);
            },500);
        };
        
        //ADICIONA MARCADOR NA TELA
        $scope.adicionaMarcador = function (event) {
            $scope.mapa.removeLayer($scope.marker);
            $scope.marker = L.marker([event.latlng.lat, event.latlng.lng]).addTo($scope.mapa);
            $scope.marker.bindPopup('<div class="map-popup-content"><strong>Este é o endereço desejado?</strong><br /><br /><button id="btnMapaSim" class="material-btn">Sim</md-button><button class="material-btn">Não</md-button></div>').openPopup();
            $("#btnMapaSim").on('click',function(ev){ ev.preventDefault(); $scope.confirmaLatLng(event); });
        };
        
        //CONFIRMA LAT LNG
        $scope.confirmaLatLng = function (event) {
            var lt = parseFloat(event.latlng.lat).toFixed(6); var ln = parseFloat(event.latlng.lng).toFixed(6);
            $scope.instituicao.endereco.latitude = parseFloat(lt); $scope.instituicao.endereco.longitude = parseFloat(ln); $scope.mapa.closePopup();
        };
        
        //PREENCHE CEP
        $scope.preencheCEP = function(){ console.log('ha'); $timeout(function(){ $("input[name=cep]").change(function(){ $scope.buscaCEP(); }); },500); };
        
        //BUSCA CEP
        $scope.buscaCEP = function () {
            /*if (!Util.isVazio($scope.instituicao.endereco.cep) && $scope.instituicao.endereco.cep.length === 8) {
                $.getJSON("https://viacep.com.br/ws/"+$scope.instituicao.endereco.cep+"/json/?callback=?", function(dados) {
                    console.log(dados);
                    if (!Util.isVazio(dados.logradouro)) { $scope.instituicao.endereco.logradouro = dados.logradouro; }
                    if (!Util.isVazio(dados.bairro)) { $scope.instituicao.endereco.bairro = dados.bairro; }
                    if (!Util.isVazio(dados.uf)) { 
                        var promise = Util.buscar('estados',{sigla: dados.uf});
                        promise.then(function(response){ $scope.instituicao.endereco.cidade.estado = response.data;  $scope.buscarCidades(); });
                    }
                    
                });
            }*/
        };
        
        //INICIANDO
        $scope.form = Util.getTemplateForm(); Util.inicializar(); $scope.buscarUnidade();
        Util.mudarImagemToolbar('unidades/assets/images/unidades.jpg');
    }]);
})();