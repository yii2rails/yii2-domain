<?php

namespace yii2rails\domain\interfaces\services;

use yii\data\DataProviderInterface;
use yii2rails\domain\data\Query;

interface ReadPaginationInterface {
	
	/**
	 * @param Query|null $query
	 *
	 * @return DataProviderInterface
	 */
	public function getDataProvider(Query $query = null);
	
}