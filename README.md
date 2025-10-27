# Projeto Laravel com Docker

Este é um projeto Laravel configurado para rodar em um ambiente Docker. Este guia irá ajudá-lo a configurar e iniciar o projeto rapidamente.

## Requisitos

-   Docker
-   Docker Compose

## Passos para Instalação

1. Clone este repositório para o seu ambiente local:
    ```bash
    git clone https://github.com/LucasVSCS/users-addresses.git
    cd users-addresses
    ```
2. Copie o arquivo de ambiente de exemplo para criar o seu próprio arquivo `.env`:
    ```bash
    cp .env.example .env
    ```
3. Inicie os contêineres Docker usando o Docker Compose:
    ```bash
    docker-compose up -d
    ```
4. Execute as migrações do banco de dados:
    ```bash
    php artisan migrate
    ```
5. (Opcional) Seed o banco de dados com dados iniciais:
    ```bash
    php artisan db:seed
    ```

## Acessando a Aplicação

Após iniciar os contêineres, você pode acessar a aplicação Laravel no seu navegador através do seguinte endereço:

```
http://localhost:9000
```
