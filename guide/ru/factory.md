Фабрика
===

## Создание сущности

```php
$identity = [
	'id' => 123,
	'login' => 'qwerty',
];
$identityEntity = \App::$domain->account->factory->entity->create('identity', $identity);
```

или

```php
$identity = [
	'id' => 123,
	'login' => 'qwerty',
];
$identityEntity = new IdentityEntity($identity);
```

или

```php
$identityEntity = new IdentityEntity;
$identityEntity->id = 123;
$identityEntity->login = 'qwerty';
```

## Cоздание репозитория

```php
use yii2rails\domain\helpers\factory\RepositoryFactoryHelper;

$loginArRepository = RepositoryFactoryHelper::createObject('identity', Driver::ACTIVE_RECORD, \App::$domain->account);
$loginRestRepository = RepositoryFactoryHelper::createObject('identity', Driver::REST, \App::$domain->account);
```

В этом примере созданы два независимых драйвера репозитория.
Такое может потребоваться, когда мы кешируем данные из удаленного источника в локалное хранилище.

## Cоздание сервиса

```php
use yii2rails\domain\helpers\factory\ServiceFactoryHelper;

$loginService = ServiceFactoryHelper::createObject('identity', null, \App::$domain->account);
```

или

```php
$loginService = \App::$domain->account->factory->service->create('identity');
```