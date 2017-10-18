# Atualização

Atualmente o Erudio está passando por uma reestruturação de seu front-end e finalização de módulos pendentes, buscamos em breve (segundo semestre de 2017) anunciar e disponibilizar a primeira versão considerada estável. Durante este tempo, não recomendamos que o mesmo seja utilizado em produção, apenas para fins de teste das funcionalidades (a nova interface manterá as funcionalidades existentes, mesmo que hajam mudanças na usabilidade). Também não poderemos prestar suporte de qualquer tipo neste momento. Caso tenha interesse de colaborar com o projeto, seja revisando ou estendendo seu código-fonte, ou produzindo manuais, por favor entre em contato pelo e-mail jhony@itajai.sc.gov.br.

# Erudio

Erudio é um sistema de gestão escolar desenvolvido pela Secretaria Municipal de Educação de Itajaí-SC, disponibilizado desde 2016 como Software Livre, que visa oferecer a flexibilidade necessária para comportar cursos com diferentes estruturas e sistemas de avaliação, como o Ensino Infantil, Ensino Fundamental (Anos Iniciais e Finais) e Educação de Jovens e Adultos. Um dos principais objetivos do Erudio é também integrar gestores, professores, pais e alunos em um só sistema, oferecendo serviços específicos para cada tipo de usuário.

# Requisitos

O Erudio requer:
* Servidor web que suporte PHP (Apache, Nginx ou outro)
* PHP 5.5 ou superior
* Banco de dados: MySQL ou PostgreSQL (outros não foram testados)
Sua interface web é testada sempre nos navegadores Firefox e Chrome em suas versões mais recentes. Internet Explorer não é suportado.

# Instalação

1. **Faça o download do software e suas dependências** <br />
Faça o download dos arquivos do sistema antes de prosseguir.<br />
A versão atual deve ser baixada diretamente deste repositório, que contém tanto o backend (erudio-server), implementado em PHP/Symfony 3, quanto o frontend (erudio-front), escrito em AngularJS. Ambos devem ser colocados na pasta do servidor web.

  * *Instalação das dependências PHP via Composer*<br />
  Faça o download do *Composer* no diretório */caminho-da-raiz-web/erudio-server*<br />
  Acesse [http://getcomposer.org/](http://getcomposer.org/) para instruções sobre a instalação do *Composer*<br />
  Após fazer o download do *Composer* o arquivo **composer.phar** será criado no diretório */caminho-da-raiz-web/erudio/servicos*<br />
  Execute o instalador do *Composer*:<br />
  `$ cd /var/www/erudio/servicos`<br />
  `$ php composer.phar install`<br /><br />

2. **Configure a comunicação do frontend com o backend** <br />

  Configure a conexão do frontend com o servidor backend, que fica no diretório */caminho-da-raiz-web/erudio-server* contém o servidor de serviços.<br />
  As configurações de conexão do front-end com servidor de serviços estão armazenadas em dois arquivos:<br />
  `$ /caminho-da-raiz-web/erudio-front/modules/main/app/app.js`<br />
  `$ /caminho-da-raiz-web/erudio-front/modules/login/app/loginApp.js`<br /><br />
  
  Basta editar estes arquivos para que corresponda ao IP ou URL do seu servidor.<br />
	**Observação:** O *IP* ou *URL* do servidor a ser configurado deve ser acessível pelos clientes.<br />
	A configuração deve ser feita nesta linha:<br />
	`RestangularProCrie o banco de dados ao qual o Erudio usará para armazenar os dados
   inseridos através da interface web. Os passos descritos nessa seção irão
   criar:vider.setBaseUrl('http://meu-endereco-teste.br/erudio-server/web/app.php/api');`<br /><br />
	
	Para o serviço de mapas funcionar corretamente uma chave do *Google Maps API* deve ser criada acessando o
	website [https://developers.google.com/maps/documentation/javascript/get-api-key](https://developers.google.com/maps/documentation/javascript/get-api-key)<br />
	Esta chave deve ser inserida no arquivo **index.html** que está localizado em **/caminho-da-raiz-web/erudio/index.html**<br />
	Edite a linha que se refere ao Google Maps API atribuindo a sua chave ao valor da variável key.<br />
	`<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?v=3.exp&key=INSIRA_SUA_CHAVE_AQUI"></script>`<br /><br />
	
3. **Crie o Banco de Dados** <br />
	Execute o script SQL erudio.sql, localizado no diretório misc/database, em seu servidor MySQL ou PostgreSQL. O script irá criar o banco de dados "erudio" e todas as devidas tabelas.<br />

4. **Configure o servidor e ajuste as permissões do diretório de cache e logs**<br />
	O servidor do Erudio armazena as configurações necessárias para a aplicação no arquivo erudio-server/app/parameters.yml.<br />
	Altere as seguintes configurações deste arquivo para conectar ao seu banco de dados:<br />
	
	<br />
		`database_host: localhost ### Alterar para host do banco`<br />
		`database_port: 3306`<br />
		`database_name: erudio ### Alterar para nome da base de dados criada`<br />
		`database_user: erudio ### Alterar para seu usuário do banco`<br />
		`database_password: erudio ### Alterar para a senha do usuário`<br />
	<br />
	
	Depois, conceda permissão de escrita ao servidor web para o diretório *erudio-server/var*.<br />

5. **Acesse o Erudio em seu navegador**
	Abra o navegador de sua preferência e acesse o endereço *http://SEU_SERVIDOR/erudio*.<br />
	Faça o login na aplicação utilizando o usuário *administrador*.<br />
   	O login e senha para acesso são admin e admin, respectivamente.<br />

# Documentação
	O modelo entidade-relacionamento encontra-se neste pacote na pasta *misc/documentação*.
	**ATENÇÃO: documentação encontra-se desatualizada no momento**

# Licença
	O Erudio é um software livre e licenciado pela Creative Commons Licença Pública Geral versão 2 traduzida (CC GNU/GPL 2). Uma cópia da licença está incluida nesta distribuição no arquivo LICENSE-pt_BR.txt.
