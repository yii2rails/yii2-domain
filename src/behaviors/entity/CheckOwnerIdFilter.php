<?php

namespace yii2rails\domain\behaviors\entity;

use yii\web\ForbiddenHttpException;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\enums\ActiveMethodEnum;
use yii2rails\domain\events\ReadEvent;

class CheckOwnerIdFilter extends BaseEntityFilter {
	
	public $attribute = 'user_id';
    public $fromIdentityAttribute = 'id';
	
	public function prepareContent(BaseEntity $entity, ReadEvent $event) {
		if($event->activeMethod == ActiveMethodEnum::READ_ONE) {
			$currentUserId = \App::$domain->account->auth->identity->{$this->fromIdentityAttribute};
			$attributeValue = $entity->{$this->attribute};
			if($attributeValue != $currentUserId) {
				throw new ForbiddenHttpException();
			}
		}
	}
	
}
