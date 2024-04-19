## Projeto: CRUD API de Produtos em Laravel

Sobre o projeto
=======================

Este projeto consiste em uma API para gerenciamento de produtos desenvolvida utilizando o framework Laravel e MySQL.
A API permite a realização das operações básicas de CRUD (Create, Read, Update, Delete) para manipular os dados dos produtos.

Funcionalidades Principais:
==========================
* **CRUD de Produtos:** A API permite a criação, leitura, atualização e exclusão de produtos.
* **Filtragem Avançada:** Utilizando a biblioteca `tucker-eric/eloquentfilter`, é possível aplicar filtros avançados nos resultados das consultas, facilitando a busca por produtos com base em diversos critérios.
* **Organização Modular:** O projeto faz uso da biblioteca `nwidart/laravel-modules` para organizar o código em módulos, facilitando a manutenção e escalabilidade do sistema.

Requisitos
============
* PHP >= 8.2
* Composer
* Docker

Installation
============
Passo a passo para instalar e executar esse projeto.

**1.** Clone o projeto `git clone https://github.com/WellingtonMaia/crud-test-bevi.git`

**2.** Executar esses dois comandos
`cd crud-test-bevi` e `cp .env.example .env`

**3.** Configurar o banco de dados no arquivo `.env` com essas informações:

    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=bevi_database
    DB_USERNAME=sail
    DB_PASSWORD=password

**4.** Executar esse comando para intalar as dependências do projeto. Este comando usa um pequeno contêiner Docker contendo PHP e Composer para instalar as dependências.

    docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

**5.** Executar esse comando para iniciar todos os container Docker em segundo plano.

    sail up -d

**6.** Executar esse comando para gerar uma chave na variável de ambiente `APP_KEY` no arquivo `.env`.

        sail artisan key:generate

**7.** Executar esse comando para criar as tabelas no banco de dados.

    sail artisan migrate

**8.** Por último, executar os testes com esse comando:

    sail artisan test --filter=product

**Obs.:** Nesse projeto tem um arquivo `TEST_API_BEVI.postman_collection.json` para importar no **Postman** e realizar testes manualmente.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
