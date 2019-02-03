<?php

namespace yii2rails\domain\strategies\join\handlers;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\dto\WithDto;
use yii2rails\domain\entities\relation\RelationEntity;

interface HandlerInterface {
	
	public function join(array $collection, RelationEntity $relationEntity);
	public function load(BaseEntity $entity, WithDto $w, $relCollection) : RelationEntity;
	
}
