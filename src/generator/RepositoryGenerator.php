<?php

namespace yii2rails\domain\generator;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2rails\extension\code\entities\ClassEntity;
use yii2rails\extension\code\enums\AccessEnum;

class RepositoryGenerator extends BaseGenerator {

	public $name;
	public $defaultUses = [
		['name' => BaseActiveArRepository::class],
	];
	
	public function run() {
		$classEntity = new ClassEntity();
		$classEntity->name = $this->name;
		$classEntity->extends = 'BaseActiveArRepository';
		$classEntity->variables = [
			[
				'name' => 'schemaClass',
				'access' => AccessEnum::PROTECTED,
				'value' => 'true',
			],
		];
		$this->generate($classEntity);
	}
	
}
