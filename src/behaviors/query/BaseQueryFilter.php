<?php

namespace yii2rails\domain\behaviors\query;

use yii\base\Behavior;
use yii2rails\domain\data\Query;
use yii2rails\domain\enums\EventEnum;
use yii2rails\domain\events\QueryEvent;
use yii2rails\domain\traits\behavior\CallbackTrait;

abstract class BaseQueryFilter extends Behavior {
	
	use CallbackTrait;
	
	abstract public function prepareQuery(Query $query);
	
	public function events() {
		return [
			EventEnum::EVENT_PREPARE_QUERY => 'prepareQueryEvent',
		];
	}
	
	public function prepareQueryEvent(QueryEvent $event) {
		if(!$this->runCallback($event)) {
			$this->prepareQuery($event->query);
		}
	}
	
}
