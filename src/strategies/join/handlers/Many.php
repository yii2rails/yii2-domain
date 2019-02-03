<?php

namespace yii2rails\domain\strategies\join\handlers;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\dto\WithDto;
use yii2rails\domain\entities\relation\RelationEntity;
use yii2rails\domain\helpers\repository\RelationRepositoryHelper;
use yii2rails\extension\arrayTools\helpers\ArrayIterator;

class Many extends Base implements HandlerInterface {
	
	public function join(array $collection, RelationEntity $relationEntity) {
		$values = self::getColumn($collection, $relationEntity->field);
		$query = Query::forge();
		$query->where($relationEntity->foreign->field, $values);
		$relCollection = RelationRepositoryHelper::getAll($relationEntity->foreign, $query);
		return $relCollection;
	}
	
	public function load(BaseEntity $entity, WithDto $w, $relCollection): RelationEntity {
		$fieldValue = $entity->{$w->relationConfig->field};
		if(empty($fieldValue)) {
			return $w->relationConfig;
		}
		$query = Query::forge();
		$query->where($w->relationConfig->foreign->field, $fieldValue);
		$data = ArrayIterator::allFromArray($query, $relCollection);
		$data = self::prepareValue($data, $w);
		$entity->{$w->relationName} = $data;
		return $w->relationConfig;
	}
	
}