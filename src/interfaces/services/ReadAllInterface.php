<?php

namespace yii2rails\domain\interfaces\services;

use yii2rails\domain\data\Query;

interface ReadAllInterface {
	
	/**
	 * @param Query|null $query
	 *
	 * @return array|null
	 */
	public function all(Query $query = null);
	
	/**
	 * @param Query|null $query
	 *
	 * @return integer
	 */
	public function count(Query $query = null);
	
}