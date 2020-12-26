## Inicialização

* Verificar que o `.env` está atualizado, (base de dados, urls) verificar `.env.example`
* Verificar que o `config.js` está atualizado, verificar `config.example.js`
* Realizar o link da storage `php artisan storage:link`
* Definir a cache das configurações: `php artisan config:cache`


## Troubleshooting

Se os pedidos falharem com "Server Error", verificar no /storage/logs/laravel.log, se estiver o erro `production.ERROR: No application encryption key has been specified.` têm de se correr o comando: `php artisan key:generate`
