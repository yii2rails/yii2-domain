<?php

namespace yii2rails\domain\traits;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\enums\ActiveMethodEnum;
use yii2rails\domain\enums\EventEnum;
use yii2rails\domain\events\QueryEvent;
use yii2rails\domain\events\ReadEvent;

trait ReadEventTrait {
	
	protected function prepareQuery(Query $query = null) {
		$action = ActiveMethodEnum::READ_ALL;
		$query = Query::forge($query);
		$event = new QueryEvent();
		$event->query = $query;
		$event->activeMethod = $action;
		$this->trigger(EventEnum::EVENT_PREPARE_QUERY, $event);
		return $query;
	}
	
	protected function afterReadTrigger($content, Query $query = null) {
		$query = Query::forge($query);
		$event = new ReadEvent();
		$event->content = $content;
		$event->query = $query;
		$event->activeMethod = $content instanceof BaseEntity ? ActiveMethodEnum::READ_ONE : ActiveMethodEnum::READ_ALL;
		$this->trigger(EventEnum::EVENT_AFTER_READ, $event);
		return $event->content;
	}
	
}