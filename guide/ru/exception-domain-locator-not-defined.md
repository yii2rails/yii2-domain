Domain Locator Not Defined
===

## Описание

Прблема сервисной шины.

Не объявлен объект домен-локатора.

## Решение

Объявить конфигурацию сценария `DefineDomainLocator` в приложении.

Конфигурация находится тут `common/config/env.php`.

В секции `app.commands` должен быть объявлен сценарий `DefineDomainLocator`:

```php
[
		'class' => 'yii2rails\domain\filters\DefineDomainLocator',
		'filters' => [
				[
						'class' => LoadDomainConfig::class,
						'app' => COMMON,
						'name' => 'domains',
						'withLocal' => true,
				],
		],
],
```
