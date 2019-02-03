<?php

namespace yii2rails\domain\generator;

use yii2rails\domain\interfaces\services\CrudInterface;
use yii2rails\extension\code\entities\InterfaceEntity;

class ServiceInterfaceGenerator extends BaseGenerator {

	public $name;
	public $defaultUses = [
		['name' => CrudInterface::class],
	];
	
	public function run() {
		$classEntity = new InterfaceEntity();
		$classEntity->name = $this->name;
		$classEntity->extends = 'CrudInterface';
		$this->generate($classEntity);
	}
	
}
