# API - Academia SysTrain
O Projeto Academia SysTrain consiste em uma API para gestão de alunos de uma academia, que permite o cadastro de usuários do sistema, alunos, treinos e exercicio, no projeto também é possivel enviar email de boas vindas para os novos usuários e gerar PDF de treinos da semana dos alunos.

## 🔧 Tecnologias utilizadas

Projeto foi desenvolvido utilizando a linguagem PHP com framework Laravel. O banco de dados foi hospedado através do Docker utilizando PostgreSQL com DBeaver. 

### Vídeo de apresentação: 
(https://drive.google.com/drive/folders/1Sf6MqVekAAVpdbJFeABDflO6NM8Ajzmg?usp=drive_link)

Seguem abaixo as depêndencias externas utilizadas:
| Plugin | Uso |
| ------ | ------ |
| DOMPDF | Dompdf é um conversor de HTML para PDF, utilizado para gerar pdf dos treinos dos alunos |

### Modelagem da base de dados PostgreSQL

Modelo extraido do app DBeaver.

![modelagem de dados api](https://github.com/diegobmorais/Projeto-M2-PHP/assets/128264029/b8ec5faf-cec3-4de5-8703-7f863bddfa69)


## 🚀 Como executar o projeto

-Clonar o repositório https://github.com/diegobmorais/Projeto-M2-PHP

-Criar uma base de dados no PostgreSQL com nome **academia_api**

-Instalar Docker Desktop e executar o comando no powershell como admin

```sh
 docker run --name academia_api -e POSTGRESQL_USERNAME=docker -e POSTGRESQL_PASSWORD=docker -e POSTGRESQL_DATABASE=academia_api -p 5432:5432 bitnami/postgresql
``` 

-Criar um arquivo .env na raiz do projeto com os seguintes parametros configurados para acessar o banco de dados:
```
DIALECT_DATABASE=''
HOST_DATABASE=''
USER_DATABASE=''
PASSWORD_DATABASE=''
PORT_DATABASE=''
PORT_API=''
NAME_DATABASE=''
```

-No prompt de comando executar :
```sh
composer install 
```
-Executar em seguida:
```sh
php artisan serve
```

### 🚥 Endpoints - Rotas da api


| Parâmetro (Rotas publicas)  |  Descrição                           |
| :---------- |:---------------------------------- |
| `/users`  (post)    | rota para criação do usuário que irá utilizar o sistema |
| `/login` (post) | Rota para se autenticar no sistema|
| Parâmetro (Rotas privadas)  |  Descrição                           |
| `/dashboard` (get) | Lista o plano do usuário, quantidade de alunos e exercicios cadastrador |
| `/exercises'` (post) | Cadastro de exercicios no sistema|
| `/exercises'` (post)` | Lista todos os exercicios cadastrados|
| `/exercises/{id}`(delete) | Deleta exercicio pelo parâmetro id|
| `/student` (post) | Cadastro de alunos|
| `/student` (get) | Lista todos os alunos cadastrados pelo usuario logado|
| `/student/{id}` (delete) | Deleta aluno pelo id|
| `/student/{id}` (put) | Atualiza cadastro do estudante |
| `/student/{id}` (get) | Lista um estudante |
| `/students/{id}/workouts` (get) | Lista treino do estudante |
| `/students/export` (get) | exporta treino em pdf para o estudante|
| `/workouts` (post) | Cadastra um treino para o estudante |

## Observações 

-Para que possa utilizar o recurso de envio de email de boas vindas para os novos usuarios, 
deve-se configurar as crendenciais no .env, no projeto foi utilizado o trapmail para testes.
(as configurações abaixo são meramentes ilustrativas)

```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=edbd5c32e3ef84
MAIL_PASSWORD=c368ebf9175414
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```
