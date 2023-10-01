# User Manager

## Como Rodar o Projeto
### Clonando o projeto
`git clone https://github.com/reichert-lucas/user-manage.git`

### Copiando os arquivos de ambiente de exemplo
`cp back/.env.example back/.env && cp front/.env.example front/.env.development && cp front/.env.example front/.env.production`

### Rodando o ambiente docker
`docker-compose up`

### Populando a base de dados
`docker exec -it user-manager-front php artisan migrate:fresh --seed`

### Rodando alguns testes
`docker exec -it user-manager-front php artisan test`

### Acessando o projeto
O projeto vai estar disponível nos seguintes endereços: `https://localhost:3000` e `http://localhost:8000`

### Dados do usuário de teste
`primeiro_usuario@test.com`
`password`

## Objetivos
- [x] Tela de login;
- [x] CRUD completo de usuário;
- [x] Cadastro básico;
- [x] CRUD completo de tarefas;
    - [x] Nome;
    - [x] Data de conclusão;
    - [x] Status;
- [x] Criar um demo e disponibilizar online

#### Todas as telas precisam ser em VueJS;
- [x] As telas de cadastro e edição não podem ser exibidas em modal;
- [x] O Controle de rotas na camada web precisa ser feita com VueRouter;
- [x] Todas as rotas precisam estar protegidas e a autenticação deve ser feita na camada de api stateless com JWT Token;
- [x] O sistema precisa enviar um e-mail sempre que um cadastro for feito, editado ou deletado; -> Não fiz o uso de filas nessa parte para facilitar o deploy da demo no servidor, mas já usei Job e filas em outros projetos.
- [x] O sistema precisa oferecer suporte a criação do primeiro usuário via CLI (`docker exec -it user-manager-front php artisan migrate:fresh --seed`) 

#### Tecnologias que devem ser utilizadas:
- [x] VueJS, VueRouter, Axios. (Qualquer outra peça do ecossistema Vue é permitida Vuex, VueI18n, VueCli e componentes feitos em Vue)
- [x] Não é permitido utilizar jQuery;
- [x] A camada web precisa ser uma SPA;
- [x] O Backend só pode ser acionado na camada de API;
- [x] O Backend deve ser construído no laravel;

<div align="center" style="padding='3%'">
    <img src="imgs/1.png" class="img-fluid" width="95%">
    <img src="imgs/2.png" class="img-fluid" width="95%">
    <img src="imgs/3.png" class="img-fluid" width="95%">
    <img src="imgs/4.png" class="img-fluid" width="95%">
    <img src="imgs/5.png" class="img-fluid" width="95%">
    <img src="imgs/6.png" class="img-fluid" width="95%">
    <img src="imgs/7.png" class="img-fluid" width="95%">
</div>