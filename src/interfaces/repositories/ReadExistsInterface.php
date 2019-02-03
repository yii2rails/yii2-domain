<?php

namespace yii2rails\domain\interfaces\repositories;

use yii2rails\domain\data\Query;

interface ReadExistsInterface extends RepositoryInterface {
	
	public function isExists(Query $query);
	public function isExistsById($id);
	
}