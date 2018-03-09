#Erudio

## Descrição

Erudio é um sistema de gestão escolar desenvolvido pela Secretaria Municipal de Educação de Itajaí-SC, disponibilizado desde 2016 como Software Livre, que visa oferecer a flexibilidade necessária para comportar cursos com diferentes estruturas e sistemas de avaliação, como o Ensino Infantil, Ensino Fundamental (Anos Iniciais e Finais) e Educação de Jovens e Adultos. Um dos principais objetivos do Erudio é também integrar gestores, professores, pais e alunos em um só sistema, oferecendo serviços específicos para cada tipo de usuário.

## Requisitos

A instalação do Erudio necessita das seguintes tecnologias:

* Servidor web que suporte PHP como Apache ou Nginx
* PHP 7.0 ou superior
* Banco de dados: MySQL / MariaDB (adaptável para outros)

Sua interface web é testada sempre nos navegadores Firefox e Chrome/Chromium, em suas versões mais recentes. Deve ser compatível também com as versões mais recentes do Safari e Edge. Internet Explorer não é suportado.

## Instalação

#### 1. Download do software
 
Clone este repositório na raiz do seu servidor usando git clone, ou baixe-o como uma pasta zipada e descompacte no mesmo local. A página inicial do site encontra-se no caminho /erudio/erudio-front, mas a estrutura pode ser modificada para gerar uma url mais amigável, colocando o conteúdo da pasta erudio-front diretamente na raiz do servidor, por exemplo.

#### 2. Instalar dependências do servidor

O servidor REST do Erudio é implementado na linguagem PHP, e suas dependências são gerenciadas com a ferramenta Composer. Para instalar todas as dependências, basta fazer o download do arquivo composer.phar em  [http://getcomposer.org/](http://getcomposer.org/), movê-lo para pasta erudio-server, e executar o comando:

  	$ php composer.phar install


#### 3. Configuração do servidor

O arquivo parameters.yml.dist, localizado na pasta /app/config em erudio-server, deve ser renomeado para apenas parameters.yml. Em seguida, edite seu conteúdo, substituindo os valores da configuração de acordo com seu ambiente. O mais importantes são os dados de conexão com o banco:

	database_host: [host do banco de dados]
	database_port: [porta da conexão]
 	database_name: [nome da base de dados]
	database_user: [usuário]
 	database_password: [senha]
 
 O Erudio usa o protocolo JWT para autenticação do usuário no servidor, portanto devem ser geradas duas chaves, uma pública e uma privada, na pasta /var/jwt (crie a pasta se ela não existir). Os seguintes comandos podem ser usados:
 
	$ openssl genrsa -out var/jwt/private.pem -aes256 4096
	$ openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem

Por último, conceda permissão de leitura e escrita ao servidor web para todo o diretório erudio-server/var.

#### 4. Criar o Banco de Dados

Primeiramente crie a base de dados erudio usando seu gerenciador de banco de dados. Para gerar as tabelas do sistema, existem duas opções:

* Utilizar o comando migrate da ferramenta Doctrine Migrations, para gerar automaticamente as tabelas. Consulte o [site](https://symfony.com/doc/master/bundles/DoctrineMigrationsBundle/index.html) para mais informações.

* Executar o script estrutura.sql contido na pasta db, na raiz do projeto.

Para inserir os dados iniciais, como usuário padrão, cidades, estados, etc, apenas execute o script dados-iniciais.sql após gerar as tabelas.

#### 5. Configurar o caminho do servidor

O arquivo erudioConfig.js.dist, localizado na pasta /app/modules/main/services, do frontend, deve ser renomeado para apenas erudioConfig.js. Em seguida, edite seu conteúdo, substituindo o valor da variável dominio para o endereço da raiz do erudio em seu servidor.

Exemplos:

	this.dominio = 'https://erudio.meumunicipio.meuestado.gov.br';

ou

	this.dominio = 'https://meumunicipio.meuestado.gov.br/erudio';

Dependendo das configurações do seu servidor, as variáveis urlServidor e urlRelatorios também deverão ser ajustadas, para apontar para o arquivo app.php que fica em /erudio-server/web.

#### 6. Acesse o Erudio em seu navegador

Abra o navegador e acesse a url do seu servidor. Faça o login na aplicação utilizando o usuário *admin* e senha *admin*. Recomenda-se alterar a senha deste usuário por segurnaça.

## Documentação

A documentação do software, incluindo manuais do usuário, será disponibilizada em breve, ainda no início de 2018.

## Licença

O Erudio é um software livre e licenciado pela Creative Commons Licença Pública Geral versão 2 traduzida (CC GNU/GPL 2). Uma cópia da licença está incluida nesta distribuição no arquivo LICENSE-pt_BR.txt.
