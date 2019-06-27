Схема
===

## Описание

Классы схем нужны в случае, когда у нас много драйверов одного и того же хранилища, и нам требуется централизовано хранить их конфиг схемы.

На текущий момент поддерживается конфигурация:

* связи с другими хранилищами
* список уникальных полей

В будущем настроек будет больше.

## Пример

Указываем в хранилище параметр `schemaClass`:

```php
class CityRepository extends ActiveArRepository {
	
	protected $schemaClass = true;
	
	public function tableName()
	{
		return 'geo_city';
	}
	
}
```

Если параметр `schemaClass` равен `true`, то класс схемы берется по пути:
`domain/repositories/schema/{id}Schema`, где `{id}` - это `id` репозитория.
Заметьте, папка `schema` лежит в той же папке `repositories`, что и папки хранилищ.

Если в параметре `schemaClass` указанно имя класса схемы, то берется указанный класс.

Если параметр `schemaClass` пустой, то схема берется из класса хранилища.

Пример класса схемы:

```php
class CitySchema extends BaseSchema {
	
	public function uniqueFields() {
		return [
			['name'],
		];
	}
	
	public function relations() {
		return [
			'country' => [
				'type' => RelationEnum::ONE,
				'field' => 'country_id',
				'foreign' => [
					'id' => 'geo.country',
					'field' => 'id',
				],
			],
			'region' => [
				'type' => RelationEnum::ONE,
				'field' => 'region_id',
				'foreign' => [
					'id' => 'geo.region',
					'field' => 'id',
				],
			],
		];
	}
	
}
```

Методы:

* uniqueFields - список уникальных полей
* relations - связи

Если надо связать не с хранилищем, а с сервисом, указываем параметр `classType`:

```php
class CitySchema extends BaseSchema {
	
	public function relations() {
		return [
			'country' => [
				'type' => RelationEnum::ONE,
				'field' => 'country_id',
				'classType' => RelationClassTypeEnum::SERVICE,
				'foreign' => [
					'id' => 'geo.country',
					'field' => 'id',
				],
			],
		];
	}
	
}
```

Параметры:

* `type` - тип связи (ко многим или к одному)
* `field` - имя поля в сущности текущего репозитория
* `foreign` - параметры связи с другим хранилищем
	* `id` - идентификатор (формат: `домен.хранилище`)
	* `field` - имя поля в сущности подтягиваемого репозитория

Если не указан параметр `foreign.field`, то по умолчанию он будет равен 'id'.

Можно сделать связь многие ко многим:

```php
class AeticleSchema extends BaseSchema {
	
	public function relations() {
		return [
			'categories' => [
                'type' => RelationEnum::MANY_TO_MANY,
                'via' => [
                    'id' => 'article.categories',
                    'this' => 'article',
                    'foreign' => 'category',
                ],
            ],
		];
	}
	
}
```

Параметры:

* `type` - тип связи (ко многим или к одному)
* `via` - параметры связи с промежуточным хранилищем
	* `id` - идентификатор промежуточного хранилища (формат: `домен.хранилище`)
	* `this` - промежуточное имя реляции для связи с текущим репозиторием
	* `foreign` - промежуточное имя реляции для связи целевым репозиторием

>Note: Обратите внимание: в параметрах `this` и `foreign` указывается не имя поля, а имя реляции, которое описано в промежуточном репозитории

При этом, в промежуточном репозитории должны быть объявлены связи текущий и целевой репозиторий:

```php
```php
class CategoriesSchema extends BaseSchema {
	
	public function relations() {
		return [
			'article' => [
                'type' => RelationEnum::ONE,
                'field' => 'article_id',
                'foreign' => [
                    'id' => 'article.article',
                    'field' => 'id',
                ],
            ],
            'category' => [
                'type' => RelationEnum::ONE,
                'field' => 'category_id',
                'foreign' => [
                    'id' => 'article.category',
                    'field' => 'id',
                ],
            ],
		];
	}
	
}
```
