<?php

namespace yii2rails\domain\behaviors\query;

use yii2rails\domain\data\Query;

/**
 * Class QueryFilter
 *
 * [
 * 'class' => QueryFilter::class,
 * 'method' => 'with',
 * 'value' => 'fields',
 * ],
 * [
 * 'class' => QueryFilter::class,
 * 'method' => 'addOrderBy',
 * 'value' => ['priority' => SORT_DESC],
 * 'callback' => function(Query $query) {
 * $query->addOrderBy(['sort' => SORT_ASC]);
 * }
 * ],
 *
 * @package yii2rails\domain\behaviors\query
 */
class QueryOrderFilter extends BaseQueryFilter {
	
	public $columns;
	
	public function prepareQuery(Query $query) {
		$query->orderBy($this->columns);
	}
	
}
