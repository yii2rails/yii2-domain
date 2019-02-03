<?php

namespace yii2rails\domain\generator;

use yii2rails\domain\repositories\relations\BaseSchema;
use yii2rails\extension\code\entities\ClassEntity;

class RepositorySchemaGenerator extends BaseGenerator {

	public $name;
	public $defaultUses = [
		['name' => BaseSchema::class],
	];
	
	public function run() {
		$classEntity = new ClassEntity();
		$classEntity->name = $this->name;
		$classEntity->extends = 'BaseSchema';
		$classEntity->methods = [
			[
				'name' => 'relations',
			],
		];
		$this->generate($classEntity);
	}
	
}
