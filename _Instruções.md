#Criando um projeto Laravel:
  - Utilize o composer para fazer a criação do projeto:

        composer.phar create-project --prefer-dist laravel/laravel <nome-do-projeto>

        ou ainda

        laravel new <nome-do-projeto>

  - Caso ainda não exista no arquivo composer.json um script de postUpdate, deve ser gerada a chave para o projeto a ser armazenada em APP_KEY no arquivo .env:

        php artisan key:generate
        php artisan optimize

  - Complete a etapa básica de instalação instalando os pacotes do nodejs que são necessários para o funcionamento do ambiente:

        npm install

     ***Sempre lembrar de baixar o JQuery que pode ser instalado através de:***

         npm install jquery --save

         No exemplo acima mostra esta forma de utilização, pois seus arquivos estão na pasta 'node_modules', mas igualmente pode ser feito o download dos arquivos e colocados na pasta 'resources/assets/JQuery', por exemplo.

     *** No caso do MDBootstrap ou do Materialize, a pasta 'fonts' deve ser copiada para 'public/fonts'. Também para estes casos, já existe na pasta 'js' o JQuery homologado, então de preferência por ele para o 'combine'
     *** Para o MDBootstrap é necessário o 'popper.js', que só vem no pacote em sua versão 'min'. Sendo assim execute:

         npm install popper.js --save

     *** Lembrando que estes arquivos gerados devem ter como destino as pastas em public/js, public/css
     *** Os arquivos a serem utilizados de frameworks visuais como o MDBootstrap, Materialize e assim por diante devem ser colocados na pasta resources/assets, criando uma pasta para comportar seus arquivos. Para o MDBootstrap a sugestão é uma pasta 'MDB-Pro'


  - Depois coloque no arquivo webpack.mix.js as informações para que o Mix execute a combinação de arquivos CSS, js, como exemplo que se segue:

        mix = require('laravel-mix');

        /*
         |--------------------------------------------------------------------------
         | Mix Asset Management
         |--------------------------------------------------------------------------
         |
         | Mix provides a clean, fluent API for defining some Webpack build steps
         | for your Laravel application. By default, we are compiling the Sass
         | file for the application as well as bundling up all the JS files.
         |
         */

        mix.combine
        ([
          'node_modules/jquery/dist/jquery.js'                   ,
          'resources/assets/MDB-Pro/js/popper.js'                ,
          'resources/assets/MDB-Pro/js/modules/hammer.js'        ,
          'resources/assets/MDB-Pro/js/modules/jquery.hammer.js' ,
          'resources/assets/MDB-Pro/js/mdb.js'
         ], 'public/js/app.js')
        .sass('resources/assets/sass/app.scss', 'public/css')
        .sass('resources/assets/MDB-Pro/sass/mdb.scss','public/scss');;

        // CSS
        mix.combine
        (['arquivocss1','arquivocss2','arquivocss3','arquivocssn'],
        'arquivo-css-destino');

  - Para habilitar o uso do Laravel Mix, existe dentro do package.json, scripts para a execução destas tarefas:

        // Run all Mix tasks...
        npm run dev

        // Run all Mix tasks and minify output...
        npm run production  - Caso venha a usar o precesso de autenticação do Laravel, deve-se executar o seguinte:


Habilitar o plugin do Laravel no PHPStorm em:

        File -> Settings -> Plugins

   - Caso o mesmo não esteja habilitado ou instalado, deve-se fazê-lo para que funcione a instalação dos Helpers que vem a seguir, que são extremamente úteis no desenvolvimento.

Para instalar o Helper para o Laravel:
  - Instalar o Helper via composer:

        composer.phar require barryvdh/laravel-ide-helper

  - Instalar o doctrine/dbal que depois será necessário  para que se possa fazer alterações nas colunas de tabelas no banco de dados:

        composer.phar require doctrine/dbal

  - Incluir no arquivo config/app.php o provider para o help funcionar:
          /*
          IDE Helper Providers
          */
          Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,

  - Incluir no arquivo composer.json na sessão "scripts": os comandos para que sempre ao final de
    um install ou update, que sejam executados os comandos que estão a seguir:

        "post-update-cmd": [
            "Illuminate\\Foundation\\ComposerScripts::postUpdate",
            "php artisan ide-helper:generate",
            "php artisan ide-helper:meta",
            "php artisan optimize"
        ]

  - Na primeira vez, executar na mão:

            php artisan ide-helper:generate
            php artisan ide-helper:meta
            php artisan optimize

  - Existe também um processo para que as colunas do banco também fiquem
    disponíveis pelo Helper, mas vou estudar isto depois. Isto é acionado por:

            php artisan ide-helper:model

            Importante!!! Usar este comando sempre que o banco for modificado e com o mesmo configurado

  *** Após estes passos, não esqueça de verificar se o Laravel Plugin está habilitado para este projeto. Verifique isto em:

            File -> Settings -> Languages & Frameworks -> Laravel -> Enable Plugin for this project

    Usando o UUID para todos os models no Laravel
  - Instalar um gerador de UUID. O melhor para o Laravel foi este que encontrei:

        composer.phar require webpatser/laravel-uuid

  - Utilizar um 'trait' para que toda a vez que um model seja criado, possamos
    implementar uma coluna UUID:

    Criar uma pasta 'App/Traits' e criar um arquivo GerarUuid.php com:

    <?php
    namespace App\Traits;

    use Webpatser\Uuid\Uuid;

    trait GerarUuid
    {

      /**
       * Boot function from laravel.
       */
      protected static function boot()
      {
        parent::boot();

        static::creating(function ($model) {
          $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
      }
    }

    Atenção!!! Para quando tivermos tabelas que tenham colunas auto-increment,
    de alguma forma precisaremos testar quando isto deverá ser acionado ou
    não. Ver isto depois e documentar aqui!

  - Nos modelos que forem criados sempre colocar:

        <?php

        namespace App;

        use App\Traits\GerarUuid;
        use Illuminate\Database\Eloquent\Model;


        class <modelo> extends Model
        {
          use GerarUuid; // O trait deve está sempre dentro da Class que fará uso dele...

          protected $table = '<tabela>';
          protected $primaryKey = '<coluna-pk>';
          protected $fillable = [
            <lista das colunas atualizáveis>
          ];
          public $incrementing = false; // Somente quando a tabela usar UUID...

        
  - Visualizar o que será aplicado no banco (DDL) antes de rodar a migração:
        
          Isto pode ser feito através de
        
            php artisan migrate --pretend
            
        Para gerar a saída em um arquivo:
        
            php artisan migrate --pretend --no-ansi > <nome-do-arquivo-de-saída>
            
  - Como configurar os relacionamentos dentro dos modelos em Many-to-Many:
  
        Fonte: http://laraveldaily.com/pivot-tables-and-many-to-many-relationships/
        
        
            $this->belongsToMany('<nome-do-modelo-relacionado','<nome-tabela-relacionamento>, '<coluna-para-este-modelo>','<coluna-para-modelo-relacionado>');
            
              <coluna-para-este-modelo>: Qual nome da coluna na tabela de relacionamento que é chave para a modelo em questão
              <coluna-para-modelo-relacionado>: Qual o nome da coluna na tabela de relacionamento que aponta para a tabela relacionada
              
  - Configurando a ambiente multitenant

        Deve existir um schema para armazenar as informações básicas da gestão do ambiente multi-tenant e para tanto é
        necessário um schema e um usuário com permissões amplas para poder criar os novos 'schemas' para cada tenant a ser
        criado:

            CREATE DATABASE IF NOT EXISTS <schema-gerente> character set utf8 default collate utf8_general_ci;
            CREATE USER IF NOT EXISTS <usuário>@localhost IDENTIFIED BY '<senha-usuário>';
            GRANT ALL PRIVILEGES ON *.* TO <usuário>@localhost WITH GRANT OPTION;

        Criar uma conexão 'system' para poder comportar e permitir a instalação do ambiente multi-tenant.
        Para tanto, alterar o arquivo config/database.php e incluir:

            'system' => [
                'driver' => 'mysql',
                'username' => '<usuário>',
                'database' => '<schema-gerente>',
                'password' => '<senha-usuário>',
                // Copiar as demais parametrizações do driver do 'mysql'
            ],
              'tenant' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => 'abe8bcb199cb4a53bd0132741bf1152c',
                'username' => 'projetobase',
                'password' => 'projetobase'
                // Copiar as demais parametrizações do driver do 'mysql'
                ]

        Instalar hyn/laravel-tenancy:

            composer.phar require hyn/multi-tenant:5.*
              
        Publicar o pacote para o projeto:

            php artisan vendor:publish --tag=tenancy

        O passo seguinte deve ser sempre realizado em uma nova instalação e se você copiou o novo projeto deste projeto
        base deve antes remover o arquivo 'tenacy.json' da raiz do projeto e executar:

        IMPORTANTE!!! Crie uma nova pasta em /database como /tenant/migrations. Estas
        pastas determinarão que estas migrações só são relativas ao ambiente 'tenant' criado e não ao ambiente gestor:

            php artisan tenancy:install

        Como o MySql não aceita nomes de schemas e de users com tamanho maior do que 32 bytes, deve-se acrescentar a
        seguinte opção no arquivo '.env':


            LIMIT_UUID_LENGTH_32=true

        A criação de websites e hostnames do ambiente muti-tenant é feita através de comandos artisan customizados que
        estão em dois arquivos:

            app/Console/Commands/laravelTenancy.php
            app/Console/Commands/laravelTenancyList.php

        Criar primeiramente o 'website':

            php artisan laravel-tenancy:create website

        Será apresentada a seguinte mensagem:

            Creating Website
            Website created with UUID=<uuid-gerado-para-website>

        Guarde o UUID porque ele será utilizado para associar o website ao hostname:

            php artisan laravel-tenancy:create hostname --fqdn=<fqdn-do-site> --uuid=<uuid-do-website>

        Criado no arquivo 'filesystem.php' um novo disco para o tenant. Ainda não sei bem como isto funciona, mas ficou
        temporariamente parametrizado assim:

            Em filesystems.php:

                'tenancy-disk' => [
                  'driver' => 'local',
                  'root' => storage_path('app/tenancy'),
                ],

            Em 'webservers.php' onde estava 'disk' => null foi trocado por 'disk' => 'tenancy-disk'

            Em 'tenancy.php' também foi alterado em 'website' > 'disk' => null foi trocado por 'disk' => 'tenancy-disk'

        PENDÊNCIA!!!! Entender o funcionamento dos 'seeds'. Fiz um sedder usando diretamente a classe DatabaseSeeder,
        mas não funcionou via call. Também tem uma parametrização em 'tenancy.php' para indicar uma classe que faz a carga
        toda vez que um website é criado. Bem importante isto para fazer a carga inicial de um novo tenant. Ainda é necessário
        entender isto direito como funciona.

###Instalar e configurar  o Laratrust 
Para gerenciamento de segurança:

   - http://laratrust.readthedocs.io/en/5.0/installation.html

   - Instalar via composer:

        composer.phar require "santigarcor/laratrust:5.0.*"

###Validações brasileiras:


    Para ajudar nas diversas validações brasileiras como CPF, CNPJ, CEP, placada de veículos, estamos usando
    o pacote laravellegengs/pt-br-validator. Instalação:

        composer.phar laravellegents/pt-br/validator

        ou adicione diretamente no arquivo do composer.json as seguintes linhas:

        {
            "laravellegends/pt-br-validator" : "5.1.*"
        }

        e depois execute um composer.phar update --no-scripts

    Veja maiores detalhes sobre como utilizar este pacote em https://packagist.org/packages/laravellegends/pt-br-validator

Tratamento de Imagens:
======================

    Para o tratamento de imagem utilizaremos dosi pacotes:

        * intervention/image
        * intervention/imagecache

    O primeiro permite diversos tratamentos de imagem para efetur resize(), crop(), brightness(), blur() entre outros tratamentos.
    Ja o imagecache permite que efetuemos o tratamento das imagens em cache, sem precisarmos armazenar os arquivos de forma
    temporária e é extremente adaptado ao Laravel.

    Instalação:

        composer.phar require intervention/image
        composer.phar require intervention/imagecache

    Após instalar executar:

        $ php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5"


    **** Colocar aqui um exemplo completo de uso, inclusive com o uso do Multi-tenant ***



Executar as migrações iniciais, que vão criar as tabelas de Usuários, Pessoas e as tabelas de ACL do Laratrust:

        php artisan tenancy:migrate

    Este comando realizará a migração em 'todos' os tenants existentes.
    Pode ser informado um website específico para migrar através da opção --website_id. Veja que se trata do id do WebSite
    e não o seu UUID. Para maiores detalhes:

        php artisan tenancy:migrate --help

Executar a carga inicial dos dados através dos seeds:

        php artisan tenancy:db:seed

    De igual forma, este comando realizarará a carga dos dados em 'todos' os tenants existentes.
    Pode ser informado um website específico para a realização do 'seed' através da opção --website_id. Veja que se trata do id do WebSite
    e não o seu UUID. Para maiores detalhes:

        php artisan tenancy:db:seed --help


Criando um Controller completo, já com os métodos para o CRUD:

  - Usar o seguinte comando do artisan:

        php artisan make:controller ClienteController --resource --model=<nome-model>

Para os helpers estamos usando um pacote browner12/helpers. Para maiores detalhes e instruções de instalação,
veja em: https://github.com/browner12/helpers

### Instalando o PHPBrew:

Esta ferramenta facilita muito o trabalho no desenvolvimento com PHP porque permite gerenciar diversas versões do PHP e também alterar de forma simples entre elas.

Veja os passos para a sua instalação em:

        https://github.com/phpbrew/phpbrew

Importante observar os pré-requisitos para a instalação do PHPBrew:

        https://github.com/phpbrew/phpbrew/wiki/Requirement

Observe a lista dos pacotes que devem estar instalados e com a versão do Ubuntu 18.04 ocorreu um erro com o 'curl', onde
se reclamava da versão utilizada. Isto foi resolvido com as seguintes instalações:

        sudo apt-get install libssl-dev libcurl4-openssl-dev pkg-config
   
Após isto, pode ser executada a geração do PHP pelo PHPBrew:

        phpbrew install 7.x.x +default +pdo+mysql +intl
        
Para a instalação de extensões do PHP deve ser usado:

        phpbrew ext install <nomes-da-extensões>
        
Maiores detalhes podem ser obtidos na página do PHPBrew já citada.