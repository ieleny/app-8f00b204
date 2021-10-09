# app-8f00b204e9800998ecf8427e

# Objetivo:
    Criar um controle de estoque

# Requisitos:

## Back-end:
> php --version: ^7.3|^8.0

> Laravel --version: Laravel v8.63.0

## Banco de dados:
### Obs: Para colocar os dados do seu banco local, é preciso criar um arquivo '.env' copiando do exemplo que tem no projeto '.env.example'

> Mysql --version: 5.7
> Nome da base: appmax_challenge

## Executar o projeto:

### Todos os comandos a seguir, serão executados na pasta 'appmax'

> Executar o composer:
> composer install

> Executar as migration:
> php artisan db:create
> php artisan migrate

> Rodar a aplicação:
> php artisan serve

## Verbos: 

### Produtos:

    Tipo: GET
    Objetivo: Listar o todos os produtos
    Url: http://127.0.0.1:8000/api/v1/products/list
    
    Tipo: GET
    Objetivo: Listar o produto especifico
    Url: http://127.0.0.1:8000/api/v1/products/1
    
    Tipo: GET
    Objetivo: Criar o produto
    Url: http://127.0.0.1:8000/api/v1/products/create
    Parametros: 
        - name
        - quantity

### Movimentação dos produtos:

    Tipo: GET
    Objetivo: Listar todas as movimentações
    Url: http://127.0.0.1:8000/api/v1/productsMovement/list
    
    Tipo: GET
    Objetivo: Listar todas as movimentações
    Url: http://127.0.0.1:8000/api/v1/productsMovement/1
    
    Tipo: PUT
    Objetivo: Inserir e atualizar as movimentações
    Url: http://127.0.0.1:8000/api/v1/productsMovement/1
    Parametros: 
        - name
        - quantity
        - type_movimentacion (A = ADICIONAR OU R= REMOVER)
    
### Historico(Logs):
    Tipo: GET
    Objetivo: Listar o todos os historicos
    Url: http://127.0.0.1:8000/api/v1/productsMovement/list
    
    Tipo: GET
    Objetivo: Listar o historicos especifico
    Url: http://127.0.0.1:8000/api/v1/productsMovement/1
    
    
