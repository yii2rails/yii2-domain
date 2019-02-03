<?php

namespace yii2rails\domain\strategies\join;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\dto\WithDto;
use yii2rails\domain\entities\relation\RelationEntity;
use yii2rails\domain\enums\RelationEnum;
use yii2rails\extension\scenario\base\BaseStrategyContextHandlers;
use yii2rails\domain\strategies\join\handlers\One;
use yii2rails\domain\strategies\join\handlers\Many;
use yii2rails\domain\strategies\join\handlers\ManyToMany;
use yii2rails\domain\strategies\join\handlers\HandlerInterface;

/**
 * Class PaymentStrategy
 *
 * @package yii2rails\domain\strategies\payment
 *
 * @property-read HandlerInterface $strategyInstance
 */
class JoinStrategy extends BaseStrategyContextHandlers {
	
	public function getStrategyDefinitions() {
		return [
			RelationEnum::ONE => One::class,
			RelationEnum::MANY => Many::class,
			RelationEnum::MANY_TO_MANY => ManyToMany::class,
		];
	}
	
	public function load(BaseEntity $entity, WithDto $w, $relCollection) : RelationEntity {
		return $this->strategyInstance->load($entity, $w, $relCollection);
	}
	
	public function join(array $collection, RelationEntity $relationEntity) {
		if(empty($collection)) {
			return null;
		}
		return $this->strategyInstance->join($collection, $relationEntity);
	}
	
}