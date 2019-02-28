<?php

namespace yii2rails\domain\interfaces\repositories;

use yii2rails\domain\data\Query;

interface ReadPaginationInterface extends RepositoryInterface {

    /**
     * @param Query|null $query
     * @return DataProviderInterface
     */
	public function getDataProvider(Query $query = null);
	
}