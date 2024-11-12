# Teste Prático - Creditas
Este é um projeto desenvolvido como parte de um teste prático para a Creditas. Ele consiste em uma aplicação com o objetivo de demonstrar habilidades em desenvolvimento web e manipulação de dados, seguindo os requisitos estabelecidos no teste.

## Passo a passo para rodar o projeto
Clone Repositório
```sh
git clone https://github.com/JulioCavenaghi/teste-pratico-creditas
```

Abra o projeto e atualize as variáveis de ambiente para domínio, banco e e-mail, conforme necessário. Para testes de e-mail, utilizei o Mailtrap: https://mailtrap.io/.
```sh
APP_URL=

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
```

Instale as dependências do projeto:
```sh
composer install
```

Gere a Chave do Projeto Laravel
```sh
php artisan key:generate
```

Crie o banco e popule as tabelas necessárias
```sh
 php artisan migrate --seed
```

## Endpoint para as requisições
Para realizar os testes, utilize o seguinte endpoint: http://seudominio/simularcredito

## Explicação das variáveis da requisição

| Variável             | Obrigatório |      Tipo     | Valor default | Descrição                                             |
|----------------------|-------------|---------------|---------------|-------------------------------------------------------|
| valorCotado          | Sim         |   Numérico    |       -       | O valor que está sendo cotado para o crédito.         |
| prazoEmMeses         | Sim         |   Inteiro     |       -       | Prazo em meses para o pagamento do crédito.           |
| dataNascimento       | Sim         |   Data        |       -       | Data de nascimento do cliente para o cálculo de idade.|
| moeda                | Não         |   String      |      BRL      | Moeda escolhida para a cotação. Opções: BRL, USD, EUR.|
| email                | Não         |   Email       |       -       | E-mail para o envio da cotação.                       |
| taxaEspecial         | Não         |   Numérico    |       -       | Taxa especial, caso queira aplicar uma taxa diferenciada ao cliente, como um desconto.   |
| jurosVariavel        | Não         |   Numérico    |       0       | Taxa de correção aplicada a cada 12 meses, acumulando ao longo do período total do contrato. No final, o juros total acumulado é dividido pelo número total de meses. Exemplo: Em uma simulação de 36 meses com taxa inicial de 5% e jurosVariavel de 1, o contrato terá um reajuste de 1% a cada 12 meses. Isso significa 5% de juros do mês 1 ao 12, 6% do mês 13 ao 24 e 7% do mês 25 ao 36, resultando em um total de 18% de juros acumulado, equivalente a uma média mensal de 0,5%.|

## Exemplo de Body da Requisição
Exemplo de JSON para a requisição:
```sh
{
    "valorCotado": 10000,
    "prazoEmMeses": 36,
    "dataNascimento": "2014-04-25",
    "moeda": "BRL",
    "email":"teste@teste.com",
    "taxaEspecial": 1.99,
    "jurosVariavel":1
}
```

# Explicação das variáveis de resposta
| Variável             | Descrição                                               |
|----------------------|---------------------------------------------------------|
| message              | Mensagem informando o status da requisição.             |
| valorParcela         | Valor final de cada parcela após a simulação.           |
| valorTotal           | Valor total a ser pago pelo crédito simulado.           |
| totalJuros           | Valor total de juros a ser pago pelo crédito simulado.  |
| emailEnviado         | Indica se um e-mail foi enviado ao cliente.             |

# Exemplo de resposta
```sh
{
    "message": "Simulação realizada com sucesso e email enviado!",
    "data": {
        "valorParcela": "R$:290,77",
        "valorTotal": "R$:10.467,72",
        "totalJuros": "R$:467,72",
        "emailEnviado": true
    }
}
```

# Explicação das variáveis de resposta em caso de erro
| Variável      | Descrição                                                        |
|---------------|------------------------------------------------------------------|
| error         | Mensagem informando, de forma mais genérica, onde ocorreu o erro.|
| errorMessage  | Mensagem descrevendo detalhadamente o erro ocorrido.             |

# Exemplo de resposta em caso de erro
```sh
{
    "error": "Falha ao consultar banco de dados.",
    "errorMessage": "SQLSTATE[HY000] [1045] Access denied for user ''@'172.30.0.5' (using password: NO) (Connection: mysql, SQL: select distinct `moeda` from `taxas_juros` where `idade_min` <= 10 and `idade_max` >= 10)"
}
```

