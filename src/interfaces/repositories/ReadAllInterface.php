<?php

namespace yii2rails\domain\interfaces\repositories;

use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;

interface ReadAllInterface extends RepositoryInterface {
	
	/**
	 * @param Query|null $query
	 *
	 * @return BaseEntity[]|null
	 */
	public function all(Query $query = null);
	
	/**
	 * @param Query|null $query
	 *
	 * @return integer
	 */
	public function count(Query $query = null);
	
}