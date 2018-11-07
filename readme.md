# Smartpark - API

Public URL: http://smartparkapi-env.52xifpf7uk.us-east-2.elasticbeanstalk.com/api/

## Autenticação

Para autenticar na API, utilize a rota POST /auth/login , enviando no body os campos e-mail e senha. Se o login for bem sucedido, retorna um token de Acesso. Este token deve ser colocado em cada requisição para a API no header Authorization. Ex: Se o token retornado é ABCDEFGH , então o header Authorization ficará assim: 
```json
{
    "Headers" : {
      "Authorization": "ABCDEFGH"
    }
}
```

## Endpoints

| Método   | Path   | Descricao | Input       | Regras |
| ---  | --- | --- | --- | --- 
| GET  | /user/{id} | Retorna informações de usuário |  {} |  |
| POST | /user | Cria um novo usuário |  ``` { nome, email, matricula, senha, tipo } ``` | Todos os campos são obrigatórios. E-mail deve ser um e-mail válido. Matricula deve ter no minimo 10 caracteres. Senha deve ter entre 6 e 16 caracteres. Tipo deve ser A ou P.|
| PUT  | /user/{id} | Edita um usuário | Igual o POST porém sem Tipo. Só precisa mandar o campo que foi alterado. | Mesmas regras do POST porém nenhum campo é obrigatório.|
| DELETE | /user/{id}/ Deleta um usuário |  |  |
| POST | /user/{id}/card | Cria um novo cartão de cŕedito para o usuário {id} | ``` { numero, validade, cvv, bandeira } ``` | Todos os campos são obrigatórios. O número deve ser um cartão válido. A validade deve ser no formato m/y(02/23,03/25,...). cvv deve ser um cvv válido. Bandeira é uma string com o nome da bandeira(Mastercard,Visa,etc)|
| DELETE | /user/{user_id}/card/{card_id} | Deleta um cartão de crédito do usuário |  |  |
| GET | /user/{user_id}/tradings | Retorna uma lista de todas as transações financeiras do usuário |  |  |
| POST | /user/{user_id}/trade | Gera uma nova movimentação(Recarga ou utilização) | ``` {cartao_de_credito_id,valor,tipo}```  | Cartão de crédito deve ser um cartão de crédito do usuário). Valor deve ser numérico, negativo para gastar e positivo para carregar. Tipo deve ser E ou S.|
| GET | /user/{user_id}/balance | Retorna o saldo do usuário {user_id} |  |  |
| POST | /auth/login | Faz login e retorna um token de acesso a API. | ```{email,senha}```  |  |
    
# Retorno

Todo endpoint tem um formato padrão de retorno. Data retorna o objeto desejado, se esse for o caso e mensagem retorna uma mensagem, por exemplo se houver algum erro ou de confirmação.

### Exemplos: 

#### Retorno de GET /user/{id} 
```json
{
    "data": {
        "id": 2,
        "nome": "Jose Henrique",
        "email": "jose.felipetto@pucpr.br",
        "matricula": "301891140730",
        "tipo": "A",
        "created_at": "2018-11-06 14:06:36",
        "updated_at": "2018-11-07 02:23:49",
        "cartao_de_credito": [
            {
                "id": 1,
                "numero": "5431702374307550",
                "validade": "2022-02-06",
                "bandeira": "Mastercard",
                "user_id": 2,
                "created_at": "2018-11-06 15:27:18",
                "updated_at": "2018-11-06 15:27:18"
            }
        ]
    },
    "message": null
}
```

#### Retorno de POST /auth/login
```json
{
    "data": {
        "token": "eyJ0eXAiOirrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrImlhdCI6MTU0MTYyODM4NiwiZXhwIjoxNTU0NTg4Mzg2fQ.-eQPNep3cd8D-HKryfo9AOerYirlgNCKR3FQduqjx20"
    },
    "message": null
}
```

