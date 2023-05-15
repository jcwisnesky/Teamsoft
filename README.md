# Teamsoft
Este é o repositório da aplicação Teamsoft, uma API desenvolvida com Laravel.

## Requisitos
- PHP 7.4 ou superior
- Composer
- Banco de dados MySQL
- Extensões PHP necessárias (verificar o arquivo composer.json para mais detalhes)

## Instalação

1. Clone o repositório para o seu ambiente local:
git clone https://github.com/jcwisnesky/Teamsoft.git

2. Acesse o diretório do projeto:
cd Teamsoft

3. Instale as dependências do Composer:
composer install


4. Copie o arquivo `.env.example` e renomeie-o para `.env`. Configure as variáveis de ambiente do seu banco de dados e outras configurações relevantes no arquivo `.env`.

5. Execute as migrações do banco de dados:
php artisan migrate


6. Inicie o servidor de desenvolvimento:
php artisan serve


7. Acesse a API em `http://localhost:8000` (ou outra porta, dependendo da configuração do servidor).

## Uso

A API oferece os seguintes endpoints:

- `GET /clientes`: Retorna todos os clientes cadastrados.
- `GET /clientes/{id}`: Retorna os detalhes de um cliente específico.
- `POST /clientes`: Cria um novo cliente e um novo endereço.
- `PUT /clientes/{id}`: Atualiza um cliente existente.
- `DELETE /clientes/{id}`: Remove um cliente.

- `GET /enderecos`: Retorna todos os endereços cadastrados.
- `GET /enderecos/{id}`: Retorna os detalhes de um endereço específico.
- `PUT /enderecos/{id}`: Atualiza um endereço existente.
- `DELETE /enderecos/{id}`: Remove um endereço.

Certifique-se de enviar as requisições adequadas com os dados corretos para cada endpoint.




