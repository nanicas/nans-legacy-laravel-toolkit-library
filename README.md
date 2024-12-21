> Atenção: Todos os comandos abaixo deverão ser executados em seu projeto principal.

## Instalar dependência
```
composer require nanicas/legacy-laravel-toolkit-library:dev-main
```

## Configurar o `provider` como gatilho
Edite o arquivo `<app_name>/config/app.php` e adicione a linha:
```
'providers' => [
    \Nanicas\LegacyLaravelToolkit\Providers\BootstrapServiceProvider::class,
]
```

## Executar o comando de publicação dos arquivos de configuração
```
php artisan vendor:publish --tag="legacy_laravel_toolkit:config"
```

Após o comando, favor verificar no diretório `config` (raiz) se os arquivos foram transferidos:
- `nanicas_legacy_laravel_toolkit.php`

## Observações

### Geral
- Para evitar problemas com `namespace`, os arquivos contidos em `_to_copy` estarão comentados, lembre-se de descomentá-los quando for fazer o uso;
- Caso desejar renomear os arquivos copiados (que estava em `_to_copy`), lembre-se de alterar também a referência no arquivo `nanicas_legacy_laravel_toolkit.php`

### Adicionar funções globais

No seu arquivo `composer.json`, configure dessa maneira:

```json
"autoload": {
    "files": [
        "app/Functions/global.php"
    ]
}
```

E, no seu arquivo `app/Functions/global.php`, pode ser feito da seguinte maneira:

```php
include_once __DIR__ . '/../../vendor/nanicas/legacy-laravel-toolkit-library/app/Functions/global.php';
```
