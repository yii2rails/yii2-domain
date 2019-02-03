<?php

namespace yii2rails\domain\generator;

use yii2rails\extension\code\entities\ClassEntity;

class EntityGenerator extends BaseGenerator {

	public $name;
	public $defaultUses = [
		['name' => 'yii2rails\domain\BaseEntity'],
	];
	
	public function run() {
		$classEntity = new ClassEntity();
		$classEntity->name = $this->name;
		$classEntity->extends = 'BaseEntity';
		$this->generate($classEntity);
	}
	
}
