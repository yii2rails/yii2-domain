<?php

namespace yii2rails\domain\behaviors\query;

use yii2rails\domain\data\Query;
use yii2rails\extension\common\helpers\TypeHelper;

class PerPageLimitFilter extends BaseQueryFilter {
	
	public $perPage = 20;
	
	public function prepareQuery(Query $query) {
		$this->setLimit($query);
	}
	
	protected function setLimit(Query $query) {
		$perPage = $query->getParam('per-page', TypeHelper::INTEGER);
		if($perPage > $this->perPage) {
			$query->perPage($this->perPage);
		}
	}
}
