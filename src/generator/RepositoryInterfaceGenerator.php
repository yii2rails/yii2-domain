<?php

namespace yii2rails\domain\generator;

use yii2rails\domain\interfaces\repositories\CrudInterface;
use yii2rails\extension\code\entities\InterfaceEntity;

class RepositoryInterfaceGenerator extends BaseGenerator {

	public $name;
	public $extends = 'CrudInterface';
	public $defaultUses = [
		['name' => CrudInterface::class],
	];
	
	public function run() {
		$classEntity = new InterfaceEntity();
		$classEntity->name = $this->name;
		$classEntity->extends = $this->extends;
		$this->generate($classEntity);
	}
	
}
