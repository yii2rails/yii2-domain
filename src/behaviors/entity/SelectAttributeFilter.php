<?php

namespace yii2rails\domain\behaviors\entity;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\events\ReadEvent;

class SelectAttributeFilter extends BaseEntityFilter {
	
	public function prepareContent(BaseEntity $entity, ReadEvent $event) {
		$attributes = $event->query->getParam(Query::SELECT);
		if(empty($attributes)) {
			return;
		}
		$hideAttributes = array_diff($entity->attributes(), $attributes);
		$entity->hideAttributes($hideAttributes);
	}
	
}
