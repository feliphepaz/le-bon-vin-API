# Le Bon Vin API 🍷

Para criar a API que seria consumida no projeto [Le Bon Vin](https://github.com/feliphepaz/leBonVin) foi usado o banco de dados do WordPress e o plugin JWT Authentication para utilizar o JSON Web Token. Os endpoints aqui criados são o retorno de funções que seriam utilizadas no cardápio:

Requisição | Endpoint | Method | O que faz
:------ | :------ | :------ | :------
user_get | /user | GET | Puxa as informações do usuário
wine_get | /wine | GET | Puxa as informações do vinho
wine_post | /wine | POST | Cria um novo vinho
wine_delete | /wine | DELETE | Deleta o vinho correspondente

## 🍾 Como funciona?
1. Ao acessar a plataforma, a API realiza o `user_get` para chamar e autenticar o usuário que tem permissão do banco de dados. 
2. Agora com o usuário já logado, ele pode criar um vinho. Realizando assim um método POST para a sua criação.
3. Com o respectivo vinho criado, ele consegue acessar as informações do produto com o `wine_get`, passando o ID do vinho pela rota.


