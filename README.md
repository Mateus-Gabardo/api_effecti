# api_effecti
 *Api rest para captação de informações a partir de licitações disponíveis em [SIPAC](https://sig.ifsc.edu.br/public/listaEditais.do?tipo=2&aba=p-editais-atas&buscaTodas=true&acao=544) , através do protocolo HTTP.*

## O QUE ESTA API FAZ: 
- A api capta as informações relevantes das licitações presentes;
- Organiza as informações em um modelo de dados;
- Retorna as informações organizadas em formato JSON.

## INFORMAÇÕES RETORNADAS:
### Modelo de licitações:
- id
- pregão
- status
- localização
- vigência
- descrição
- link dos itens da licitação
- informação sobre a leitura

### Modelo de itens das licitações:
- id
- material
- unidade
- marca
- valor
- descricao

## COMO UTILIZAR:

## Recurso URL
```
http://localhost/api_effecti/api_effecti.php
```

## Informações do recurso

| *Formato de retorno* | *JSON* |
| ------------- | -----:|
| Requer autenticação | Não |

## Parametros

### GET

|*Name* | *Required* | *Description* |
| ------------- |:-------------:| -----|
|  --- | Não  | Retorna os dados da licitação, de acordo com seu modelo|


### POST
| *Name*  |*Required* |*Valor* |*Description* |
| ------------- |:-------------:|:-------------:| -----|
| operacao| Required | busca_licitacoes|Retorna os dados das licitações disponíveis de acordo com o modelo de licitação.|
| operacao| Required | busca_itens | Retorna os dados dos itens referentes a uma licitação específica. O id da licitação com a qual se quer estas informações deve ser passada pelo parametro chave.|
| chave   | Required | 1549 (exemplo) | Utilizada em conjunto com o parametro busca_itens. Refere-se ao id passado na chamada das licitações. Retorna as informações dos itens da licitação requerida. |

## Exemplos
*Busca das licitações*

![busca_licitacoes](https://user-images.githubusercontent.com/92892326/146692992-137a8e07-fc83-4231-8d64-a86090039927.png)

*Busca dos itens de uma licitação a partir do seu código*

![busca_itens](https://user-images.githubusercontent.com/92892326/146692980-6f02c462-693b-4284-90b9-a6bb4c167880.png)


