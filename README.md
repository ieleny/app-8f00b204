# app-8f00b204e9800998ecf8427e

Objetivo:
    Criar um controle de estoque

Requisitos:

Back-end:
> php --version: ^7.3|^8.0

> Laravel --version: Laravel v8.63.0

Banco de dados:
> Mysql --version: 5.7

Executar o projeto:

Todos os comandos a seguir, serão executados na pasta 'appmax'

> Executar o composer:
> composer install

> Executar as migration:
    > php artisan db:create
    > php artisan migrate

> Acessar a pasta appmax, e executar o comando abaixo:
    > php artisan serve

Verbos: 
> Produtos:
    > http://127.0.0.1:8000/api/v1/products/list
