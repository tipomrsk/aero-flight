# aero-flight

## Descrição da API

A API `aero-flight` permite a gestão de voos, incluindo a criação, atualização, consulta e exclusão de informações sobre voos.

## [Outras tecnologias]()

Tem duas coisas aqui um pouco mais específicas que utilizei nesse projeto para a dockerização que são:

[PHP-FPM]() - O PHP-FPM é um gerenciador de processos FastCGI para PHP. Ele é uma alternativa ao PHP mod, que é um módulo do Apache. O PHP-FPM é mais rápido e mais flexível.

[OpCache]() - O OpCache é um sistema de cache do fonte do PHP. Ele armazena o fonte compilado em memória, o que permite que o PHP execute mais rapidamente. Casa muito bem com o FPM.

> É importante salientar que eles já estão configurados e ativos na dockerização. Se não for utilizar o docker, você pode instalar e configurar manualmente. 
> 
> Mas como os aquivos já estão aqui, configurar fica mais fácil. :D

## [Que mais?]()

Mais coisas sobre a aplicação:
1. É disparada um email quando a order é aprovada.
2. Usei Redis para gerenciar a fila, nada mais que isso.
4. É utilizado Service Repository pattern para separa as responsabilidades em camadas, fica mais fácil de ler, dar manutenção...
5. Para o disparo de email, como é só testes utilizei o [MailTrap](https://mailtrap.io/).
6. Tem um Trait pra lidar com os erros e gravar log.
7. Falando em log, foi implementado o log-viewer, bem simples, ta aberto inclusive, sem auth.

## [Documentação da API]()
Pela praticidade, tem a Collection do Postman aqui no repo.

## [Rodando os testes]()

Para rodar os testes, rode o seguinte comando.
Testes criados com [Pest](https://pestphp.com/). Os testes utilizando sqlite em memória pra ganhar performance e testar as regras. 

Ele roda na mesma engine do PHPUnit, então se os testes forem escritos na sintaxe do PHPUnit rodariam no mesmo comando, eu particularmente acho o Pest mais elegante, por isso prefiro.

```bash
  ./vendor/bin/pest
```




