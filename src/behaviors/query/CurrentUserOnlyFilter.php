<?php

namespace yii2rails\domain\behaviors\query;

use yii2rails\domain\data\Query;

class CurrentUserOnlyFilter extends BaseQueryFilter {
	
	public $attribute = 'user_id';
    public $fromIdentityAttribute = 'id';
	
	public function prepareQuery(Query $query) {
		$query->removeWhere($this->attribute);
		$currentUserId = \App::$domain->account->auth->identity->{$this->fromIdentityAttribute};
		$query->andWhere([$this->attribute => $currentUserId]);
	}
	
}
