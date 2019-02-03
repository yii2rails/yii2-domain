<?php

namespace yii2rails\domain\generator;

use yii2rails\domain\services\base\BaseActiveService;
use yii2rails\extension\code\entities\ClassEntity;

class ServiceGenerator extends BaseGenerator {

	public $name;
	public $defaultUses = [
		['name' => BaseActiveService::class],
	];
	
	public function run() {
		$classEntity = new ClassEntity();
		$classEntity->name = $this->name;
		$classEntity->extends = 'BaseActiveService';
		$this->generate($classEntity);
	}
	
}
